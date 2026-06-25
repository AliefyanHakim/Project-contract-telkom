@extends('layouts.app')

@section('title', 'Billing & Invoices | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $user = auth()->user();

    $isAccountManager = $user->isAccountManager();
    $canUpdateBilling = $user->isSupportPaycall();

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
        <form method="GET" action="{{ url('/billing') }}" class="{{ $toolbarClass }}">

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

            <select name="status" onchange="this.form.submit()">
                <option value="">
                    All Statuses
                </option>

                <option value="pending" @selected(request('status') === 'pending')>
                    Pending
                </option>

                <option value="overdue" @selected(request('status') === 'overdue')>
                    Overdue
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
                    <th>AM</th>
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

                        @php
                            $status = $row->payment_status ?? 'pending';

                            $rowClass = $status === 'overdue'
                                ? 'expiring'
                                : 'followup';

                            $statusLabel = $status === 'overdue'
                                ? 'Overdue'
                                : 'Pending';
                        @endphp

                        <tr class="billing-row {{ $rowClass }}">

                            <td>
                                {{ $row->contract->contract_name ?? '-' }}
                            </td>

                            <td>
                                {{ $row->contract?->owner_am_id ? 'AM ' . $row->contract->owner_am_id : '-' }}
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
                                {{ $row->due_date ? \Carbon\Carbon::parse($row->due_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                @if($status === 'overdue')
                                    <span class="billing-status expiring">
                                        Overdue
                                    </span>
                                @else
                                    <span class="billing-status followup">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($canUpdateBilling)
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

                                            <option value="pending" @selected($status === 'pending')>
                                                Pending
                                            </option>

                                            <option value="overdue" @selected($status === 'overdue')>
                                                Overdue
                                            </option>

                                            <option value="paid">
                                                Paid
                                            </option>

                                        </select>

                                    </form>
                                @else
                                    <span class="billing-readonly-badge">
                                        View Only
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="billing-empty">
                                No billing data found.
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