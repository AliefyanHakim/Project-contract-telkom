<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractTransferHistory;
use App\Models\ContractTransferRequest;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function requests(Request $request)
    {
        $query = ContractTransferRequest::with([
            'contract.services.service',
            'requester',
            'currentAM',
            'targetAM',
            'approver',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('contract', function ($contractQuery) use ($search) {
                    $contractQuery->where('contract_name', 'like', "%{$search}%")
                        ->orWhere('contract_number', 'like', "%{$search}%");
                })
                ->orWhereHas('currentAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('targetAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $transferRequests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transfer.transfer-request', compact('transferRequests'));
    }

    public function directHistory(Request $request)
    {
        $user = Auth::user();

        $query = ContractTransferHistory::with([
            'contract.services.service',
            'fromAM',
            'toAM',
            'transferredBy',
        ]);

        if ($user->isManager()) {
            $query->where('transfer_type', 'direct');
        }

        if ($user->isAccountManager()) {
            $query->where('transfer_type', 'approved_request')
                ->where(function ($q) use ($user) {
                    $q->where('from_am_id', $user->id)
                        ->orWhere('to_am_id', $user->id);
                });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('contract', function ($contractQuery) use ($search) {
                    $contractQuery->where('contract_name', 'like', "%{$search}%")
                        ->orWhere('contract_number', 'like', "%{$search}%");
                })
                ->orWhereHas('fromAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('toAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $directTransfers = $query
            ->latest('transfer_date')
            ->paginate(10)
            ->withQueryString();

        $contracts = Contract::with('owner')
            ->orderBy('contract_name')
            ->get();

        $accountManagers = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('transfer.direct-transfer', compact(
            'directTransfers',
            'contracts',
            'accountManagers'
        ));
    }

    public function create(Request $request)
{
    $user = Auth::user();

    $contracts = Contract::with([
        'owner',
        'services.service',
    ])
    ->when($user->isAccountManager(), function ($query) use ($user) {
        $query->where('owner_am_id', $user->id);
    })
    ->when($request->filled('search'), function ($query) use ($request) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('contract_name', 'like', "%{$search}%")
                ->orWhere('contract_number', 'like', "%{$search}%")
                ->orWhereHas('owner', function ($ownerQuery) use ($search) {
                    $ownerQuery->where('name', 'like', "%{$search}%");
                });
        });
    })
    ->orderBy('contract_name')
    ->get();

    $accountManagers = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    return view('transfer.transfer-contract', compact(
        'contracts',
        'accountManagers'
    ));
}

    public function store(Request $request)
{
    $user = Auth::user();

    if (!$user->isManager() && !$user->isAccountManager()) {
        abort(403, 'Anda hanya boleh melihat data transfer.');
    }

    $validated = $request->validate([
        'contract_id' => [
            'required',
            'exists:contracts,id',
        ],
        'target_am_id' => [
            'required',
            'exists:users,id',
        ],
        'reason' => [
            'nullable',
            'string',
        ],
    ]);

    $contract = Contract::findOrFail($validated['contract_id']);

    if ($user->isAccountManager() && $contract->owner_am_id !== $user->id) {
        abort(403, 'Anda tidak memiliki akses ke kontrak ini.');
    }

    if ((int) $contract->owner_am_id === (int) $validated['target_am_id']) {
        return back()
            ->withInput()
            ->with('error', 'Target Account Manager tidak boleh sama dengan AM saat ini.');
    }

    if ($user->isManager()) {
        DB::transaction(function () use ($contract, $validated) {
            $oldAmId = $contract->owner_am_id;
            $newAmId = $validated['target_am_id'];

            $contract->update([
                'owner_am_id' => $newAmId,
            ]);

            ContractTransferHistory::create([
                'contract_id' => $contract->id,
                'from_am_id' => $oldAmId,
                'to_am_id' => $newAmId,
                'transferred_by' => Auth::id(),
                'transfer_type' => 'direct',
                'notes' => $validated['reason'] ?? 'Direct transfer by Manager.',
                'transfer_date' => now(),
            ]);

            ActivityLogger::log(
                'Direct Transfer',
                'Manager melakukan direct transfer kontrak ' . $contract->contract_number
            );
        });

        return redirect('/direct-transfer')
            ->with('success', 'Direct transfer kontrak berhasil dilakukan.');
    }

    ContractTransferRequest::create([
        'contract_id' => $contract->id,
        'requested_by' => $user->id,
        'current_am_id' => $contract->owner_am_id,
        'target_am_id' => $validated['target_am_id'],
        'reason' => $validated['reason'] ?? null,
        'status' => 'pending',
    ]);

    ActivityLogger::log(
        'Transfer Request',
        'AM membuat request transfer kontrak ' . $contract->contract_number
    );

    return redirect('/transfer-request')
        ->with('success', 'Request transfer kontrak berhasil dibuat.');
}

    public function showApproval(ContractTransferRequest $transferRequest)
    {
        if (!Auth::user()->isManager()) {
            abort(403, 'Hanya Manager yang boleh membuka detail approval transfer.');
        }

        $transferRequest->load([
            'contract.services.service',
            'requester',
            'currentAM',
            'targetAM',
            'approver',
        ]);

        return view('transfer.acceptreject-transfer', compact('transferRequest'));
    }

        public function acceptedRequests(Request $request)
        {
        $query = ContractTransferRequest::with([
            'contract.services.service',
            'requester',
            'currentAM',
            'targetAM',
            'approver',
        ])
        ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('contract', function ($contractQuery) use ($search) {
                    $contractQuery->where('contract_name', 'like', "%{$search}%")
                        ->orWhere('contract_number', 'like', "%{$search}%");
                })
                ->orWhereHas('currentAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('targetAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $transferRequests = $query
            ->latest('approved_at')
            ->paginate(10)
            ->withQueryString();

        return view('transfer.accepted-transfer', compact('transferRequests'));
    }

    public function rejectedRequests(Request $request)
    {
        $query = ContractTransferRequest::with([
            'contract.services.service',
            'requester',
            'currentAM',
            'targetAM',
            'approver',
        ])
        ->where('status', 'rejected');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('contract', function ($contractQuery) use ($search) {
                    $contractQuery->where('contract_name', 'like', "%{$search}%")
                        ->orWhere('contract_number', 'like', "%{$search}%");
                })
                ->orWhereHas('currentAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('targetAM', function ($amQuery) use ($search) {
                    $amQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $transferRequests = $query
            ->latest('approved_at')
            ->paginate(10)
            ->withQueryString();

        return view('transfer.rejected-transfer', compact('transferRequests'));
    }

    public function approve(ContractTransferRequest $transferRequest)
    {
        if (!Auth::user()->isManager()) {
            abort(403, 'Hanya Manager yang boleh menyetujui transfer.');
        }

        if ($transferRequest->status !== 'pending') {
            return back()->with('error', 'Request transfer ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($transferRequest) {
            $transferRequest->load('contract');

            $contract = $transferRequest->contract;

            $oldAmId = $transferRequest->current_am_id;
            $newAmId = $transferRequest->target_am_id;

            $contract->update([
                'owner_am_id' => $newAmId,
            ]);

            $transferRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            ContractTransferHistory::create([
                'contract_id' => $contract->id,
                'from_am_id' => $oldAmId,
                'to_am_id' => $newAmId,
                'transferred_by' => Auth::id(),
                'transfer_type' => 'approved_request',
                'notes' => $transferRequest->reason ?? 'Transfer request approved by Manager.',
                'transfer_date' => now(),
            ]);

            ActivityLogger::log(
                'Transfer Request',
                'Manager menyetujui transfer kontrak ' . $contract->contract_number
            );
        });

        return redirect('/transfer-request')
            ->with('success', 'Transfer request berhasil disetujui.');
    }

    public function reject(ContractTransferRequest $transferRequest)
    {
        if (!Auth::user()->isManager()) {
            abort(403, 'Hanya Manager yang boleh menolak transfer.');
        }

        if ($transferRequest->status !== 'pending') {
            return back()->with('error', 'Request transfer ini sudah diproses sebelumnya.');
        }

        $transferRequest->load('contract');

        $transferRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        ActivityLogger::log(
            'Transfer Request',
            'Manager menolak transfer kontrak ' . optional($transferRequest->contract)->contract_number
        );

        return redirect('/transfer-request')
            ->with('success', 'Transfer request berhasil ditolak.');
    }

    public function directStore(Request $request)
    {
        if (!Auth::user()->isManager()) {
            abort(403, 'Hanya Manager yang boleh melakukan direct transfer.');
        }

        $validated = $request->validate([
            'contract_id' => [
                'required',
                'exists:contracts,id',
            ],
            'target_am_id' => [
                'required',
                'exists:users,id',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $contract = Contract::findOrFail($validated['contract_id']);

        $oldAmId = $contract->owner_am_id;
        $newAmId = $validated['target_am_id'];

        if ((int) $oldAmId === (int) $newAmId) {
            return back()
                ->withInput()
                ->with('error', 'Target Account Manager tidak boleh sama dengan AM saat ini.');
        }

        DB::transaction(function () use ($contract, $oldAmId, $newAmId, $validated) {
            $contract->update([
                'owner_am_id' => $newAmId,
            ]);

            ContractTransferHistory::create([
                'contract_id' => $contract->id,
                'from_am_id' => $oldAmId,
                'to_am_id' => $newAmId,
                'transferred_by' => Auth::id(),
                'transfer_type' => 'direct',
                'notes' => $validated['notes'] ?? 'Direct transfer by Manager.',
                'transfer_date' => now(),
            ]);

            ActivityLogger::log(
                'Direct Transfer',
                'Manager melakukan direct transfer kontrak ' . $contract->contract_number
            );
        });

        return redirect('/direct-transfer')
            ->with('success', 'Direct transfer kontrak berhasil dilakukan.');
    }
}