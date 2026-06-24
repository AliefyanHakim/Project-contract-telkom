@extends('layouts.app')

@section('title', 'Transfer Approval | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $requestData = $transferRequest ?? null;
    $contract = $requestData ? $requestData->contract : null;

    $clientName = data_get($contract, 'contract_name', '-');
    $contractNumber = data_get($contract, 'contract_number', '-');
    $currentAm = data_get($requestData, 'currentAM.name', '-');
    $targetAm = data_get($requestData, 'targetAM.name', '-');
    $requester = data_get($requestData, 'requester.name', '-');
    $packageName = data_get($contract, 'services.0.service.service_name', 'Enterprise');
    $startDate = data_get($contract, 'start_date');
    $endDate = data_get($contract, 'end_date');
    $reason = data_get($requestData, 'reason', '-');
    $status = data_get($requestData, 'status', 'pending');
@endphp

<div class="transfer-page">

    <div class="transfer-header">
        <a href="{{ url('/transfer-request') }}" class="transfer-back-btn">
            ‹
        </a>

        <div>
            <h1>Transfer Approval Detail</h1>
            <p>Review contract transfer request before approving or rejecting it.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="transfer-alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="transfer-alert error">
            {{ session('error') }}
        </div>
    @endif

    @if(!$requestData)
        <section class="transfer-table-card">
            <div class="transfer-empty">
                No transfer request data available.
            </div>
        </section>
    @else

        <section class="transfer-form-card">
            <h2>Request Information</h2>
            <p>This request was submitted by {{ $requester }}.</p>

            <div class="approval-detail-grid">

                <div class="approval-detail-item">
                    <span>Client Name</span>
                    <strong>{{ $clientName }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>ID Contract</span>
                    <strong>{{ $contractNumber }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>Current AM</span>
                    <strong>{{ $currentAm }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>Target AM</span>
                    <strong>{{ $targetAm }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>Package</span>
                    <strong>{{ $packageName }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>Status</span>
                    <strong>{{ ucfirst($status) }}</strong>
                </div>

                <div class="approval-detail-item">
                    <span>Start Date</span>
                    <strong>
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }}
                    </strong>
                </div>

                <div class="approval-detail-item">
                    <span>End Date</span>
                    <strong>
                        {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
                    </strong>
                </div>

            </div>

            <div class="approval-reason-box">
                <span>Transfer Reason</span>
                <p>{{ $reason ?: '-' }}</p>
            </div>

            @if($status === 'pending')
                <div class="approval-action-group">

                    <form method="POST" action="{{ url('/transfer-requests/' . $requestData->id . '/reject') }}">
                        @csrf
                        <button type="submit" class="approval-reject-btn">
                            Reject
                        </button>
                    </form>

                    <form method="POST" action="{{ url('/transfer-requests/' . $requestData->id . '/approve') }}">
                        @csrf
                        <button type="submit" class="approval-accept-btn">
                            Accept
                        </button>
                    </form>

                </div>
            @else
                <div class="approval-done-box">
                    This transfer request has already been processed.
                </div>
            @endif

        </section>

    @endif

</div>

@endsection