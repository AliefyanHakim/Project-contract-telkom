@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')

<div class="contract-page">

    <div class="contract-container">

        <div class="contract-title">
            Edit Contract
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <br>
        @endif

        @if(auth()->user()->isSupportPaycall())

            {{-- FORM PENDEK PAYCALL --}}
            <div class="form-container">
                <form action="{{ route('contracts.update', $contract->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" value="{{ $contract->contract_name }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Contract Number</label>
                        <input type="text" value="{{ $contract->contract_number }}" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label>Start Date</label>
                            <input
                                type="date"
                                name="start_date"
                                value="{{ old('start_date', optional($contract->start_date)->format('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="form-group half">
                            <label>End Date</label>
                            <input
                                type="date"
                                name="end_date"
                                value="{{ old('end_date', optional($contract->end_date)->format('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <button type="submit" class="save-btn">
                        Save Date Changes
                    </button>
                </form>
            </div>

        @else

        <form
            method="POST"
            action="{{ route('contracts.update', $contract->id) }}"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <!-- baris 1 -->
            <div class="form-row">

                <div class="form-group half">
                    <label>Account Number</label>

                    <input
                        type="text"
                        name="account_number"
                        value="{{ old('account_number', $contract->account_number) }}">
                </div>

                <div class="form-group half">
                    <label>S-ID</label>

                    <input
                        type="text"
                        name="sid"
                        value="{{ old('sid', $contract->sid) }}">
                </div>

                <div class="form-group half">
                    <label>Contract Number</label>

                    <input
                        type="text"
                        name="contract_number"
                        value="{{ old('contract_number', $contract->contract_number) }}"
                        readonly>
                </div>

            </div>

            <br><hr><br>

                            <div class="form-section-title">
        Telkom Representative
    </div>

    <div class="form-section-desc">
        Authorized representative from PT Telekomunikasi Indonesia Tbk.
    </div>

            <div class="form-group">
                <label>Name</label>

                <input
                    type="text"
                    name="telkom_name"
                    value="{{ old('telkom_name', $contract->telkom_name) }}">
            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Position</label>

                    <input
                        type="text"
                        name="telkom_position"
                        value="{{ old('telkom_position', $contract->telkom_position) }}">
                </div>

                <div class="form-group half">
                    <label>Unit</label>

                    <input
                        type="text"
                        name="telkom_unit"
                        value="{{ old('telkom_unit', $contract->telkom_unit) }}">
                </div>

            </div>

            <br><hr><br>
                
            <div class="form-section-title">
        Customer Information
    </div>

    <div class="form-section-desc">
        Customer identity, address, and authorized representative.
    </div>

            <div class="form-group">
                <label>Company Name</label>

                <input
                    type="text"
                    name="contract_name"
                    value="{{ old('contract_name', $contract->contract_name) }}">
            </div>

            <div class="form-group">
                <label>Address</label>

                <textarea
                    rows="3"
                    name="customer_address">{{ old('customer_address', $contract->customer_address) }}</textarea>
            </div>

            <div class="form-group">
                <label>NPWP</label>

                <input
                    type="text"
                    name="customer_npwp"
                    value="{{ old('customer_npwp', $contract->customer_npwp) }}">
            </div>
            <br>
            <a>Diwakili secara sah oleh:</a>
            <div class="form-row">

                <div class="form-group half">
                    <label>Name</label>

                    <input
                        type="text"
                        name="customer_pic_name"
                        value="{{ old('customer_pic_name', $contract->customer_pic_name) }}">
                </div>

                <div class="form-group half">
                    <label>Position</label>

                    <input
                        type="text"
                        name="customer_pic_position"
                        value="{{ old('customer_pic_position', $contract->customer_pic_position) }}">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="customer_phone"
                        value="{{ old('customer_phone', $contract->customer_phone) }}">
                </div>

                <div class="form-group half">
                    <label>Email</label>

                    <input
                        type="email"
                        name="customer_email"
                        value="{{ old('customer_email', $contract->customer_email) }}">
                </div>

            </div>
            <br><hr><br>

            <div class="form-section-title">
        Services
    </div>
    <div class="form-section-desc">Manage selected service packages for this contract.</div>

            <div id="services-container">

            @foreach($contract->services as $index => $contractService)

            <div class="service-item">

                <div class="form-row">

                    <div class="form-group half">

                        <label>Service</label>

                        <select
                            name="services[]">

                            @foreach($services as $service)

                                <option
                                    value="{{ $service->id }}"
                                    @selected(
                                        $contractService->service_id == $service->id
                                    )>

                                    {{ $service->service_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="form-group half">

                        <button
                            type="button"
                            class="remove-service upload-btn">

                            Remove

                        </button>

                    </div>

                </div>

            </div>

            @endforeach

            </div>

            <button
                type="button"
                id="add-service"
                class="upload-btn">

                + Add Service
            
            </button>
            <br><br>

            <div class="form-section-title">
        Custom Services
    </div>

            <div id="custom-services-container">

            </div>
            
            <button
                type="button"
                id="add-custom-service"
                class="upload-btn">

                + Add Custom Service

            </button>
        </div>

            <br><hr>

            <div class="form-row">

                <div class="form-group half">

                    <label>Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ optional($contract->start_date)->format('Y-m-d') }}">
                </div>

                <div class="form-group half">

                    <label>End Date</label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ optional($contract->end_date)->format('Y-m-d') }}">
                </div>

            </div>

            <br><hr>


            <!-- Assigned AM -->
           <div class="form-section">
    <div class="form-section-title">Account Manager</div>
    <div class="form-section-desc">Assigned owner of this contract.</div>

    <div class="form-group">
        <label>Assigned AM</label>
        <input
            type="text"
            value="{{ $contract->owner_am_id ? 'AM ' . $contract->owner_am_id : '-' }}"
            readonly>
    </div>
