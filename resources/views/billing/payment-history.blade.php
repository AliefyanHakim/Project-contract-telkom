@extends('layouts.app')

@section('title', 'Payment History | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $user = auth()->user();

    $isAccountManager = $user->isAccountManager();

    $toolbarClass = 'billing-toolbar';

    if ($isAccountManager) {
        $toolbarClass .= ' am-toolbar';
    }
@endphp

<div class="billing-page">

    <div class="billing-header">
        <a href="{{ url('/dashboard') }}" class="billing-back-btn">
            ‹
        </a>

        <div>
            <h1>Billing & Invoices</h1>
            <p>View completed payment records and paid invoices.</p>
        </div>
    </div>

    <div class="billing-tabs">
        <a href="{{ url('/billing') }}" class="billing-tab">
            Outstanding
        </a>

        <a href="{{ url('/billing/payment-history') }}" class="billing-tab active">
            Payment History
        </a>
    </div>

    <section class="billing-toolbar-card">
        <form method="GET" action="{{ url('/billing/payment-history') }}" class="{{ $toolbarClass }}">

            @unless($isAccountManager)
                <select name="account_manager" onchange="this.form.submit()">
                    <option value="">
                        All Account Managers
                    </option>

                    @foreach(($accountManagers ?? collect()) as $am)
                        <option value="{{ $am->id }}" @selected(request('account_manager') == $am->id)>
                            {{ $am->name }}
                        </option>
                    @endforeach
                </select>
            @endunless

            <select disabled>
                <option>
                    Paid
                </option>
            </select>

            <div class="billing-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client, invoice, or contract ID..."
                >

                <button type="submit">
                    ⌕
                </button>
            </div>

        </form>
    </section>

    <section class="billing-table-card">

        <div class="billing-table-wrapper">

            <table class="billing-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>ID Contract</th>
                        <th>No. Invoice</th>
                        <th>Period</th>
                        <th>Price</th>
                        <th>Payment Date</th>
                        <th>Billing State</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($rows as $row)

                        <tr class="billing-row active">

                            <td>
                                {{ $row->contract->contract_name ?? '-' }}
                            </td>

                            <td>
                                {{ $row->contract->contract_number ?? '-' }}
                            </td>

                            <td>
                                INV-{{ $row->id }}
                            </td>

                            <td>
                                {{ $row->billing_period }}
                            </td>

                            <td>
                                Rp {{ number_format($row->amount, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $row->payment_date ? \Carbon\Carbon::parse($row->payment_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <span class="billing-status active">
                                    Paid
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="billing-empty">
                                No payment history found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if(method_exists($rows, 'total') && $rows->total() > 0)
            <div style="margin-top: 18px;">
                {{ $rows->links() }}
            </div>
        @endif

    </section>

</div>

@endsection