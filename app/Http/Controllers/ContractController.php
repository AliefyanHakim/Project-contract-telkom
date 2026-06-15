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
    public function index()
    {
        $query = Contract::with([
            'owner',
            'creator'
        ]);
        
    /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAccountManager()) {
            $query->where(
                'owner_am_id',
                $user->id
            );
        }

        $contracts = $query
            ->latest()
            ->paginate(20);

        return view(
            'contracts.index',
            compact('contracts')
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