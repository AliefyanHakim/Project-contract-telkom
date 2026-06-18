<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts.
     */
public function index(Request $request)
{
    $query = Contract::with([
        'owner',
        'creator'
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Account Manager hanya melihat kontraknya sendiri
    |--------------------------------------------------------------------------
    */
    if ($user->isAccountManager()) {

        $query->where(
            'owner_am_id',
            $user->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search    ) {

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
    | Filter Account Manager
    |--------------------------------------------------------------------------
    */
    if (
        $request->filled('account_manager')
        && !$user->isAccountManager()
    ) {

        $query->where(
            'owner_am_id',
            $request->account_manager
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Status
    |--------------------------------------------------------------------------
    */
    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }

    $contracts = $query
        ->orderBy('end_date')
        ->paginate(15)
        ->withQueryString();

    $accountManagers = User::where(
        'role_id',
        User::ROLE_ACCOUNT_MANAGER
    )
    ->where('status', 'active')
    ->orderBy('name')
    ->get();

    return view(
        'contracts-list',
        compact(
            'contracts',
            'accountManagers'
        )
    );
}

    /**
     * Show form create contract.
     */
    public function create()
    {
        $accountManagers = User::where(
            'role_id',
            User::ROLE_ACCOUNT_MANAGER
        )
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

        return view(
            'contracts.create',
            compact('accountManagers')
        );
    }

    /**
     * Store contract.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_number' => [
                'nullable',
                'unique:contracts,contract_number'
            ],
            'contract_name' => [
                'required',
                'max:255'
            ],
            'owner_am_id' => [
                'required',
                'exists:users,id'
            ],
            'start_date' => [
                'required',
                'date'
            ],

            'end_date' => [
                'required',
                'date',
                'after:start_date'
            ],
        ]);

        $validated['created_by']
            = Auth::id();

        $validated['status']
            = 'active';

        Contract::create($validated);

        return redirect()
            ->route('contracts.index')
            ->with(
                'success',
                'Contract created successfully.'
            );
    }

    /**
     * Show contract detail.
     */
    public function show(Contract $contract)
    {
        $contract->load([
            'owner',
            'creator',
            'files',
            'notes',
            'billings',
            'extensions',
            'transferRequests',
            'transferHistory'
        ]);

        return view(
            'contracts.show',
            compact('contract')
        );
    }
}