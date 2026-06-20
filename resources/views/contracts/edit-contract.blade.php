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

        <form>
            <!-- baris 1 -->
            <div class="form-row">

                <div class="form-group half">
                    <label>Customer ID Number</label>
                    <input type="text">
                </div>

                <div class="form-group half">
                    <label>ID Contract</label>
                    <input type="text">
                </div>

            </div>

            <br><hr><br>

            <a> 1. PERUSAHAAN PERSEROAN (PERSERO) PT TELEKOMUNIKASI INDONESIA Tbk (TELKOM)<br>
            DIwakili secara sah oleh:<a>

            <div class="form-group">
                <label>Name</label>
                <input type="text">
            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Position</label>
                    <input type="text">
                </div>

                <div class="form-group half">
                    <label>Unit</label>
                    <input type="text">
                </div>

            </div>

            <br><hr><br>

            <a>2. PELANGGAN<br>
                Identitas Perusahaan/Institusi</a>

            <div class="form-group">
                <label>Name</label>
                <input type="text">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text">
            </div>

            <div class="form-group">
                <label>NPWP</label>
                <input type="text">
            </div>

            <br>
            <a>Diwakili secara sah oleh:</a>
            <div class="form-row">

                <div class="form-group half">
                    <label>Name</label>
                    <input type="text">
                </div>

                <div class="form-group half">
                    <label>Position</label>
                    <input type="text">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group half">
                    <label>Phone Number</label>
                    <input type="text">
                </div>

                <div class="form-group half">
                    <label>Email Address</label>
                    <input type="text">
                </div>

            </div>

            <br><hr>

            <div class="form-group">
                <label>Service Type</label>
                <input type="text">
            </div>

            <br><hr>
            <!-- Contract Value -->
            <div class="form-group">
                <label>Contract Value</label>
                <input type="text">
            </div>

            <br><hr>

            <!-- tanggal -->
            <div class="form-row">

                <div class="form-group half">
                    <label>Start Date</label>
                    <input type="date">
                </div>

                <div class="form-group half">
                    <label>End Date</label>
                    <input type="date">
                </div>

            </div>

            <br><hr>


            <!-- Assigned AM -->
            <div class="form-group">
                <label>Assigned AM</label>
                <input type="text">
            </div>


            <!-- Upload -->
            <div class="form-group">
                <label>Upload Contract File</label>

                <div class="upload-box">

                    <input type="file">

                    <button type="button" class="upload-btn">
                        Upload
                    </button>

                </div>
            </div>


            <!-- Save button -->
            <div class="save-area">
                <button class="save-btn">
                    Save
                </button>
            </div>

        </form>

    </div>

</div>

@endsection