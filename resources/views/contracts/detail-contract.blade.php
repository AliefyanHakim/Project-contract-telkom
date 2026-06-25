@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')

@php
    $status = $contract->status ?? '-';
    $statusLabel = $status !== '-' ? ucfirst($status) : '-';
@endphp

<div class="contract-page">
    <div class="contract-container">
        <div class="detail-header">
            <div>
                <div class="contract-title">Contract Detail</div>
                <div class="detail-subtitle">
                    Read-only overview of contract identity, customer data, service, period, and supporting files.
                </div>
            </div>

            <span class="detail-status-badge {{ $status }}">
                {{ $statusLabel }}
            </span>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-section">
            <div class="form-section-title">Contract Identity</div>
            <div class="form-section-desc">Basic contract identifier and customer account data.</div>

            <div class="form-row three">
                <div class="form-group half">
                    <label>Account Number</label>
                    <input type="text" value="{{ $contract->account_number ?? '-' }}" readonly>
                </div>

                <div class="form-group half">
                    <label>S-ID</label>
                    <input type="text" value="{{ $contract->sid ?? '-' }}" readonly>
                </div>

                <div class="form-group half">
                    <label>Contract Number</label>
                    <input type="text" value="{{ $contract->contract_number ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Telkom Representative</div>
            <div class="form-section-desc">Authorized representative from PT Telekomunikasi Indonesia Tbk.</div>

            <div class="form-group">
                <label>Name</label>
                <input type="text" value="{{ $contract->telkom_name ?? '-' }}" readonly>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Position</label>
                    <input type="text" value="{{ $contract->telkom_position ?? '-' }}" readonly>
                </div>

                <div class="form-group half">
                    <label>Unit</label>
                    <input type="text" value="{{ $contract->telkom_unit ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Customer Information</div>
            <div class="form-section-desc">Customer identity, address, and authorized representative.</div>

            <div class="form-group">
                <label>Company Name</label>
                <input type="text" value="{{ $contract->contract_name ?? '-' }}" readonly>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea rows="3" readonly>{{ $contract->customer_address ?? '-' }}</textarea>
            </div>

            <div class="form-group">
                <label>NPWP</label>
                <input type="text" value="{{ $contract->customer_npwp ?? '-' }}" readonly>
            </div>

            <div class="detail-mini-title">Customer Representative</div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Name</label>
                    <input type="text" value="{{ $contract->customer_pic_name ?? '-' }}" readonly>
                </div>

                <div class="form-group half">
                    <label>Position</label>
                    <input type="text" value="{{ $contract->customer_pic_position ?? '-' }}" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Phone Number</label>
                    <input type="text" value="{{ $contract->customer_phone ?? '-' }}" readonly>
                </div>

                <div class="form-group half">
                    <label>Email</label>
                    <input type="text" value="{{ $contract->customer_email ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Services</div>
            <div class="form-section-desc">Selected service packages for this contract.</div>

            <div class="detail-service-list">
                @forelse($contract->services as $contractService)
                    <div class="detail-service-card">
                        <div class="detail-service-main">
                            <strong>{{ $contractService->service?->service_name ?? '-' }}</strong>
                            <span>Service package attached to this contract</span>
                        </div>

                        <div class="detail-service-fees">
                            <div>
                                <small>Installation Fee</small>
                                <strong>
                                    Rp {{ number_format($contractService->installation_fee ?? $contractService->service?->installation_fee ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div>
                                <small>Monthly Fee</small>
                                <strong>
                                    Rp {{ number_format($contractService->monthly_fee ?? $contractService->service?->monthly_fee ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-file-state">No service selected.</div>
                @endforelse
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Contract Period</div>
            <div class="form-section-desc">Start date, end date, signing date, and signing location.</div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Start Date</label>
                    <input
                        type="text"
                        value="{{ $contract->start_date ? $contract->start_date->format('d M Y') : '-' }}"
                        readonly>
                </div>

                <div class="form-group half">
                    <label>End Date</label>
                    <input
                        type="text"
                        value="{{ $contract->end_date ? $contract->end_date->format('d M Y') : '-' }}"
                        readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label>Signing Date</label>
                    <input
                        type="text"
                        value="{{ $contract->signing_date ? \Carbon\Carbon::parse($contract->signing_date)->format('d M Y') : '-' }}"
                        readonly>
                </div>

                <div class="form-group half">
                    <label>Signing Location</label>
                    <input type="text" value="{{ $contract->signing_location ?? '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Account Manager</div>
            <div class="form-section-desc">Assigned owner of this contract.</div>

            <div class="form-group">
                <label>Assigned AM</label>
                <input
                    type="text"
                    value="{{ $contract->owner ? $contract->owner->name : ($contract->owner_am_id ? 'AM ' . $contract->owner_am_id : '-') }}"
                    readonly>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">Contract File</div>
            <div class="form-section-desc">Open or download uploaded contract documents.</div>

            <div class="contract-file-existing-list">
                @forelse($contract->files as $file)
                    <div class="contract-file-existing-card">
                        <div class="contract-file-existing-info">
                            <strong>{{ $file->file_name }}</strong>
                            <span>
                                Uploaded:
                                {{ $file->created_at ? $file->created_at->format('d/m/Y') : '-' }}
                            </span>
                        </div>

                        <div class="contract-file-actions">
                            <a
                                href="{{ route('contracts.view', $file->id) }}"
                                class="contract-file-view-btn"
                                target="_blank"
                                rel="noopener">
                                View
                            </a>

                            <a
                                href="{{ route('contract-files.download', $file->id) }}"
                                class="contract-file-download-btn">
                                Download
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-file-state">No contract file has been uploaded yet.</div>
                @endforelse
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">BASO Files</div>
            <div class="form-section-desc">Open or download uploaded BASO supporting documents.</div>

            <div class="baso-existing-list">
                @forelse($contract->basoFiles as $baso)
                    <div class="baso-existing-card">
                        <div class="baso-existing-info">
                            <strong>{{ $baso->file_name }}</strong>
                            <span>
                                BASO Date:
                                {{ $baso->baso_date ? \Carbon\Carbon::parse($baso->baso_date)->format('d/m/Y') : '-' }}
                            </span>
                        </div>

                        <div class="baso-file-actions">
                            <a
                                href="{{ route('baso.view', $baso->id) }}"
                                class="baso-view-btn"
                                target="_blank"
                                rel="noopener">
                                View
                            </a>

                            <a
                                href="{{ route('baso.download', $baso->id) }}"
                                class="baso-download-btn">
                                Download
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-file-state">No BASO file has been uploaded yet.</div>
                @endforelse
            </div>
        </div>

        <div class="save-area detail-action-area">
            <a href="{{ route('contracts.index') }}" class="upload-btn">
                Back
            </a>

            @if(auth()->user()->isAccountManager() || auth()->user()->isSupportInputter() || auth()->user()->isSupportPaycall())
                <a href="{{ route('contracts.edit', $contract->id) }}" class="edit-btn">
                    Edit Contract
                </a>
            @endif
        </div>
    </div>
</div>

@endsection