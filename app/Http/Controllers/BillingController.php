<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function outstanding()
    {
        $rows = Billing::with('contract')
                    ->whereIn('payment_status', ['pending', 'overdue'])
                    ->get();

        return view('billing.outstanding', compact('rows'));
    }

    public function paymentHistory()
    {
        $rows = Billing::with('contract')
                    ->where('payment_status', 'paid')
                    ->get();

        return view('billing.payment-history', compact('rows'));
    }

    public function updateStatus(Request $request, Billing $billing)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,overdue'
        ]);

        $data = [
            'payment_status' => $request->payment_status
        ];

        if ($request->payment_status === 'paid') {
            $data['payment_date'] = now();
        }

        $billing->update($data);

        return back()->with(
            'success',
            'Billing status updated successfully.'
        );
    }
}