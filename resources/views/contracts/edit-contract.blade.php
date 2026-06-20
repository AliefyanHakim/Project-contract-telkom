@extends('layouts.app')

@section('styles')
<link rel="stylesheet"
href="{{ asset('css/create.css') }}">
@endsection

@section('content')

<div class="contract-page">

    <div class="contract-container">

        <div class="contract-title">
            ✏ Edit Contract
        </div>

        <hr>

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

        <form
            method="POST"
            action="{{ route('contracts.update', $contract->id) }}"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <!-- baris 1 -->
            <div class="form-row">

                <div class="form-group half">
                    <label>Customer ID Number</label>

                    <input
                        type="text"
                        name="customer_id_number"
                        value="{{ old('customer_id_number', $contract->customer_id_number) }}">
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

            <a> 1. PERUSAHAAN PERSEROAN (PERSERO) PT TELEKOMUNIKASI INDONESIA Tbk (TELKOM)<br>
            DIwakili secara sah oleh:<a>

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

            <a>2. PELANGGAN<br>
                Identitas Perusahaan/Institusi</a>

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
            <br><hr>

            <h4>Services</h4>

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
            <select name="owner_am_id">

            @foreach($accountManagers as $am)

            <option
                value="{{ $am->id }}"
                @selected($contract->owner_am_id == $am->id)>

                {{ $am->name }}

            </option>

            @endforeach

            </select>

            @if(auth()->user()->isSupportPaycall())

            <div class="form-group">

                <label>Status</label>

                <select name="status">

                    <option
                        value="active"
                        @selected($contract->status == 'active')>
                        Active
                    </option>

                    <option
                        value="expired"
                        @selected($contract->status == 'expired')>
                        Expired
                    </option>

                    <option
                        value="terminated"
                        @selected($contract->status == 'terminated')>
                        Terminated
                    </option>

                </select>

            </div>

            @endif

            <!-- Upload -->
            <div class="form-group">

                <label>Replace Contract File</label>

                <input
                    type="file"
                    name="contract_file">

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

    </div>

</div>

@endsection

@section('scripts')

<script>

let serviceIndex =
    document.querySelectorAll('.service-item').length;

document
.getElementById('add-service')
.addEventListener('click', function () {

    const html = `
    <div class="service-item" style="margin-top:20px">

        <hr>

        <div class="form-row">

            <div class="form-group half">

                <label>Service</label>

                <select name="services[]">

                    @foreach($services as $service)

                        <option value="{{ $service->id }}">
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
    `;

    document
        .getElementById('services-container')
        .insertAdjacentHTML(
            'beforeend',
            html
        );
});

/*
|--------------------------------------------------------------------------
| Remove Service
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'click',
    function (e) {

        if (
            e.target.classList.contains(
                'remove-service'
            )
        ) {

            e.target
                .closest('.service-item')
                .remove();
        }
    }
);

</script>

@endsection