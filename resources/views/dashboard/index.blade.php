@extends('layouts.app')

@section('title', 'Dashboard | VasTrack')

@section('content')

@php
    $cards = [
        [
            'title' => 'Total Active Contracts',
            'value' => '123',
            'unit' => 'Contracts',
            'desc' => 'across 5 Account Managers',
            'type' => 'blue',
            'icon' => 'DOC'
        ],
        [
            'title' => 'Expiring < 7 Days',
            'value' => '2',
            'unit' => 'Contracts',
            'desc' => 'Immediate action required',
            'type' => 'red',
            'icon' => 'EXP'
        ],
        [
            'title' => 'Expiring < 30 Days',
            'value' => '45',
            'unit' => 'Contracts',
            'desc' => 'Follow up soon',
            'type' => 'yellow',
            'icon' => 'REM'
        ],
        [
            'title' => 'Outstanding Invoices',
            'value' => '145.000.000',
            'unit' => 'Rupiah',
            'desc' => '6 pending invoices',
            'type' => 'green',
            'icon' => 'INV'
        ],
    ];

    $contracts = [
        ['company' => 'PT Xxxxx Xxxxxxxxx', 'package' => 'Paket Enterprise', 'am' => 'Budi Santoso', 'end_date' => '29/05/2026', 'price' => 'Rp 5.000.000/month', 'days_left' => '0 days left', 'status' => 'danger'],
        ['company' => 'PT Xxxxx Xxxxxxxxx', 'package' => 'Paket Enterprise', 'am' => 'Rina Dewi', 'end_date' => '29/05/2026', 'price' => 'Rp 4.500.000/month', 'days_left' => '3 days left', 'status' => 'danger'],
        ['company' => 'PT Xxxxx Xxxxxxxxx', 'package' => 'Paket Enterprise', 'am' => 'Budi Santoso', 'end_date' => '31/05/2026', 'price' => 'Rp 12.000.000/month', 'days_left' => '12 days left', 'status' => 'warning'],
        ['company' => 'PT Xxxxx Xxxxxxxxx', 'package' => 'Paket Enterprise', 'am' => 'Budi Santoso', 'end_date' => '01/06/2026', 'price' => 'Rp 10.000.000/month', 'days_left' => '23 days left', 'status' => 'warning'],
    ];

    $summaries = [
        ['name' => 'Account Manager 1', 'clients' => 16, 'active' => 14, 'expiring' => 3, 'value' => 'Rp 24.000.000', 'billing' => '4 invoices'],
        ['name' => 'Account Manager 2', 'clients' => 12, 'active' => 12, 'expiring' => 2, 'value' => 'Rp 23.000.000', 'billing' => '2 invoices'],
        ['name' => 'Account Manager 3', 'clients' => 14, 'active' => 11, 'expiring' => 1, 'value' => 'Rp 25.000.000', 'billing' => '3 invoices'],
    ];
@endphp

<section class="vt-alert-box">
    <div class="vt-alert-icon">!</div>

    <p>
        <strong>4 contracts</strong> will expire within the next 30 days —
        follow up or renew immediately.
    </p>

    <a href="#">View All Alerts</a>
</section>

<section class="vt-kpi-grid">
    @foreach ($cards as $card)
        <div class="vt-kpi-card {{ $card['type'] }}">
            <div class="vt-kpi-top">
                <div class="vt-kpi-icon">{{ $card['icon'] }}</div>
                <h3>{{ $card['title'] }}</h3>
            </div>

            <h2>{{ $card['value'] }}</h2>
            <h4>{{ $card['unit'] }}</h4>
            <p>{{ $card['desc'] }}</p>
        </div>
    @endforeach
</section>

<section class="vt-content-grid">
    <div class="vt-panel">
        <div class="vt-panel-header">
            <div>
                <h3>Contracts Near Expiration</h3>
                <p>Attention needed for renewal and follow up</p>
            </div>

            <a href="#">View All</a>
        </div>

        <div class="vt-contract-list">
            @foreach ($contracts as $contract)
                <div class="vt-contract-item">
                    <div class="vt-contract-logo">PT</div>

                    <div class="vt-contract-info">
                        <h4>{{ $contract['company'] }} — {{ $contract['package'] }}</h4>
                        <p>
                            AM: {{ $contract['am'] }}
                            <span>•</span>
                            End date: {{ $contract['end_date'] }}
                            <span>•</span>
                            {{ $contract['price'] }}
                        </p>
                    </div>

                    <div class="vt-contract-action">
                        <span class="vt-badge {{ $contract['status'] }}">
                            {{ $contract['days_left'] }}
                        </span>

                        <a href="#">Detail ›</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="vt-panel">
        <div class="vt-panel-header">
            <div>
                <h3>Summary by Account Manager</h3>
                <p>Contract performance overview</p>
            </div>
        </div>

        <div class="vt-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Account Manager</th>
                        <th>Clients</th>
                        <th>Active</th>
                        <th>Expiring</th>
                        <th>Monthly Value</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($summaries as $summary)
                        <tr>
                            <td>{{ $summary['name'] }}</td>
                            <td>{{ $summary['clients'] }}</td>
                            <td>{{ $summary['active'] }}</td>
                            <td>{{ $summary['expiring'] }}</td>
                            <td>{{ $summary['value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="#" class="vt-summary-link">View Full Summary</a>
    </div>
</section>

<section class="vt-quick-actions">
    <div class="vt-panel-header">
        <div>
            <h3>Quick Actions</h3>
            <p>Shortcut for contract management activities</p>
        </div>
    </div>

    <div class="vt-action-grid">
        <a href="#" class="vt-action-card">
            <span>+</span>
            Add New Contract
        </a>

        <a href="#" class="vt-action-card">
            <span>↑</span>
            Upload Contract
        </a>

        <a href="#" class="vt-action-card">
            <span>⇄</span>
            Request Transfer
        </a>

        <a href="#" class="vt-action-card">
            <span>R</span>
            Generate Report
        </a>

        <a href="#" class="vt-action-card danger">
            <span>!</span>
            Set Reminder
        </a>
    </div>
</section>

@endsection