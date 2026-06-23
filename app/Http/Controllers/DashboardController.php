<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Contract;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $cards = [
            [
                'title' => 'Total Active Contracts',
                'value' => Contract::where('status', 'active')->count(),
                'unit' => 'Contracts',
                'desc' => 'All active contracts',
                'type' => 'blue',
                'icon' => 'DOC'
            ],
            [
                'title' => 'Expiring < 7 Days',
                'value' => Contract::whereBetween(
                    'end_date',
                    [now(), now()->addDays(7)]
                )->count(),
                'unit' => 'Contracts',
                'desc' => 'Immediate action required',
                'type' => 'red',
                'icon' => 'EXP'
            ],
            [
                'title' => 'Expiring < 30 Days',
                'value' => Contract::whereBetween(
                    'end_date',
                    [now(), now()->addDays(30)]
                )->count(),
                'unit' => 'Contracts',
                'desc' => 'Follow up soon',
                'type' => 'yellow',
                'icon' => 'REM'
            ],
            [
                'title' => 'Outstanding Invoices',
                'value' => number_format(
                    Billing::sum('amount'),
                    0,
                    ',',
                    '.'
                ),
                'unit' => 'Rupiah',
                'desc' => Billing::count().' invoices',
                'type' => 'green',
                'icon' => 'INV'
            ],
        ];

        $contracts = Contract::with('owner')
            ->orderBy('end_date')
            ->take(5)
            ->get()
            ->map(function ($contract) {

                $daysLeft = now()->diffInDays(
                    $contract->end_date,
                    false
                );

                return [
                    'company' => $contract->contract_name,
                    'package' => $contract->contract_number,
                    'am' => $contract->owner?->name ?? '-',
                    'end_date' => $contract->end_date
                        ? $contract->end_date->format('d/m/Y')
                        : '-',
                    'price' => '-',
                    'days_left' => $daysLeft.' days left',
                    'status' => $daysLeft <= 7
                        ? 'danger'
                        : 'warning',
                ];
            });

        $summaries = User::where(
                'role_id',
                User::ROLE_ACCOUNT_MANAGER
            )
            ->with('ownedContracts')
            ->get()
            ->map(function ($user) {

                return [
                    'name' => $user->name,
                    'clients' => $user->ownedContracts->count(),
                    'active' => $user->ownedContracts
                        ->where('status', 'active')
                        ->count(),
                    'expiring' => $user->ownedContracts
                        ->filter(function ($contract) {
                            return $contract->end_date &&
                                $contract->end_date <= now()->addDays(30);
                        })
                        ->count(),
                    'value' => '-',
                    'billing' => '-',
                ];
            });

        $expiring30Days = Contract::whereBetween(
            'end_date',
            [now(), now()->addDays(30)]
        )->count();

        return view(
            'dashboard.index',
            compact(
                'cards',
                'contracts',
                'summaries',
                'expiring30Days'
            )
        );
    }
}