@extends('layouts.app')

@section('styles')
<link rel="stylesheet"
href="{{ asset('css/create.css') }}">
@endsection

@section('content')

<div class="contract-page">

    <div class="contract-container">

        <div class="contract-title">
            ✉ Add Contract
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

        <form method="POST"
              action="{{ route('contracts.store') }}">

            @csrf

            {{-- Customer ID + Contract Number --}}
            <div class="form-row">

                <div class="form-group half">
                    <label>Customer ID Number</label>
                    <input type="text"
                           name="customer_id_number"
                           value="{{ old('customer_id_number') }}">
                </div>

                <div class="form-group half">
                    <label>Contract Number</label>
                    <input type="text"
                           name="contract_number"
                           value="{{ old('contract_number') }}">
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
                <input type="text"
                       name="telkom_name"
                       value="{{ old('telkom_name') }}">
            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Position</label>
                    <input type="text"
                           name="telkom_position"
                           value="{{ old('telkom_position') }}">
                </div>

                <div class="form-group half">
                    <label>Unit</label>
                    <input type="text"
                           name="telkom_unit"
                           value="{{ old('telkom_unit') }}">
                </div>

            </div>

            <br><hr><br>

            <strong>
                2. PELANGGAN
            </strong>

            <br>
            Identitas Perusahaan / Institusi

            <br><br>

            <div class="form-group">
                <label>Company Name</label>
                <input type="text"
                       name="contract_name"
                       value="{{ old('contract_name') }}">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea
                    name="customer_address"
                    rows="3">{{ old('customer_address') }}</textarea>
            </div>

            <div class="form-group">
                <label>NPWP</label>
                <input type="text"
                       name="customer_npwp"
                       value="{{ old('customer_npwp') }}">
            </div>

            <br>

            <strong>
                Diwakili secara sah oleh:
            </strong>

            <br><br>

            <div class="form-row">

                <div class="form-group half">
                    <label>Name</label>
                    <input type="text"
                           name="customer_pic_name"
                           value="{{ old('customer_pic_name') }}">
                </div>

                <div class="form-group half">
                    <label>Position</label>
                    <input type="text"
                           name="customer_pic_position"
                           value="{{ old('customer_pic_position') }}">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Phone Number</label>
                    <input type="text"
                           name="customer_phone"
                           value="{{ old('customer_phone') }}">
                </div>

                <div class="form-group half">
                    <label>Email Address</label>
                    <input type="email"
                           name="customer_email"
                           value="{{ old('customer_email') }}">
                </div>

            </div>

            <br><hr><br>

            <h4>Services</h4>

            <div id="services-container">

                <div class="service-row" style="margin-bottom:15px;">

                    <div class="form-row">

                        <div class="form-group half">

                            <label>Service</label>

                            <select
                                name="services[]"
                                required>

                                <option value="">
                                    -- Select Service --
                                </option>

                                @foreach($services as $service)

                                    <option
                                        value="{{ $service->id }}">

                                        {{ $service->service_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="form-group half">

                            <label>&nbsp;</label>

                            <button
                                type="button"
                                class="remove-service upload-btn"
                                style="display:none;">

                                Remove

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <button
                type="button"
                id="add-service"
                class="upload-btn">

                + Add Service

            </button>

            <br><br><hr><br>

            <div class="form-row">

                <div class="form-group half">
                    <label>Start Date</label>

                    <input type="date"
                           name="start_date"
                           value="{{ old('start_date') }}">
                </div>

                <div class="form-group half">
                    <label>End Date</label>

                    <input type="date"
                           name="end_date"
                           value="{{ old('end_date') }}">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Signing Date</label>

                    <input type="date"
                           name="signing_date"
                           value="{{ old('signing_date') }}">
                </div>

                <div class="form-group half">
                    <label>Signing Location</label>

                    <input type="text"
                           name="signing_location"
                           value="{{ old('signing_location') }}">
                </div>

            </div>

            <br><hr><br>

            <div class="form-group">

                <label>Upload Contract File</label>

                <input
                    type="file"
                    name="contract_file"
                    accept=".pdf,.doc,.docx">

            </div>

            <div class="form-group">

                <label>Assigned Account Manager</label>

                <select name="owner_am_id">

                    <option value="">
                        -- Select Account Manager --
                    </option>

                    @foreach($accountManagers as $am)

                        <option
                            value="{{ $am->id }}">

                            {{ $am->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="save-area">

                <button
                    type="submit"
                    class="save-btn">

                    Save Contract

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        console.log('LOADED');

        const container =
            document.getElementById('services-container');

        const addButton =
            document.getElementById('add-service');

        console.log(container);
        console.log(addButton);

        addButton.addEventListener(
            'click',
            function () {

                console.log('CLICKED');

            }
        );
    }
);

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const container =
            document.getElementById(
                'services-container'
            );

        const addButton =
            document.getElementById(
                'add-service'
            );

        addButton.addEventListener(
            'click',
            function () {

                const html = `
                    <div class="service-row"
                         style="margin-bottom:15px;">

                        <div class="form-row">

                            <div class="form-group half">

                                <label>Service</label>

                                <select
                                    name="services[]"
                                    required>

                                    <option value="">
                                        -- Select Service --
                                    </option>

                                    @foreach($services as $service)

                                        <option
                                            value="{{ $service->id }}">

                                            {{ $service->service_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group half">

                                <label>&nbsp;</label>

                                <button
                                    type="button"
                                    class="remove-service upload-btn">

                                    Remove

                                </button>

                            </div>

                        </div>

                    </div>
                `;

                container.insertAdjacentHTML(
                    'beforeend',
                    html
                );
            }
        );

        container.addEventListener(
            'click',
            function (e) {

                if (
                    e.target.classList.contains(
                        'remove-service'
                    )
                ) {

                    e.target
                        .closest('.service-row')
                        .remove();
                }
            }
        );
    }
);

</script>

@endsection

