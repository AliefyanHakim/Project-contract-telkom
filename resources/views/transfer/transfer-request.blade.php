@extends('layouts.app')

@section('title', 'Transfer Request | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $transferRows = $transferRequests ?? collect();

    $totalRows = method_exists($transferRows, 'total')
        ? $transferRows->total()
        : $transferRows->count();

    $canAddTransferContract = auth()->user()->isManager()
        || auth()->user()->isAccountManager();
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
    <a href="{{ url('/transfer-request') }}" class="transfer-tab active">
        Approval Requests
    </a>

    <a href="{{ url('/direct-transfer') }}" class="transfer-tab">
        Direct Transfer
    </a>
</div>

    @php
    $canAddTransferContract = auth()->user()->isManager() || auth()->user()->isAccountManager();
@endphp

<section class="transfer-toolbar-card">
    <form method="GET"
          action="{{ url('/transfer-request') }}"
          class="transfer-toolbar {{ $canAddTransferContract ? '' : 'no-add' }}">

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

        @if($canAddTransferContract)
            <a href="{{ url('/transfer-contract') }}" class="transfer-add-btn">
                <span>＋</span>
                Transfer Contract
            </a>
        @endif

    </form>
</section>

    <section class="transfer-table-card">

        <div class="transfer-table-wrapper">

            <table class="transfer-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>ID Contract</th>
                        <th>AM</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($transferRows as $row)

                        @php                            
                            $rawStatus = strtolower(
                                str_replace(' ', '_', data_get($row, 'status', 'pending'))
                            );

                            if (in_array($rawStatus, ['approved', 'approve'])) {
                                $rowClass = 'approved';
                                $statusLabel = 'Approved';
                            } elseif (in_array($rawStatus, ['rejected', 'reject'])) {
                                $rowClass = 'rejected';
                                $statusLabel = 'Rejected';
                            } else {
                                $rowClass = 'pending';
                                $statusLabel = 'Approval Pending';
                            }

                            $contract = data_get($row, 'contract');

                            $clientName = data_get($contract, 'contract_name', '-');

                            $contractNumber = data_get($contract, 'contract_number', '-');

                            $currentAmName = data_get($row, 'currentAM.name', '-');

                            $targetAmName = data_get($row, 'targetAM.name', '-');

                            $amName = $currentAmName . ' → ' . $targetAmName;

                            $packageName = data_get($contract, 'services.0.service.service_name', 'Enterprise');

                            $startDate = data_get($contract, 'start_date');

                            $endDate = data_get($contract, 'end_date');
                        @endphp

                        <tr class="transfer-row {{ $rowClass }}">
                            <td>
                                <div class="transfer-client">
                                    <span class="transfer-client-icon {{ $rowClass }}">▥</span>
                                    <span>{{ $clientName }}</span>
                                </div>
                            </td>

                            <td>{{ $contractNumber }}</td>

                            <td>{{ $amName }}</td>

                            <td>{{ $packageName }}</td>

                            <td>
                                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <span class="transfer-status {{ $rowClass }}">
                                    @if ($rowClass === 'approved')
                                        ✓
                                    @elseif ($rowClass === 'rejected')
                                        ×
                                    @else
                                        ⏱
                                    @endif

                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="transfer-empty">
                                No transfer request data available.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if(method_exists($transferRows, 'total') && $transferRows->total() > 0)
    <div class="transfer-table-footer">
        <p>
            Showing {{ $transferRows->firstItem() }}
            to {{ $transferRows->lastItem() }}
            of {{ $transferRows->total() }} entries
        </p>

        @if($transferRows->hasPages())
            <div class="transfer-pagination">
                @if($transferRows->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $transferRows->previousPageUrl() }}">‹</a>
                @endif

                @foreach($transferRows->getUrlRange(1, $transferRows->lastPage()) as $page => $url)
                    @if($page == $transferRows->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($transferRows->hasMorePages())
                    <a href="{{ $transferRows->nextPageUrl() }}">›</a>
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