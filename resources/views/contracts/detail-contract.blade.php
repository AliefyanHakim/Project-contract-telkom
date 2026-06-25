@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/create.css') }}">

@endsection

@section('content')

<div class="contract-page">

    <div class="contract-container">

        <div class="contract-title">
            📄 Contract Detail
        </div>

        <hr>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

            <br>

        @endif

        {{-- CONTRACT INFORMATION --}}

        <div class="form-row">

            <div class="form-group half">

                <label>Account Number</label>

                <input
                    type="text"
                    value="{{ $contract->account_number }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>S-ID</label>

                <input
                    type="text"
                    value="{{ $contract->sid }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>Contract Number</label>

                <input
                    type="text"
                    value="{{ $contract->contract_number }}"
                    readonly>

            </div>

        </div>

        <br><hr><br>

        <strong>
            1. PERUSAHAAN PERSEROAN (PERSERO)
            PT TELEKOMUNIKASI INDONESIA Tbk
        </strong>

        <br>
        Diwakili secara sah oleh:

        <br><br>

        <div class="form-group">

            <label>Name</label>

            <input
                type="text"
                value="{{ $contract->telkom_name }}"
                readonly>

        </div>

        <div class="form-row">

            <div class="form-group half">

                <label>Position</label>

                <input
                    type="text"
                    value="{{ $contract->telkom_position }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>Unit</label>

                <input
                    type="text"
                    value="{{ $contract->telkom_unit }}"
                    readonly>

            </div>

        </div>

        <br><hr><br>

        <strong>
            2. CUSTOMER
        </strong>

        <br>
        Company Information

        <br><br>

        <div class="form-group">

            <label>Company Name</label>

            <input
                type="text"
                value="{{ $contract->contract_name }}"
                readonly>

        </div>

        <div class="form-group">

            <label>Address</label>

            <textarea
                rows="4"
                readonly>{{ $contract->customer_address }}</textarea>

        </div>

        <div class="form-group">

            <label>NPWP</label>

            <input
                type="text"
                value="{{ $contract->customer_npwp }}"
                readonly>

        </div>

        <br>

        <strong>
            Customer Representative
        </strong>

        <br><br>

        <div class="form-row">

            <div class="form-group half">

                <label>Name</label>

                <input
                    type="text"
                    value="{{ $contract->customer_pic_name }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>Position</label>

                <input
                    type="text"
                    value="{{ $contract->customer_pic_position }}"
                    readonly>

            </div>

        </div>

        <div class="form-row">

            <div class="form-group half">

                <label>Phone Number</label>

                <input
                    type="text"
                    value="{{ $contract->customer_phone }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>Email Address</label>

                <input
                    type="text"
                    value="{{ $contract->customer_email }}"
                    readonly>

            </div>

        </div>

        <br><hr><br>

        {{-- SERVICES --}}

        <h4>
            Services
        </h4>

        @forelse($contract->services as $contractService)

            <div
                style="
                    border:1px solid #ddd;
                    border-radius:8px;
                    padding:12px;
                    margin-bottom:10px;
                    background:#fafafa;
                ">

                <strong>

                    {{ $contractService->service->service_name }}

                </strong>

            </div>

        @empty

            <p>
                No service selected.
            </p>

        @endforelse

        <br><hr><br>

        {{-- CONTRACT DATE --}}

        <div class="form-row">

            <div class="form-group half">

                <label>Start Date</label>

                <input
                    type="text"
                    value="{{ optional($contract->start_date)->format('d M Y') }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>End Date</label>

                <input
                    type="text"
                    value="{{ optional($contract->end_date)->format('d M Y') }}"
                    readonly>

            </div>

        </div>

        <div class="form-row">

            <div class="form-group half">

                <label>Signing Date</label>

                <input
                    type="text"
                    value="{{ optional($contract->signing_date)->format('d M Y') }}"
                    readonly>

            </div>

            <div class="form-group half">

                <label>Signing Location</label>

                <input
                    type="text"
                    value="{{ $contract->signing_location }}"
                    readonly>

            </div>

        </div>

        <br><hr><br>

        {{-- ACCOUNT MANAGER --}}

        <div class="form-group">

            <label>Assigned Account Manager</label>

            <input
                type="text"
                value="{{ $contract->owner?->name }}"
                readonly>

        </div>

        <br><hr><br>

        {{-- FILE SECTION --}}
        <div class="form-group">

            <label>Contract Files</label>

            @forelse($contract->files as $file)

                <div class="file-row">

                    <div class="file-name">
                        {{ $file->file_name }}
                    </div>

                    <a
                        href="{{ asset('storage/' . $file->file_path) }}"
                        target="_blank"
                        class="view-btn">

                        View

                    </a>

                    <a
                        href="{{ route('contract-files.download', $file->id) }}"
                        class="download-btn">

                        Download

                    </a>

                </div>

            @empty

                <div class="file-row">

                    <div class="file-name">
                        No contract file uploaded.
                    </div>

                </div>

            @endforelse

        </div>

        <div class="form-group">

            <label>BASO Files</label>

            @forelse($contract->basoFiles as $baso)

                <div class="file-row">

                    <div class="file-name">

                        {{ $baso->file_name }}

                        @if($baso->baso_date)

                            <small>
                                ({{ \Carbon\Carbon::parse($baso->baso_date)->format('d/m/Y') }})
                            </small>

                        @endif

                    </div>

                    <a
                        href="{{ asset('storage/' . $baso->file_path) }}"
                        target="_blank"
                        class="view-btn">

                        View

                    </a>

                    <a
                        href="{{ route('baso.download', $baso->id) }}"
                        class="download-btn">

                        Download

                    </a>

                </div>

            @empty

                <div class="file-row">

                    <div class="file-name">
                        No BASO file uploaded.
                    </div>

                </div>

            @endforelse

        </div>

        <div class="save-area">

            <div class="save-area">

                    <a href="{{ route('contracts.index') }}"
                    class="save-btn">
                        Back
                    </a>

                    @if(auth()->user()->isAccountManager() || auth()->user()->isSupportInputter() || auth()->user()->isSupportPaycall())
    <a href="{{ route('contracts.edit', $contract->id) }}">
        <button type="button" class="edit-btn">
            Edit
        </button>
    </a>
@endif

            </div>

        </div>

    </div>

</div>

@endsection