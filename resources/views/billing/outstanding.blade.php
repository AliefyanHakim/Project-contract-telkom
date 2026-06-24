@extends('layouts.app')

@section('title', 'Billing & Invoices | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="billing-page">

    <div class="billing-header">
        <a href="{{ url('/dashboard') }}" class="billing-back-btn">
            ‹
        </a>

        <div>
            <h1>Billing & Invoices</h1>
            <p>Monitor outstanding invoices, due dates, and billing status.</p>
        </div>
    </div>

    <div class="billing-tabs">
        <a href="{{ url('/billing') }}" class="billing-tab active">
            Outstanding
        </a>

        <a href="{{ url('/billing/payment-history') }}" class="billing-tab">
            Payment History
        </a>
    </div>

    <section class="billing-toolbar-card">
        <form method="GET" action="{{ url('/billing') }}" class="billing-toolbar">

            <select name="account_manager">
                <option value="">All Account Managers</option>
                <option value="am1">Account Manager 1</option>
                <option value="am2">Account Manager 2</option>
            </select>

            <select name="status">
                <option value="">All Statuses</option>
                <option value="followup">Follow-up Pending</option>
                <option value="expiring">Expiring Soon</option>
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
                        <th>Due Date</th>
                        <th>Billing State</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($rows as $row)

                    <tr class="billing-row">

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
                            Rp {{ number_format($row->amount,0,',','.') }}
                        </td>

                        <td>
                            {{ optional($row->payment_date)->format('d/m/Y') }}
                        </td>

                        <td>

                            @if($row->payment_status=='pending')

                                <span class="billing-status followup">
                                    Follow-up pending
                                </span>

                            @elseif($row->payment_status=='paid')

                                <span class="billing-status paid">
                                    Paid
                                </span>

                            @else

                                <span class="billing-status expiring">
                                    Overdue
                                </span>

                            @endif

                        </td>

                        <td>
                            <form
                                action="{{ route('billing.update-status', $row->id) }}"
                                method="POST"
                                class="billing-status-form">

                                @csrf
                                @method('PATCH')

                                <select
                                    name="payment_status"
                                    class="billing-status-select"
                                    onchange="this.form.submit()">

                                    <option value="">
                                        Change Status
                                    </option>

                                    <option value="pending">
                                        Pending
                                    </option>

                                    <option value="paid">
                                        Paid
                                    </option>

                                </select>

                            </form>
                        </td>

                    </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="billing-empty">
                                No billing data found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection