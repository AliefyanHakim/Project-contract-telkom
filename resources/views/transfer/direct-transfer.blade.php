@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/transfer-request.css') }}">

@endsection

@section('content')

<div class="transfer-page">

    <div class="page-title">

        <span class="back-arrow">
            &#8249;
        </span>

        <h1>
            Contract Request History
        </h1>

    </div>

    <div class="tabs">

        <a href="transfer-request" class="active-tab">
            Approval Requests
        </a>

        <a href="#" class="active-tab">
            Direct Transfer
        </a>

    </div>

    <div class="transfer-toolbar">

        <button class="download-btn">
            Download
            <i class="fa-solid fa-download"></i>
        </button>

        <div class="search-box">
            <input type="text" placeholder="Search ...">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <button class="transfer-btn">
            <i class="fa-solid fa-plus"></i>
            + Transfer Contract
        </button>

    </div>

    <div class="table-container">

        <div class="table-wrapper">

            <table>

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
                    <tr class="approved-row">
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td class="green">Transferred</td>
                    </tr>

                    <tr class="approved-row">
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td class="green">Transferred</td>
                    </tr>

                    <tr class="approved-row">
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td class="green">Transferred</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>


</div>

@endsection