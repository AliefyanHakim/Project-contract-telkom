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

            $query->whereHas('contract', function ($q) use ($search) {
                $q->where('contract_name', 'like', "%{$search}%")
                    ->orWhere('contract_number', 'like', "%{$search}%");
            })
            ->orWhereHas('currentAM', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('targetAM', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
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
        $query = ContractTransferHistory::with([
            'contract.services.service',
            'fromAM',
            'toAM',
            'transferredBy',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('contract', function ($q) use ($search) {
                $q->where('contract_name', 'like', "%{$search}%")
                    ->orWhere('contract_number', 'like', "%{$search}%");
            })
            ->orWhereHas('fromAM', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('toAM', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $directTransfers = $query
            ->latest('transfer_date')
            ->paginate(10)
            ->withQueryString();

        return view('transfer.direct-transfer', compact('directTransfers'));
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
            'Membuat request transfer kontrak ' . $contract->contract_number
        );

        return redirect('/transfer-request')
            ->with('success', 'Request transfer kontrak berhasil dibuat.');
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
}

        