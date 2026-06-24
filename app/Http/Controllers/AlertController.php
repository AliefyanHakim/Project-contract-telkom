<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAccountManager()) {
            abort(403, 'Contract Alerts hanya untuk Account Manager.');
        }

        $query = Contract::with([
            'owner',
            'services.service',
        ])
        ->where('owner_am_id', $user->id)
        ->whereIn('status', [
            'expiring',
            'followup',
            'expired',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('contract_name', 'like', "%{$search}%")
                    ->orWhere('contract_number', 'like', "%{$search}%");
            });
        }

        $contracts = $query
            ->orderBy('end_date')
            ->paginate(10)
            ->withQueryString();

        $summaryBaseQuery = Contract::query()
            ->where('owner_am_id', $user->id);

        $summary = [
            'active' => (clone $summaryBaseQuery)
                ->where('status', 'active')
                ->count(),

            'critical' => (clone $summaryBaseQuery)
                ->where('status', 'followup')
                ->count(),

            'expiring' => (clone $summaryBaseQuery)
                ->where('status', 'expiring')
                ->count(),
        ];

        return view('alerts.contract-alerts', compact(
            'contracts',
            'summary'
        ));
    }

        public function exportReport(Request $request): StreamedResponse
{
    $user = Auth::user();

    if (!$user->isAccountManager()) {
        abort(403, 'Contract Alerts report hanya untuk Account Manager.');
    }

    $query = Contract::with([
        'owner',
        'services.service',
    ])
    ->where('owner_am_id', $user->id)
    ->whereIn('status', [
        'expiring',
        'followup',
        'expired',
    ]);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('contract_name', 'like', "%{$search}%")
                ->orWhere('contract_number', 'like', "%{$search}%");
        });
    }

    return response()->streamDownload(function () use ($query) {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Client Name',
            'Contract Number',
            'Account Manager',
            'Package / Services',
            'Start Date',
            'End Date',
            'Alert Status',
            'Days Left',
            'Monthly Value',
        ]);

        $query->orderBy('end_date')->chunk(200, function ($contracts) use ($handle) {
            foreach ($contracts as $contract) {
                $services = $contract->services
                    ? $contract->services->pluck('service.service_name')->implode(', ')
                    : '-';

                $monthlyValue = $contract->services
                    ? $contract->services->sum(function ($contractService) {
                        return (float) (
                            $contractService->monthly_fee
                            ?? $contractService->service?->monthly_fee
                            ?? 0
                        );
                    })
                    : 0;

                $statusLabel = match ($contract->status) {
                    'expiring' => 'Expiring Soon',
                    'followup' => 'Critical Follow-up',
                    'expired' => 'Expired',
                    default => ucfirst($contract->status ?? '-'),
                };

                $daysLeft = $contract->end_date
                    ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($contract->end_date), false)
                    : null;

                fputcsv($handle, [
                    $contract->contract_name ?? '-',
                    $contract->contract_number ?? '-',
                    $contract->owner?->name ?? '-',
                    $services ?: '-',
                    $contract->start_date
                        ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y')
                        : '-',
                    $contract->end_date
                        ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y')
                        : '-',
                    $statusLabel,
                    $daysLeft !== null ? $daysLeft . ' days' : '-',
                    $monthlyValue,
                ]);
            }
        });

        fclose($handle);
    }, 'My_Contract_Alerts_Report.csv', [
        'Content-Type' => 'text/csv',
    ]);
    }
}