@extends('layouts.app')

@section('title', 'Direct Transfer | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $directRows = $directTransfers ?? collect();

    $totalRows = method_exists($directRows, 'total')
        ? $directRows->total()
        : $directRows->count();
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

                    @php
                        $contract = data_get($row, 'contract');

                        $clientName = data_get($contract, 'contract_name', '-');
                        $contractNumber = data_get($contract, 'contract_number', '-');
                        $fromAm = data_get($row, 'fromAM.name', '-');
                        $toAm = data_get($row, 'toAM.name', '-');
                        $packageName = data_get($contract, 'services.0.service.service_name', 'Enterprise');
                        $transferDate = data_get($row, 'transfer_date');
                    @endphp

                    <tr class="transfer-row approved">
                        <td>
                            <div class="transfer-client">
                                <span class="transfer-client-icon approved">⇄</span>
                                <span>{{ $clientName }}</span>
                            </div>
                        </td>

                        <td>{{ $contractNumber }}</td>

                        <td>{{ $fromAm }}</td>

                        <td>{{ $toAm }}</td>

                        <td>{{ $packageName }}</td>

                        <td>
                            {{ $transferDate ? \Carbon\Carbon::parse($transferDate)->format('d/m/Y') : '-' }}
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

        @if(method_exists($directRows, 'total') && $directRows->total() > 0)
    <div class="transfer-table-footer">
        <p>
            Showing {{ $directRows->firstItem() }}
            to {{ $directRows->lastItem() }}
            of {{ $directRows->total() }} entries
        </p>

        @if($directRows->hasPages())
            <div class="transfer-pagination">
                @if($directRows->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $directRows->previousPageUrl() }}">‹</a>
                @endif

                @foreach($directRows->getUrlRange(1, $directRows->lastPage()) as $page => $url)
                    @if($page == $directRows->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($directRows->hasMorePages())
                    <a href="{{ $directRows->nextPageUrl() }}">›</a>
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