</div>
                        <!-- Upload -->
            <div class="form-section">
    <div class="form-section-title">Contract File</div>
    <div class="form-section-desc">
        View existing contract files or upload a replacement file.
    </div>

    @if($contract->files && $contract->files->count() > 0)

        <div class="contract-file-existing-list">

            @foreach($contract->files as $file)

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
                            href="{{ route('contracts.file.view', $file->id) }}"
                            class="contract-file-view-btn">
                            View
                        </a>
                    </div>

                </div>

            @endforeach

        </div>

    @endif

    <div class="contract-file-clean-row">

        <div class="contract-file-clean-field">
            <label>Upload Contract File</label>

            <label class="contract-file-picker">
                <input
                    type="file"
                    name="file"
                    accept=".pdf,.doc,.docx"
                    class="contract-file-input">

                <span class="contract-file-icon">
                    DOC
                </span>

                <span class="contract-file-text">
                    Choose contract file
                </span>
            </label>

            <small class="form-hint">
                Accepted formats: PDF, DOC, DOCX.
            </small>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const contractFileInput = document.querySelector('.contract-file-input');

    if (contractFileInput) {
        contractFileInput.addEventListener('change', function () {
            const picker = this.closest('.contract-file-picker');
            const text = picker.querySelector('.contract-file-text');

            if (this.files && this.files.length > 0) {
                text.textContent = this.files[0].name;
            } else {
                text.textContent = 'Choose contract file';
            }
        });
    }
});
</script>

    <div class="form-section">
    <div class="form-section-title">BASO Files</div>
    <div class="form-section-desc">
        Upload supporting BASO documents and their dates.
    </div>

    @if($contract->basoFiles && $contract->basoFiles->count() > 0)
        <div class="baso-existing-list">

            @foreach($contract->basoFiles as $baso)
                <div class="baso-existing-card">

                    <div class="baso-existing-info">
                        <strong>{{ $baso->file_name }}</strong>

                        <span>
                            BASO Date:
                            {{ $baso->baso_date ? \Carbon\Carbon::parse($baso->baso_date)->format('d/m/Y') : '-' }}
                        </span>
                    </div>

                    <a href="{{ route('baso.download', $baso->id) }}"
                       class="baso-view-btn">
                        View
                    </a>

                </div>
            @endforeach

        </div>
    @endif

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

                    <span class="baso-file-text">
                        Choose BASO file
                    </span>
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
    
            <!-- Save button -->
            <div class="save-area">

                <button
                    type="submit"
                    class="save-btn">

                    Save Changes

                </button>
            </div>

        </form>

        @endif

    </div>

