@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')

@php
    $serviceRows = collect(old('services', ['']));

    if ($serviceRows->isEmpty()) {
        $serviceRows = collect(['']);
    }

    $oldCustomServices = old('custom_services', []);
@endphp

<div class="contract-page">
    <div class="contract-container">
        <div class="detail-header add-contract-header">
            <div>
                <div class="contract-title">Add Contract</div>
                <div class="detail-subtitle">
                    Create a new customer contract with service package, contract period, assigned AM, and supporting files.
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('contracts.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="form-section-title">Contract Identity</div>
                <div class="form-section-desc">Basic contract identifier and customer account data.</div>

                <div class="form-row three">
                    <div class="form-group half">
                        <label>Account Number</label>
                        <input
                            type="text"
                            name="account_number"
                            value="{{ old('account_number') }}"
                            placeholder="Enter account number">
                    </div>

                    <div class="form-group half">
                        <label>S-ID</label>
                        <input
                            type="text"
                            name="sid"
                            value="{{ old('sid') }}"
                            placeholder="Enter S-ID">
                    </div>

                    <div class="form-group half">
                        <label>Contract Number</label>
                        <input
                            type="text"
                            name="contract_number"
                            value="{{ old('contract_number') }}"
                            placeholder="Enter contract number"
                            required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Telkom Representative</div>
                <div class="form-section-desc">Authorized representative from PT Telekomunikasi Indonesia Tbk.</div>

                <div class="form-group">
                    <label>Name</label>
                    <input
                        type="text"
                        name="telkom_name"
                        value="{{ old('telkom_name') }}"
                        placeholder="Enter Telkom representative name">
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Position</label>
                        <input
                            type="text"
                            name="telkom_position"
                            value="{{ old('telkom_position') }}"
                            placeholder="Enter position">
                    </div>

                    <div class="form-group half">
                        <label>Unit</label>
                        <input
                            type="text"
                            name="telkom_unit"
                            value="{{ old('telkom_unit') }}"
                            placeholder="Enter unit">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Customer Information</div>
                <div class="form-section-desc">Customer identity, address, and authorized representative.</div>

                <div class="form-group">
                    <label>Company Name</label>
                    <input
                        type="text"
                        name="contract_name"
                        value="{{ old('contract_name') }}"
                        placeholder="Enter company name"
                        required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea
                        name="customer_address"
                        rows="3"
                        placeholder="Enter customer address">{{ old('customer_address') }}</textarea>
                </div>

                <div class="form-group">
                    <label>NPWP</label>
                    <input
                        type="text"
                        name="customer_npwp"
                        value="{{ old('customer_npwp') }}"
                        placeholder="Enter NPWP">
                </div>

                <div class="detail-mini-title">Customer Representative</div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Name</label>
                        <input
                            type="text"
                            name="customer_pic_name"
                            value="{{ old('customer_pic_name') }}"
                            placeholder="Enter representative name">
                    </div>

                    <div class="form-group half">
                        <label>Position</label>
                        <input
                            type="text"
                            name="customer_pic_position"
                            value="{{ old('customer_pic_position') }}"
                            placeholder="Enter representative position">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Phone Number</label>
                        <input
                            type="text"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            placeholder="Enter phone number">
                    </div>

                    <div class="form-group half">
                        <label>Email Address</label>
                        <input
                            type="email"
                            name="customer_email"
                            value="{{ old('customer_email') }}"
                            placeholder="Enter email address">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Services</div>
                <div class="form-section-desc">Select service packages attached to this contract.</div>

                <div id="services-container">
                    @foreach($serviceRows as $selectedServiceId)
                        <div class="service-row">
                            <div class="form-row">
                                <div class="form-group half">
                                    <label>Service</label>
                                    <select name="services[]" required>
                                        <option value="" @selected(empty($selectedServiceId))>
                                            -- Select Service --
                                        </option>

                                        @foreach($services as $service)
                                            <option
                                                value="{{ $service->id }}"
                                                @selected((string) $selectedServiceId === (string) $service->id)>
                                                {{ $service->service_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group half">
                                    <label>&nbsp;</label>
                                    <button type="button" class="remove-service upload-btn">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-service" class="upload-btn">
                    + Add Service
                </button>
            </div>

            <div class="form-section">
                <div class="form-section-title">Custom Services</div>
                <div class="form-section-desc">Add a custom service if the required package is not available in the service list.</div>

                <div id="custom-services-container">
                    @foreach($oldCustomServices as $index => $customService)
                        <div class="custom-service-row">
                            <div class="form-row">
                                <div class="form-group half">
                                    <label>Service Name</label>
                                    <input
                                        type="text"
                                        name="custom_services[{{ $index }}][service_name]"
                                        value="{{ $customService['service_name'] ?? '' }}"
                                        placeholder="Example: Dedicated Internet">
                                </div>

                                <div class="form-group half">
                                    <label>&nbsp;</label>
                                    <button type="button" class="remove-custom-service upload-btn">
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group half">
                                    <label>Installation Fee</label>
                                    <input
                                        type="number"
                                        name="custom_services[{{ $index }}][installation_fee]"
                                        value="{{ $customService['installation_fee'] ?? '' }}"
                                        placeholder="0">
                                </div>

                                <div class="form-group half">
                                    <label>Monthly Fee</label>
                                    <input
                                        type="number"
                                        name="custom_services[{{ $index }}][monthly_fee]"
                                        value="{{ $customService['monthly_fee'] ?? '' }}"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-custom-service" class="upload-btn">
                    + Add Custom Service
                </button>
            </div>

            <div class="form-section">
                <div class="form-section-title">Contract Period</div>
                <div class="form-section-desc">Set contract start date, end date, signing date, and signing location.</div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Start Date</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            required>
                    </div>

                    <div class="form-group half">
                        <label>End Date</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Signing Date</label>
                        <input
                            type="date"
                            name="signing_date"
                            value="{{ old('signing_date') }}">
                    </div>

                    <div class="form-group half">
                        <label>Signing Location</label>
                        <input
                            type="text"
                            name="signing_location"
                            value="{{ old('signing_location') }}"
                            placeholder="Enter signing location">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Account Manager</div>
                <div class="form-section-desc">Assign the owner Account Manager for this contract.</div>

                @if(auth()->user()->isAccountManager())
                    <div class="form-group">
                        <label>Assigned AM</label>
                        <input type="text" value="{{ auth()->user()->name }}" readonly>
                    </div>
                @else
                    <div class="form-group">
                        <label>Assigned Account Manager</label>
                        <select name="owner_am_id" required>
                            <option value="">-- Select Account Manager --</option>

                            @foreach($accountManagers as $am)
                                <option
                                    value="{{ $am->id }}"
                                    @selected((string) old('owner_am_id') === (string) $am->id)>
                                    {{ $am->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <div class="form-section">
                <div class="form-section-title">Contract File</div>
                <div class="form-section-desc">Upload the main contract document.</div>

                <div class="contract-file-clean-row">
                    <div class="contract-file-clean-field">
                        <label>Upload Contract File</label>

                        <label class="contract-file-picker">
                            <input
                                type="file"
                                name="file"
                                accept=".pdf,.doc,.docx"
                                class="contract-file-input">

                            <span class="contract-file-icon">DOC</span>
                            <span class="contract-file-text">Choose contract file</span>
                        </label>

                        <small class="form-hint">Accepted formats: PDF, DOC, DOCX. Maximum size: 10 MB.</small>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">BASO Files</div>
                <div class="form-section-desc">Upload supporting BASO documents and their dates.</div>

                <div id="baso-container" class="baso-clean-list">
                    <div class="baso-clean-row">
                        <div class="baso-clean-field">
                            <label>BASO File</label>

                            <label class="baso-file-picker">
                                <input
                                    type="file"
                                    name="baso_files[]"
                                    accept=".pdf,.doc,.docx"
                                    class="baso-file-input">

                                <span class="baso-file-icon">PDF</span>
                                <span class="baso-file-text">Choose BASO file</span>
                            </label>
                        </div>

                        <div class="baso-clean-field">
                            <label>BASO Date</label>
                            <input
                                type="date"
                                name="baso_dates[]"
                                class="baso-date-input">
                        </div>

                        <button type="button" class="remove-baso-clean">
                            Remove
                        </button>
                    </div>
                </div>

                <button type="button" id="add-baso" class="baso-add-btn">
                    + Add BASO
                </button>
            </div>

            <div class="save-area add-action-area">
                <a href="{{ route('contracts.index') }}" class="upload-btn">
                    Cancel
                </a>

                <button type="submit" class="save-btn">
                    Save Contract
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceContainer = document.getElementById('services-container');
    const addServiceButton = document.getElementById('add-service');
    const customContainer = document.getElementById('custom-services-container');
    const addCustomButton = document.getElementById('add-custom-service');
    const basoContainer = document.getElementById('baso-container');
    const addBasoButton = document.getElementById('add-baso');

    if (serviceContainer && addServiceButton) {
        addServiceButton.addEventListener('click', function () {
            const html = `
                <div class="service-row">
                    <div class="form-row">
                        <div class="form-group half">
                            <label>Service</label>
                            <select name="services[]" required>
                                <option value="">-- Select Service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group half">
                            <label>&nbsp;</label>
                            <button type="button" class="remove-service upload-btn">Remove</button>
                        </div>
                    </div>
                </div>
            `;

            serviceContainer.insertAdjacentHTML('beforeend', html);
        });

        serviceContainer.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-service')) {
                return;
            }

            const rows = serviceContainer.querySelectorAll('.service-row');
            const currentRow = e.target.closest('.service-row');

            if (rows.length > 1 && currentRow) {
                currentRow.remove();
                return;
            }

            const select = currentRow ? currentRow.querySelector('select') : null;

            if (select) {
                select.value = '';
            }
        });
    }

    if (customContainer && addCustomButton) {
        let customIndex = customContainer.querySelectorAll('.custom-service-row').length;

        addCustomButton.addEventListener('click', function () {
            const row = document.createElement('div');
            row.classList.add('custom-service-row');

            row.innerHTML = `
                <div class="form-row">
                    <div class="form-group half">
                        <label>Service Name</label>
                        <input
                            type="text"
                            name="custom_services[${customIndex}][service_name]"
                            placeholder="Example: Dedicated Internet">
                    </div>

                    <div class="form-group half">
                        <label>&nbsp;</label>
                        <button type="button" class="remove-custom-service upload-btn">Remove</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Installation Fee</label>
                        <input
                            type="number"
                            name="custom_services[${customIndex}][installation_fee]"
                            placeholder="0">
                    </div>

                    <div class="form-group half">
                        <label>Monthly Fee</label>
                        <input
                            type="number"
                            name="custom_services[${customIndex}][monthly_fee]"
                            placeholder="0">
                    </div>
                </div>
            `;

            customContainer.appendChild(row);
            customIndex++;
        });

        customContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-custom-service')) {
                const row = e.target.closest('.custom-service-row');

                if (row) {
                    row.remove();
                }
            }
        });
    }

    function setFilePickerText(input, emptyText) {
        const picker = input.closest('label');
        const text = picker ? picker.querySelector('.contract-file-text, .baso-file-text') : null;

        if (!text) {
            return;
        }

        text.textContent = input.files && input.files.length > 0
            ? input.files[0].name
            : emptyText;
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('contract-file-input')) {
            setFilePickerText(e.target, 'Choose contract file');
        }

        if (e.target.classList.contains('baso-file-input')) {
            setFilePickerText(e.target, 'Choose BASO file');
        }
    });

    if (basoContainer && addBasoButton) {
        addBasoButton.addEventListener('click', function () {
            const html = `
                <div class="baso-clean-row">
                    <div class="baso-clean-field">
                        <label>BASO File</label>

                        <label class="baso-file-picker">
                            <input
                                type="file"
                                name="baso_files[]"
                                accept=".pdf,.doc,.docx"
                                class="baso-file-input">

                            <span class="baso-file-icon">PDF</span>
                            <span class="baso-file-text">Choose BASO file</span>
                        </label>
                    </div>

                    <div class="baso-clean-field">
                        <label>BASO Date</label>
                        <input
                            type="date"
                            name="baso_dates[]"
                            class="baso-date-input">
                    </div>

                    <button type="button" class="remove-baso-clean">Remove</button>
                </div>
            `;

            basoContainer.insertAdjacentHTML('beforeend', html);
        });

        basoContainer.addEventListener('click', function (e) {
            if (!e.target.classList.contains('remove-baso-clean')) {
                return;
            }

            const rows = basoContainer.querySelectorAll('.baso-clean-row');
            const currentRow = e.target.closest('.baso-clean-row');

            if (rows.length > 1 && currentRow) {
                currentRow.remove();
                return;
            }

            if (currentRow) {
                const fileInput = currentRow.querySelector('.baso-file-input');
                const dateInput = currentRow.querySelector('.baso-date-input');
                const fileText = currentRow.querySelector('.baso-file-text');

                if (fileInput) {
                    fileInput.value = '';
                }

                if (dateInput) {
                    dateInput.value = '';
                }

                if (fileText) {
                    fileText.textContent = 'Choose BASO file';
                }
            }
        });
    }
});
</script>
@endsection