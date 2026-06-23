@extends('layouts.app')

@section('title', 'Dashboard | VasTrack')

@section('content')

@php
    $cards = $cards ?? [];
    $contracts = $contracts ?? [];
    $summaries = $summaries ?? [];
@endphp

@if($expiring30Days > 0)
<section class="vt-alert-box">
    <div class="vt-alert-icon">!</div>

    <p>
        <strong>{{ $expiring30Days }} contracts</strong>
        will expire within the next 30 days —
        follow up or renew immediately.
    </p>

    <a href="#">View All Alerts</a>
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

@endsection