</div>

@endsection

@section('scripts')

@if(!auth()->user()->isSupportPaycall())

<script> document.addEventListener('DOMContentLoaded', function ()) {
    const serviceContainer = document.getElementById('services-container');
    const addServiceButton = document.getElementById('add-service');

    if (serviceContainer && addServiceButton) {
        addServiceButton.addEventListener('click', function () {
            const html = `
                <div class="service-row">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Service</label>

                            <select name="services[]" required>
                                <option value="">-- Select Service --</option>

                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->service_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>&nbsp;</label>

                            <button type="button" class="remove-service">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;

            serviceContainer.insertAdjacentHTML('beforeend', html);
        });

        serviceContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-service')) {
                const rows = serviceContainer.querySelectorAll('.service-row');

                if (rows.length > 1) {
                    e.target.closest('.service-row').remove();
                }
            }
        });
    }
}

    const customContainer = document.getElementById('custom-services-container');
    const addCustomButton = document.getElementById('add-custom-service');

    if (customContainer && addCustomButton) {
        let customIndex = 0;

        addCustomButton.addEventListener('click', function () {
            const row = document.createElement('div');

            row.classList.add('custom-service-row');

            row.innerHTML = `
                <div class="form-row">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input
                            type="text"
                            name="custom_services[${customIndex}][service_name]"
                            placeholder="Example: Dedicated Internet">
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="remove-custom-service">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Installation Fee</label>
                        <input
                            type="number"
                            name="custom_services[${customIndex}][installation_fee]"
                            placeholder="0">
                    </div>

                    <div class="form-group">
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
                e.target.closest('.custom-service-row').remove();
            }
        });
    }

    <script> document.addEventListener('DOMContentLoaded', function () {
    const basoContainer = document.getElementById('baso-container');
    const addBasoButton = document.getElementById('add-baso');

    function updateFileName(input) {
        const row = input.closest('.baso-clean-row');
        const text = row.querySelector('.baso-file-text');

        if (input.files && input.files.length > 0) {
            text.textContent = input.files[0].name;
        } else {
            text.textContent = 'Choose BASO file';
        }
    }

    if (basoContainer && addBasoButton) {
        addBasoButton.addEventListener('click', function () {
            const html = `
                <div class="baso-row">
                    <div class="form-group">
                        <label>BASO File</label>
                        <input
                            type="file"
                            name="baso_files[]"
                            accept=".pdf,.doc,.docx">
                    </div>

                    <div class="form-group">
                        <label>BASO Date</label>
                        <input
                            type="date"
                            name="baso_dates[]">
                    </div>

                    <button type="button" class="remove-baso">
                        Remove
                    </button>
                </div>
            `;

            basoContainer.insertAdjacentHTML('beforeend', html);
        });

        basoContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-baso')) {
                const rows = basoContainer.querySelectorAll('.baso-row');

                if (rows.length > 1) {
                    e.target.closest('.baso-row').remove();
                } else {
                    const row = e.target.closest('.baso-row');

                    row.querySelector('input[type="file"]').value = '';
                    row.querySelector('input[type="date"]').value = '';
                }
            }
        });
    }
});
</script>

@endif

@endsection