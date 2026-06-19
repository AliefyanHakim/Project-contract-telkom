@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/billing.css') }}">

@endsection

@section('content')

<div class="billing-page">

    <div class="page-title">

        <span class="back-arrow">

            &#8249;

        </span>

        <h1>

            Billing & Invoices

        </h1>

    </div>

</div>

<div class="billing-tabs">

    <a href="#" class="active-tab">
        Outstanding
    </a>

    <a href="/billing/payment-history">
        Payment History
    </a>

</div>

<div class="filter-bar">

    <select>

        <option>
            All Account Managers
        </option>

    </select>

    <select>

        <option>
            All Statuses
        </option>

    </select>


    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search ..."
    >

</div>

<div class="invoice-table">
    <div class="table-wrapper">
        <table>

            <thead>

                <tr>

                    <th>Client Name</th>
                    <th>ID Contract</th>
                    <th>No. Invoice</th>
                    <th>Period</th>
                    <th>Price</th>
                    <th>Due Date</th>
                    <th>Billing State</th>

                </tr>

            </thead>


            <tbody>

                <!-- MERAH -->

                <tr class="critical">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>INV-2026-3026</td>
                    <td>Mei 2026</td>
                    <td>Rp 15.000.000</td>
                    <td>29/05/2026</td>

                    <td class="red-status">

                        Follow-up pending

                    </td>

                </tr>


                <tr class="critical">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>INV-2026-8031</td>
                    <td>Mei 2026</td>
                    <td>Rp 4.000.000</td>
                    <td>29/05/2026</td>

                    <td class="red-status">

                        Follow-up pending

                    </td>

                </tr>


                <!-- KUNING -->

                <tr class="warning">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>INV-2026-7425</td>
                    <td>Mei 2026</td>
                    <td>Rp 15.000.000</td>
                    <td>30/05/2026</td>

                    <td class="yellow-status">

                        Expiring soon

                    </td>

                </tr>


                <tr class="warning">

                    <td>PT Maju Bersama</td>
                    <td>1234567890</td>
                    <td>INV-2026-5903</td>
                    <td>Mei 2026</td>
                    <td>Rp 2.000.000</td>
                    <td>30/05/2026</td>

                    <td class="yellow-status">

                        Expiring soon

                    </td>

                </tr>

            </tbody>

        </table>
</div>

@endsection