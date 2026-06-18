@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/contract-list.css') }}">

@endsection


@section('content')

<div class="contract-page">

    <div class="page-title">

        <span class="back-arrow">

            &#8249;

        </span>

        <h1>

            Contract List

        </h1>

    </div>

    <div class="tabs">

        <a href="/contract-list"
        class="tab {{ request()->is('contract-list') ? 'active' : '' }}">
            Current Contracts
    </a>

    <a href="/closed-contract"
        class="tab {{ request()->is('closed-contract') ? 'active' : '' }}">
            Closed Contracts
    </a>

</div>

<div class="filters">

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


    <select>

        <option>
            All Packages
        </option>

    </select>


    <input
        type="text"
        placeholder="Search ...">


    <button>

        + Add Contract

    </button>

</div>

<div class="table-container">
    <div class="table-wrapper">
        <table>

            <thead>

                <tr>

                    <th>Client Name</th>
                    <th>AM</th>
                    <th>ID Contract</th>
                    <th>Package</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Contract State</th>

                </tr>

            </thead>

            <tbody>
                <tr class="followup-row">

                    <td>PT Maju Bersama</td>
                    <td>XXXXXXX</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>11/04/2026</td>
                    <td>29/05/2026</td>

                    <td class="red-text">

                        Follow-up pending

                    </td>

                </tr>


                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>BBBBBbbb</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>18/04/2026</td>
                    <td>15/06/2026</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>


                <tr class="expiring-row">

                    <td>PT Maju Bersama</td>
                    <td>XXXXXXX</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>04/04/2026</td>
                    <td>31/05/2026</td>

                    <td class="yellow-text">

                        Expiring Soon

                    </td>

                </tr>

                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>BBBBBbbb</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>18/04/2026</td>
                    <td>15/06/2026</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>

                                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>BBBBBbbb</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>18/04/2026</td>
                    <td>15/06/2026</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>
                
                                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>BBBBBbbb</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>18/04/2026</td>
                    <td>15/06/2026</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>

                                <tr class="active-row">

                    <td>PT Maju Bersama</td>
                    <td>BBBBBbbb</td>
                    <td>1234567890</td>
                    <td>Enterprise</td>
                    <td>18/04/2026</td>
                    <td>15/06/2026</td>

                    <td class="green-text">

                        Active

                    </td>

                </tr>

            </tbody>

        </table>
    </div>
</div>

@endsection