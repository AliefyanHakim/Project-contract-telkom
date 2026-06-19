@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/transfer-contract.css') }}">

@endsection

@section('content')

<div class="transfer-page">

    <div class="page-title">

        <span class="back-arrow">
            &#8249;
        </span>

        <h1>
            Contract List
        </h1>

    </div>

    <div class="transfer-toolbar">

        <div class="search-box">
            <input type="text" placeholder="Search ...">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

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
                        <th>Transfer</th>

                    </tr>

                </thead>

                <tbody>
                    <tr>
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td><input type="checkbox" checked></td>
                    </tr>

                    <tr>
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td><input type="checkbox"></td>
                    </tr>

                    <tr>
                        <td>PT Maju Bersama</td>
                        <td>1234567890</td>
                        <td>XXXXXXX</td>
                        <td>Enterprise</td>
                        <td>11/04/2026</td>
                        <td>29/05/2026</td>
                        <td><input type="checkbox"></td>
                    </tr>

                </tbody>

            </table>



        </div>

    </div>

    <div class="recipient-card">

        <label>Recipient</label>

        <select>
            <option>Select Account Manager</option>
            <option>Rina Dewi</option>
            <option>Budi Santoso</option>
            <option>Agus Wijaya</option>
        </select>

        <textarea placeholder="Enter the reason why you want to transfer this contract..."></textarea>

        <button class="transfer-btn">
            Make Transfer
        </button>

    </div>

</div>

@endsection