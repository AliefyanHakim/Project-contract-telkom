@extends('layouts.app')

@section('title', 'Contract Alerts | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contract-list.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/contract-alerts.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="contract-page alerts-page-v2">

    <div class="contract-header">
        <a href="{{ url('/dashboard') }}" class="contract-back-btn">‹</a>

        <div>
            <h1>Contract Alerts</h1>

            @if(auth()->user()->isAccountManager())
                <p>Monitor contract alerts for your owned contracts.</p>
            @else
                <p>Monitor contract alerts across all Account Managers.</p>
            @endif
        </div>
    </div>

    <section class="contract-alert-summary nice-alert-summary">

        <a href="{{ url('/contract-list?status=active') }}" class="nice-alert-card active">
            <div class="nice-alert-icon">✓</div>

            <div class="nice-alert-content">
                <h3>Active</h3>
                <strong>{{ $summary['active'] ?? 0 }}</strong>
                <p>Contracts in healthy status</p>
            </div>
        </a>

        <a href="{{ url('/contract-alerts?status=followup') }}" class="nice-alert-card critical">
            <div class="nice-alert-icon">!</div>

            <div class="nice-alert-content">
                <h3>Critical</h3>
                <strong>{{ $summary['critical'] ?? 0 }}</strong>
                <p>Need immediate follow-up</p>
            </div>
        </a>

        <a href="{{ url('/contract-alerts?status=expiring') }}" class="nice-alert-card expiring">
            <div class="nice-alert-icon">⏱</div>

            <div class="nice-alert-content">
                <h3>Expiring Soon</h3>
                <strong>{{ $summary['expiring'] ?? 0 }}</strong>
                <p>Will expire within 30 days</p>
            </div>
        </a>

    </section>

    <section class="contract-toolbar-card">
        <form method="GET" action="{{ url('/contract-alerts') }}" class="contract-toolbar alert-toolbar">

            <select name="status" onchange="this.form.submit()">
                <option value="">
                    All Alert Statuses
                </option>

                <option value="expiring" @selected(request('status') === 'expiring')>
                    Expiring Soon
                </option>

                <option value="followup" @selected(request('status') === 'followup')>
                    Critical Follow-up
                </option>

                <option value="expired" @selected(request('status') === 'expired')>
                    Expired
                </option>
            </select>

            <div class="contract-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client, AM, or contract ID..."
                >

                <button type="submit">
                    ⌕
                </button>
            </div>

            <a href="{{ route('contract.alerts.report') . '?' . http_build_query(request()->query()) }}"
               class="contract-alert-report-btn">
                Download Report
            </a>

        </form>
    </section>

    <section class="contract-table-card">

        <div class="contract-table-wrapper">

            <table class="contract-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>AM</th>
                        <th>ID Contract</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Alert State</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($contracts as $contract)

                        @php
                            $status = $contract->status;

                            $rowClass = match($status) {
                                'expiring' => 'expiring',
                                'followup' => 'followup',
                                'expired' => 'expired',
                                default => 'followup',
                            };

                            $statusLabel = match($status) {
                                'expiring' => 'Expiring Soon',
                                'followup' => 'Critical Follow-up',
                                'expired' => 'Expired',
                                default => ucfirst($status),
                            };
                        @endphp

                        <tr
                            class="contract-row {{ $rowClass }}"
                            onclick="window.location='{{ route('contracts.show', $contract->id) }}'"
                            style="cursor:pointer;">

                            <td>
                                {{ $contract->contract_name }}
                            </td>

                            <td>
                                {{ $contract->owner?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $contract->contract_number }}
                            </td>

                            <td>
                                @forelse($contract->services as $contractService)
                                    {{ $contractService->service?->service_name }}

                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @empty
                                    -
                                @endforelse
                            </td>

                            <td>
                                {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <span class="contract-status {{ $rowClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="contract-empty">
                                No contract alerts found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

    @if($contracts->hasPages())
        <div class="contract-pagination">

            @if($contracts->onFirstPage())
                <span class="contract-page-btn disabled">
                    ‹ Previous
                </span>
            @else
                <a href="{{ $contracts->previousPageUrl() }}" class="contract-page-btn">
                    ‹ Previous
                </a>
            @endif

            <span class="contract-page-info">
                Showing {{ $contracts->firstItem() }} to {{ $contracts->lastItem() }} of {{ $contracts->total() }} results
            </span>

            @if($contracts->hasMorePages())
                <a href="{{ $contracts->nextPageUrl() }}" class="contract-page-btn">
                    Next ›
                </a>
            @else
                <span class="contract-page-btn disabled">
                    Next ›
                </span>
            @endif

        </div>
    @endif

</div>

@endsection