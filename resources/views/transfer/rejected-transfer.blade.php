@extends('layouts.app')

@section('title', 'Rejected Transfer | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $rows = $transferRequests ?? collect();

    $totalRows = method_exists($rows, 'total')
        ? $rows->total()
        : $rows->count();
@endphp

<div class="transfer-page">

    <div class="transfer-header">
        <a href="{{ url('/transfer-request') }}" class="transfer-back-btn">
            ‹
        </a>

        <div>
            <h1>Rejected Transfer</h1>
            <p>View transfer requests that have been rejected by Manager.</p>
        </div>
    </div>

    <section class="transfer-toolbar-card">
        <form method="GET" action="{{ url('/rejected-transfer') }}" class="transfer-toolbar no-add">

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
                        <th>Rejected Date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($rows as $row)

                        @php
                            $contract = data_get($row, 'contract');

                            $clientName = data_get($contract, 'contract_name', '-');
                            $contractNumber = data_get($contract, 'contract_number', '-');
                            $fromAm = data_get($row, 'currentAM.name', '-');
                            $toAm = data_get($row, 'targetAM.name', '-');
                            $packageName = data_get($contract, 'services.0.service.service_name', 'Enterprise');
                            $approvedAt = data_get($row, 'approved_at');
                        @endphp

                        <tr class="transfer-row rejected">
                            <td>
                                <div class="transfer-client">
                                    <span class="transfer-client-icon rejected">×</span>
                                    <span>{{ $clientName }}</span>
                                </div>
                            </td>

                            <td>{{ $contractNumber }}</td>
                            <td>{{ $fromAm }}</td>
                            <td>{{ $toAm }}</td>
                            <td>{{ $packageName }}</td>

                            <td>
                                {{ $approvedAt ? \Carbon\Carbon::parse($approvedAt)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <span class="transfer-status rejected">
                                    × Rejected
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="transfer-empty">
                                No rejected transfer data available.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if(method_exists($rows, 'total') && $rows->total() > 0)
            <div class="transfer-table-footer">
                <p>
                    Showing {{ $rows->firstItem() }}
                    to {{ $rows->lastItem() }}
                    of {{ $rows->total() }} entries
                </p>

                @if($rows->hasPages())
                    <div class="transfer-pagination">
                        @if($rows->onFirstPage())
                            <span class="disabled">‹</span>
                        @else
                            <a href="{{ $rows->previousPageUrl() }}">‹</a>
                        @endif

                        @foreach($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
                            @if($page == $rows->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($rows->hasMorePages())
                            <a href="{{ $rows->nextPageUrl() }}">›</a>
                        @else
                            <span class="disabled">›</span>
                        @endif
                    </div>
                @endif
            </div>
        @endif

    </section>

</div>

@endsection