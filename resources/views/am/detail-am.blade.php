@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/detailam.css') }}">

@endsection


@section('content')

<div class="am-page">

    <div class="page-title">

        <span class="back-arrow">

            &#8249;

        </span>

        <h1>

            Account Manager

        </h1>

    </div>


    <div class="am-card">

        <div class="profile-section">

            <div class="profile-left">

                <div class="profile-icon">

                    👤

                </div>


                <div>

                    <h2>

                        Xxxxxxx Xxxxxxx

                    </h2>

                    <p>

                        Account Manager · xxxx.xxxxxx@perusahaan.com

                    </p>

                </div>

            </div>


            <button class="dropdown-btn">

                ▼

            </button>

        </div>

        <div class="summary-cards">

            <div class="summary-card active-card">

                <h3>Actives</h3>

                <span>6</span>

            </div>


            <div class="summary-card critical-card">

                <h3>Critical</h3>

                <span>1</span>

            </div>


            <div class="summary-card expiring-card">

                <h3>Expiring soon</h3>

                <span>2</span>

            </div>

        </div>

    </div>

    <div class="filter-bar">

        <select>
            <option>All Statuses</option>
        </select>

        <select>
            <option>All Packages</option>
        </select>

        <input type="text"
            placeholder="Search ...">

        <button class="download-btn">

            Download

        </button>

    </div>

    <div class="contract-table">

        <table>

            <thead>

                <tr>

                    <th>Client Name</th>
                    <th>ID Contract</th>
                    <th>Package</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Price/month</th>
                    <th>Billing State</th>

                </tr>

            </thead>

            <tbody>

                <tr class="critical-row">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>08/03/2026</td>
                    <td>29/05/2026</td>
                    <td>Rp 3.000.000</td>

                    <td class="red-text">

                        Follow-up pending

                    </td>

                </tr>


                <tr class="expiring-row">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>17/11/2025</td>
                    <td>28/05/2026</td>
                    <td>Rp 5.000.000</td>

                    <td class="yellow-text">

                        Expiring soon

                    </td>

                </tr>


                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>13/11/2025</td>
                    <td>30/05/2026</td>
                    <td>Rp 7.000.000</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection