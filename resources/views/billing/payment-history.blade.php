@extends('layouts.app')

@section('title', 'Payment History | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $rows = collect([
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-0722',
            'period' => 'Mei 2026',
            'price' => 16000000,
            'payment_date' => '2026-05-25',
            'status' => 'paid',
            'label' => 'Paid',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-0848',
            'period' => 'Mei 2026',
            'price' => 4000000,
            'payment_date' => '2026-05-24',
            'status' => 'paid',
            'label' => 'Paid',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-1540',
            'period' => 'Mei 2026',
            'price' => 16000000,
            'payment_date' => '2026-05-21',
            'status' => 'paid',
            'label' => 'Paid',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-2506',
            'period' => 'Mei 2026',
            'price' => 16000000,
            'payment_date' => '2026-05-16',
            'status' => 'paid',
            'label' => 'Paid',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-3324',
            'period' => 'Mei 2026',
            'price' => 14000000,
            'payment_date' => '2026-05-15',
            'status' => 'paid',
            'label' => 'Paid',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'invoice' => 'INV-2026-3815',
            'period' => 'Mei 2026',
            'price' => 14000000,
            'payment_date' => '2026-05-15',
            'status' => 'paid',
            'label' => 'Paid',
        ],
    ]);
@endphp

<div class="billing-page">

    <div class="billing-header">
        <a href="{{ url('/billing') }}" class="billing-back-btn">
            ‹
        </a>

        <div>
            <h1>Billing & Invoices</h1>
            <p>Review completed invoice payments and billing history.</p>
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
        <form method="GET" action="{{ url('/billing/payment-history') }}" class="billing-toolbar">

            <select name="account_manager">
                <option value="">All Account Managers</option>
                <option value="am1">Account Manager 1</option>
                <option value="am2">Account Manager 2</option>
            </select>

            <select name="status">
                <option value="">All Statuses</option>
                <option value="paid">Paid</option>
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
                        <th>Status</th>
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
                            <td>{{ \Carbon\Carbon::parse($row['payment_date'])->format('d/m/Y') }}</td>
                            <td>
                                <span class="billing-status {{ $row['status'] }}">
                                    {{ $row['label'] }}
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

    </section>

</div>

@endsection