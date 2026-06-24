<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $baseContracts = Contract::query();
        $this->applyContractScope($baseContracts, $user);

        $activeCount = (clone $baseContracts)
            ->where('status', 'active')
            ->count();

        $criticalCount = (clone $baseContracts)
            ->where('status', 'followup')
            ->count();

        $expiring7Days = (clone $baseContracts)
            ->whereBetween('end_date', [
                now()->toDateString(),
                now()->copy()->addDays(7)->toDateString(),
            ])
            ->count();

        $expiring30Days = (clone $baseContracts)
            ->whereBetween('end_date', [
                now()->toDateString(),
                now()->copy()->addDays(30)->toDateString(),
            ])
            ->count();

        $billingQuery = Billing::query()
            ->whereIn('payment_status', [
                'pending',
                'overdue',
            ]);

        $this->applyBillingScope($billingQuery, $user);

        $outstandingAmount = (clone $billingQuery)->sum('amount');
        $outstandingCount = (clone $billingQuery)->count();

        $cards = [
            [
                'title' => 'Total Active Contracts',
                'value' => $activeCount,
                'unit' => 'Contracts',
                'desc' => 'Contracts in healthy status',
                'type' => 'blue',
                'icon' => 'DOC',
            ],
            [
                'title' => 'Critical Follow-up',
                'value' => $criticalCount,
                'unit' => 'Contracts',
                'desc' => 'Need immediate follow-up',
                'type' => 'red',
                'icon' => 'EXP',
            ],
            [
                'title' => 'Expiring < 30 Days',
                'value' => $expiring30Days,
                'unit' => 'Contracts',
                'desc' => 'Follow up soon',
                'type' => 'yellow',
                'icon' => 'REM',
            ],
            [
                'title' => 'Outstanding Invoices',
                'value' => number_format($outstandingAmount, 0, ',', '.'),
                'unit' => 'Rupiah',
                'desc' => $outstandingCount . ' invoices',
                'type' => 'green',
                'icon' => 'INV',
            ],
        ];

        $nearExpirationQuery = Contract::with([
                'owner',
                'services.service',
            ])
            ->whereBetween('end_date', [
                now()->toDateString(),
                now()->copy()->addDays(30)->toDateString(),
            ]);

        $this->applyContractScope($nearExpirationQuery, $user);

        $contracts = $nearExpirationQuery
            ->orderBy('end_date')
            ->take(5)
            ->get()
            ->map(function ($contract) {
                $daysLeft = now()
                    ->startOfDay()
                    ->diffInDays($contract->end_date, false);

                $monthlyValue = $contract->services->sum(function ($contractService) {
                    return (float) (
                        $contractService->monthly_fee
                        ?? $contractService->service?->monthly_fee
                        ?? 0
                    );
                });

                return [
                    'id' => $contract->id,
                    'company' => $contract->contract_name,
                    'package' => $contract->contract_number,
                    'am' => $contract->owner?->name ?? '-',
                    'end_date' => $contract->end_date
                        ? $contract->end_date->format('d/m/Y')
                        : '-',
                    'price' => 'Rp ' . number_format($monthlyValue, 0, ',', '.'),
                    'days_left' => $daysLeft . ' days left',
                    'status' => $daysLeft <= 7 ? 'danger' : 'warning',
                ];
            });

        $summaryUsersQuery = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active');

        if ($user->isAccountManager()) {
            $summaryUsersQuery->where('id', $user->id);
        }

        $summaries = $summaryUsersQuery
            ->orderBy('name')
            ->get()
            ->map(function ($am) use ($user) {
                $contractsQuery = $am->ownedContracts()
                    ->with('services.service');

                if ($user->isSupportInputter()) {
                    $contractsQuery->where('created_by', $user->id);
                }

                $contracts = $contractsQuery->get();

                $monthlyValue = $contracts->sum(function ($contract) {
                    return $contract->services->sum(function ($contractService) {
                        return (float) (
                            $contractService->monthly_fee
                            ?? $contractService->service?->monthly_fee
                            ?? 0
                        );
                    });
                });

                return [
                    'name' => $am->name,
                    'clients' => $contracts->count(),
                    'active' => $contracts
                        ->where('status', 'active')
                        ->count(),
                    'expiring' => $contracts
                        ->whereIn('status', ['expiring', 'followup'])
                        ->count(),
                    'value' => 'Rp ' . number_format($monthlyValue, 0, ',', '.'),
                ];
            });

        return view('dashboard.index', compact(
            'cards',
            'contracts',
            'summaries',
            'expiring30Days'
        ));
    }

    private function applyContractScope(Builder $query, User $user): void
    {
        if ($user->isAccountManager()) {
            $query->where('owner_am_id', $user->id);
        }

        if ($user->isSupportInputter()) {
            $query->where('created_by', $user->id);
        }
    }

    private function applyBillingScope(Builder $query, User $user): void
    {
        if ($user->isAccountManager()) {
            $query->whereHas('contract', function ($contractQuery) use ($user) {
                $contractQuery->where('owner_am_id', $user->id);
            });
        }

        if ($user->isSupportInputter()) {
            $query->whereHas('contract', function ($contractQuery) use ($user) {
                $contractQuery->where('created_by', $user->id);
            });
        }
    }
}