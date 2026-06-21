<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Contract;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AmContractsExport;

class AmController extends Controller
{
    public function show(
        Request $request,
        User $user
    )
    {
        abort_unless(
            $user->isAccountManager(),
            404
        );

        $query = Contract::with([
            'services.service'
        ])
        ->where(
            'owner_am_id',
            $user->id
        );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'contract_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'contract_number',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Service Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('service')) {

            $query->whereHas(
                'services',
                function ($q) use ($request) {

                    $q->where(
                        'service_id',
                        $request->service
                    );
                }
            );
        }

        $contracts = $query
            ->orderBy('end_date')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        */

        $activeCount = Contract::where(
            'owner_am_id',
            $user->id
        )
        ->where('status', 'active')
        ->count();

        $criticalCount = Contract::where(
            'owner_am_id',
            $user->id
        )
        ->where('status', 'followup')
        ->count();

        $expiringCount = Contract::where(
            'owner_am_id',
            $user->id
        )
        ->where('status', 'expiring')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Dropdown AM
        |--------------------------------------------------------------------------
        */

        $accountManagers = User::where(
            'role_id',
            User::ROLE_ACCOUNT_MANAGER
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        $services = Service::orderBy(
            'service_name'
        )->get();

        return view(
            'am.detail-am',
            compact(
                'user',
                'contracts',
                'activeCount',
                'criticalCount',
                'expiringCount',
                'accountManagers',
                'services'
            )
        );
    }
    
    public function export(User $user)
    {
        if (!$user->isAccountManager()) {

            abort(404);
        }

        return Excel::download(
            new AmContractsExport($user->id),
            'AM_'.$user->name.'_Contracts.xlsx'
        );
    }
}