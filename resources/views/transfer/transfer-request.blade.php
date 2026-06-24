@extends('layouts.app')

@section('title', 'Transfer Request | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $transferRows = isset($transferRequests)
        ? $transferRequests
        : (isset($transfers)
            ? $transfers
            : collect([
                (object) [
                    'client_name' => 'PT Maju Bersama',
                    'contract_name' => 'PT Maju Bersama',
                    'contract_number' => '1234567890',
                    'am_name' => 'XXXXXXX',
                    'package' => 'Enterprise',
                    'start_date' => '2026-04-11',
                    'end_date' => '2026-05-29',
                    'status' => 'approval_pending',
                ],
                (object) [
                    'client_name' => 'PT Maju Bersama',
                    'contract_name' => 'PT Maju Bersama',
                    'contract_number' => '1234567890',
                    'am_name' => 'XXXXXXX',
                    'package' => 'Enterprise',
                    'start_date' => '2026-04-11',
                    'end_date' => '2026-05-29',
                    'status' => 'approved',
                ],
                (object) [
                    'client_name' => 'PT Maju Bersama',
                    'contract_name' => 'PT Maju Bersama',
                    'contract_number' => '1234567890',
                    'am_name' => 'XXXXXXX',
                    'package' => 'Enterprise',
                    'start_date' => '2026-04-11',
                    'end_date' => '2026-05-29',
                    'status' => 'rejected',
                ],
            ]));

    $totalRows = method_exists($transferRows, 'total')
        ? $transferRows->total()
        : $transferRows->count();
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
                                str_replace(' ', '_', data_get($row, 'status', 'approval_pending'))
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

                            $clientName = data_get($row, 'client_name')
                                ?? data_get($row, 'contract_name')
                                ?? 'PT Maju Bersama';

                            $contractNumber = data_get($row, 'contract_number', '1234567890');

                            $amName = data_get($row, 'am_name')
                                ?? data_get($row, 'owner.name')
                                ?? 'XXXXXXX';

                            $packageName = data_get($row, 'package')
                                ?? data_get($row, 'service_name')
                                ?? 'Enterprise';

                            $startDate = data_get($row, 'start_date');
                            $endDate = data_get($row, 'end_date');
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

        <div class="transfer-table-footer">
            <p>
                Showing 1 to {{ $totalRows }} of {{ $totalRows }} entries
            </p>

            @if (method_exists($transferRows, 'links'))
                <div class="transfer-pagination">
                    {{ $transferRows->links() }}
                </div>
            @else
                <div class="transfer-simple-pagination">
                    <button type="button">‹</button>
                    <button type="button" class="active">1</button>
                    <button type="button">›</button>
                </div>
            @endif
        </div>

    </section>

</div>

@endsection