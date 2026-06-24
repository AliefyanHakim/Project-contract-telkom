<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function outstanding(Request $request)
    {
        $user = Auth::user();

        $query = Billing::with([
            'contract.owner',
        ])
        ->whereIn('payment_status', [
            'pending',
            'overdue',
        ]);

        if ($user->isAccountManager()) {
            $query->whereHas('contract', function ($q) use ($user) {
                $q->where('owner_am_id', $user->id);
            });
        }

        if ($request->filled('account_manager') && !$user->isAccountManager()) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('owner_am_id', $request->account_manager);
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('billing_period', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('contract', function ($contractQuery) use ($search) {
                        $contractQuery->where('contract_name', 'like', "%{$search}%")
                            ->orWhere('contract_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('contract.owner', function ($ownerQuery) use ($search) {
                        $ownerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $rows = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $accountManagers = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('billing.outstanding', compact(
            'rows',
            'accountManagers'
        ));
    }

    public function paymentHistory(Request $request)
    {
        $user = Auth::user();

        $query = Billing::with([
            'contract.owner',
        ])
        ->where('payment_status', 'paid');

        if ($user->isAccountManager()) {
            $query->whereHas('contract', function ($q) use ($user) {
                $q->where('owner_am_id', $user->id);
            });
        }

        if ($request->filled('account_manager') && !$user->isAccountManager()) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('owner_am_id', $request->account_manager);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('billing_period', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('contract', function ($contractQuery) use ($search) {
                        $contractQuery->where('contract_name', 'like', "%{$search}%")
                            ->orWhere('contract_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('contract.owner', function ($ownerQuery) use ($search) {
                        $ownerQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $rows = $query
            ->latest('payment_date')
            ->paginate(10)
            ->withQueryString();

        $accountManagers = User::where('role_id', User::ROLE_ACCOUNT_MANAGER)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('billing.payment-history', compact(
            'rows',
            'accountManagers'
        ));
    }

    public function updateStatus(Request $request, Billing $billing)
    {
        if (!Auth::user()->isSupportPaycall()) {
            abort(403, 'Hanya Support Paycall yang boleh update status pembayaran.');
        }

        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:pending,paid,overdue',
            ],
        ]);

        $data = [
            'payment_status' => $validated['payment_status'],
            'updated_by' => Auth::id(),
        ];

        if ($validated['payment_status'] === 'paid') {
            $data['payment_date'] = now();
        } else {
            $data['payment_date'] = null;
        }

        $billing->update($data);

        ActivityLogger::log(
            'BILLING',
            'Updated billing status INV-' . str_pad($billing->id, 4, '0', STR_PAD_LEFT)
        );

        return back()->with(
            'success',
            'Billing status updated successfully.'
        );
    }
}