@extends('layouts.app')

@section('title', 'Dashboard | VasTrack')

@section('content')

@php
    $cards = $cards ?? [];
    $contracts = $contracts ?? [];
    $summaries = $summaries ?? [];
    $billingRows = $billingRows ?? collect();

    $user = auth()->user();

    $showBillingPanel = $user->isSupportInputter()
        || $user->isSupportPaycall();

    $canOpenContractAlerts = $user->isAccountManager()
        || $user->isSupportInputter();
@endphp

@if($expiring30Days > 0)
<section class="vt-alert-box">
    <div class="vt-alert-icon">!</div>

    <p>
        <strong>{{ $expiring30Days }} contracts</strong>
        will expire within the next 30 days —
        follow up or renew immediately.
    </p>

    @if($canOpenContractAlerts)
        <a href="{{ url('/contract-alerts') }}">
            View Alerts
        </a>
    @else
        <a href="{{ url('/contract-list') }}">
            View Contracts
        </a>
    @endif
</section>
@endif

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

            @if($canOpenContractAlerts)
                <a href="{{ url('/contract-alerts') }}">
                    View Alerts
                </a>
            @else
                <a href="{{ url('/contract-list') }}">
                    View Contracts
                </a>
            @endif
        </div>

        <div class="vt-contract-list">
            @forelse ($contracts as $contract)
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

                        <a href="{{ route('contracts.show', $contract['id']) }}">
                            Detail ›
                        </a>
                    </div>
                </div>
            @empty
                <div class="vt-empty-state">
                    No contracts near expiration.
                </div>
            @endforelse
        </div>
    </div>

    @if($showBillingPanel)

        <div class="vt-panel">
            <div class="vt-panel-header">
                <div>
                    <h3>Billing Overview</h3>
                    <p>Outstanding invoices and payment follow-up</p>
                </div>

                <a href="{{ url('/billing') }}">
                    View Billing
                </a>
            </div>

            <div class="vt-billing-summary-grid">
                <div class="vt-billing-mini-card">
                    <span>Outstanding</span>
                    <strong>{{ $billingSummary['outstanding_count'] ?? 0 }}</strong>
                    <p>{{ $billingSummary['outstanding_amount'] ?? 'Rp 0' }}</p>
                </div>

                <div class="vt-billing-mini-card">
                    <span>Pending</span>
                    <strong>{{ $billingSummary['pending_count'] ?? 0 }}</strong>
                    <p>Waiting for payment</p>
                </div>

                <div class="vt-billing-mini-card danger">
                    <span>Overdue</span>
                    <strong>{{ $billingSummary['overdue_count'] ?? 0 }}</strong>
                    <p>Need follow-up</p>
                </div>
            </div>

            <div class="vt-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>AM</th>
                            <th>Period</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($billingRows as $row)
                            <tr>
                                <td>{{ $row['client'] }}</td>
                                <td>{{ $row['am'] }}</td>
                                <td>{{ $row['period'] }}</td>
                                <td>{{ $row['amount'] }}</td>
                                <td>
                                    <span class="vt-billing-status {{ $row['status'] }}">
                                        {{ ucfirst($row['status']) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    No outstanding billing found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ url('/billing') }}" class="vt-summary-link">
                View Billing Detail
            </a>
        </div>

    @else

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
                        @forelse ($summaries as $summary)
                            <tr>
                                <td>{{ $summary['name'] }}</td>
                                <td>{{ $summary['clients'] }}</td>
                                <td>{{ $summary['active'] }}</td>
                                <td>{{ $summary['expiring'] }}</td>
                                <td>{{ $summary['value'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    No Account Manager summary found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($user->isAccountManager())
                <a href="{{ url('/contract-list') }}" class="vt-summary-link">
                    View My Contracts
                </a>
            @elseif($user->isManager())
                <a href="{{ url('/detailam') }}" class="vt-summary-link">
                    View Full Summary
                </a>
            @else
                <a href="{{ url('/contract-list') }}" class="vt-summary-link">
                    View Contracts
                </a>
            @endif
        </div>

    @endif

</section>

@endsection