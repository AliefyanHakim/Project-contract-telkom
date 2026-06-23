@extends('layouts.app')

@section('title', 'Direct Transfer | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $directRows = collect([
        (object) [
            'client_name' => 'PT Maju Bersama',
            'contract_number' => '1234567890',
            'from_am' => 'Account Manager 1',
            'to_am' => 'Account Manager 2',
            'package' => 'Enterprise',
            'transfer_date' => '2026-05-29',
            'status' => 'transferred',
        ],
        (object) [
            'client_name' => 'PT Maju Bersama',
            'contract_number' => '1234567890',
            'from_am' => 'Account Manager 1',
            'to_am' => 'Account Manager 2',
            'package' => 'Enterprise',
            'transfer_date' => '2026-05-29',
            'status' => 'transferred',
        ],
        (object) [
            'client_name' => 'PT Maju Bersama',
            'contract_number' => '1234567890',
            'from_am' => 'Account Manager 1',
            'to_am' => 'Account Manager 2',
            'package' => 'Enterprise',
            'transfer_date' => '2026-05-29',
            'status' => 'transferred',
        ],
    ]);

    $totalRows = $directRows->count();
@endphp

<div class="transfer-page">

    <div class="transfer-header">
        <a href="{{ url('/dashboard') }}" class="transfer-back-btn">
            ‹
        </a>

        <div>
            <h1>Contract Request History</h1>
            <p>Review approval requests and direct transfer history in one place.</p>
        </div>
    </div>

    <div class="transfer-tabs">
    <a href="{{ url('/transfer-request') }}" class="transfer-tab">
        Approval Requests
    </a>

    <a href="{{ url('/direct-transfer') }}" class="transfer-tab active">
        Direct Transfer
    </a>
</div>

    <section class="transfer-toolbar-card">
    <form method="GET" action="{{ url('/direct-transfer') }}" class="transfer-toolbar no-add">

        <a href="#" class="transfer-download-btn">
            <span>↓</span>
            Download Report
        </a>

        <div class="transfer-search-box">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search client, contract ID, or AM..."
            >

            <button type="submit">
                ⌕
            </button>
        </div>

    </form>
</section>

    <section class="transfer-table-card">

        <div class="transfer-table-wrapper">

            <table class="transfer-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>ID Contract</th>
                        <th>From AM</th>
                        <th>To AM</th>
                        <th>Package</th>
                        <th>Transfer Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($directRows as $row)

                        <tr class="transfer-row approved">
                            <td>
                                <div class="transfer-client">
                                    <span class="transfer-client-icon approved">⇄</span>
                                    <span>{{ $row->client_name }}</span>
                                </div>
                            </td>

                            <td>{{ $row->contract_number }}</td>

                            <td>{{ $row->from_am }}</td>

                            <td>{{ $row->to_am }}</td>

                            <td>{{ $row->package }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($row->transfer_date)->format('d/m/Y') }}
                            </td>

                            <td>
                                <span class="transfer-status approved">
                                    ✓ Transferred
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="transfer-empty">
                                No direct transfer data available.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="transfer-table-footer">
            <p>
                Showing 1 to {{ $totalRows }} of {{ $totalRows }} entries
            </p>

            <div class="transfer-simple-pagination">
                <button type="button">‹</button>
                <button type="button" class="active">1</button>
                <button type="button">›</button>
            </div>
        </div>

    </section>

</div>

@endsection