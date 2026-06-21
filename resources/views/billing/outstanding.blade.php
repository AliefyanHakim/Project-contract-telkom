@extends('layouts.app')

@section('title', 'Billing & Invoices | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $rows = collect([
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-3026',
            'period' => 'Mei 2026',
            'price' => 15000000,
            'due_date' => '2026-05-29',
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-8031',
            'period' => 'Mei 2026',
            'price' => 4000000,
            'due_date' => '2026-05-29',
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-2484',
            'period' => 'Mei 2026',
            'price' => 16000000,
            'due_date' => '2026-05-29',
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-1640',
            'period' => 'Mei 2026',
            'price' => 16000000,
            'due_date' => '2026-05-29',
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-6371',
            'period' => 'Mei 2026',
            'price' => 12000000,
            'due_date' => '2026-05-29',
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-7425',
            'period' => 'Mei 2026',
            'price' => 15000000,
            'due_date' => '2026-05-30',
            'status' => 'expiring',
            'label' => 'Expiring soon',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-5903',
            'period' => 'Mei 2026',
            'price' => 2000000,
            'due_date' => '2026-05-30',
            'status' => 'expiring',
            'label' => 'Expiring soon',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-7353',
            'period' => 'Mei 2026',
            'price' => 4000000,
            'due_date' => '2026-05-31',
            'status' => 'expiring',
            'label' => 'Expiring soon',
        ],
    ]);
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
                    </tr>
                </thead>

                <tbody>

                    @forelse ($rows as $row)

                        <tr class="billing-row {{ $row['status'] }}">
                            <td>{{ $row['client'] }}</td>
                            <td>{{ $row['contract'] }}</td>
                            <td>{{ $row['invoice'] }}</td>
                            <td>{{ $row['period'] }}</td>
                            <td>Rp {{ number_format($row['price'], 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($row['due_date'])->format('d/m/Y') }}</td>
                            <td>
                                <span class="billing-status {{ $row['status'] }}">
                                    {{ $row['label'] }}
                                </span>
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