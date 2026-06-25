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

        /*
        |--------------------------------------------------------------------------
        | Contract KPI
        |--------------------------------------------------------------------------
        | AM       → kontraknya sendiri.
        | Manager  → global.
        | Inputter → global.
        | Paycall  → global.
        */
        $baseContracts = Contract::query();
        $this->applyContractScope($baseContracts, $user);

        $activeCount = (clone $baseContracts)
            ->where('status', 'active')
            ->count();

        $criticalCount = (clone $baseContracts)
            ->where('status', 'followup')
            ->count();

        $expiring30Days = (clone $baseContracts)
            ->whereBetween('end_date', [
                now()->toDateString(),
                now()->copy()->addDays(30)->toDateString(),
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Billing KPI
        |--------------------------------------------------------------------------
        */
        $billingQuery = Billing::with([
                'contract.owner',
            ])
            ->whereIn('payment_status', [
                'pending',
                'overdue',
            ]);

        $this->applyBillingScope($billingQuery, $user);

        $outstandingAmount = (clone $billingQuery)->sum('amount');
        $outstandingCount = (clone $billingQuery)->count();
        $pendingCount = (clone $billingQuery)->where('payment_status', 'pending')->count();
        $overdueCount = (clone $billingQuery)->where('payment_status', 'overdue')->count();

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

        /*
        |--------------------------------------------------------------------------
        | Contracts Near Expiration
        |--------------------------------------------------------------------------
        | AM       → kontraknya sendiri.
        | Inputter → semua kontrak seluruh AM.
        | Paycall  → semua kontrak seluruh AM.
        | Manager  → semua kontrak.
        */
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

        /*
        |--------------------------------------------------------------------------
        | Summary by Account Manager
        |--------------------------------------------------------------------------
        | Dipakai untuk Manager dan AM.
        | Inputter dan Paycall di Blade akan pakai Billing Overview.
        */
        $summaryUsersQuery = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active');

        if ($user->isAccountManager()) {
            $summaryUsersQuery->where('id', $user->id);
        }

        $summaries = $summaryUsersQuery
            ->orderBy('name')
            ->get()
            ->map(function ($am) {
                $contracts = $am->ownedContracts()
                    ->with('services.service')
                    ->get();

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
                    'active' => $contracts->where('status', 'active')->count(),
                    'expiring' => $contracts->whereIn('status', ['expiring', 'followup'])->count(),
                    'value' => 'Rp ' . number_format($monthlyValue, 0, ',', '.'),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Billing Overview Panel
        |--------------------------------------------------------------------------
        | Dipakai untuk Inputter dan Paycall.
        */
        $billingRowsQuery = Billing::with([
                'contract.owner',
            ])
            ->whereIn('payment_status', [
                'pending',
                'overdue',
            ]);

        $this->applyBillingScope($billingRowsQuery, $user);

        $billingRows = $billingRowsQuery
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($billing) {
                return [
                    'client' => $billing->contract?->contract_name ?? '-',
                    'contract_number' => $billing->contract?->contract_number ?? '-',
                    'am' => $billing->contract?->owner?->name ?? '-',
                    'period' => $billing->billing_period ?? '-',
                    'amount' => 'Rp ' . number_format($billing->amount ?? 0, 0, ',', '.'),
                    'status' => $billing->payment_status ?? '-',
                    'due_date' => $billing->due_date
                        ? \Carbon\Carbon::parse($billing->due_date)->format('d/m/Y')
                        : '-',
                ];
            });

        $billingSummary = [
            'outstanding_amount' => 'Rp ' . number_format($outstandingAmount, 0, ',', '.'),
            'outstanding_count' => $outstandingCount,
            'pending_count' => $pendingCount,
            'overdue_count' => $overdueCount,
        ];

        return view('dashboard.index', compact(
            'cards',
            'contracts',
            'summaries',
            'billingRows',
            'billingSummary',
            'expiring30Days'
        ));
    }

    private function applyContractScope(Builder $query, User $user): void
    {
        if ($user->isAccountManager()) {
            $query->where('owner_am_id', $user->id);
        }

        /*
        |--------------------------------------------------------------------------
        | Inputter dan Paycall tidak difilter.
        |--------------------------------------------------------------------------
        | Dashboard mereka global seperti Manager.
        */
    }

    private function applyBillingScope(Builder $query, User $user): void
    {
        if ($user->isAccountManager()) {
            $query->whereHas('contract', function ($contractQuery) use ($user) {
                $contractQuery->where('owner_am_id', $user->id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Inputter dan Paycall tidak difilter.
        |--------------------------------------------------------------------------
        | Billing dashboard mereka global.
        */
    }
}