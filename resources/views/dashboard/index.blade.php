@extends('layouts.app')

@section('content')

<div class="content">

    <!-- Warning -->
    <div class="warning-box">

        <div class="warning-text">
            4 contracts will expire within the next 30 days — follow up or renew immediately.
        </div>

        <div class="warning-view">
            view
        </div>

    </div>


    <!-- Cards -->
    <div class="cards">

        <div class="card card-blue">

            <h3>Total Active Contracts</h3>

            <div class="number">
                123
            </div>

            <div class="title">
                Contracts
            </div>

            <p>
                across 5 Account Managers
            </p>

        </div>



        <div class="card card-red">

            <h3>Expiring &lt; 7 Days</h3>

            <div class="number">
                2
            </div>

            <div class="title">
                Contracts
            </div>

            <p>
                Immediate action required
            </p>

        </div>



        <div class="card card-yellow">

            <h3>Expiring &lt; 30 Days</h3>

            <div class="number">
                45
            </div>

            <div class="title">
                Contracts
            </div>

            <p>
                Follow up soon
            </p>

        </div>



        <div class="card card-green">

            <h3>Outstanding Invoices</h3>

            <div class="money">
                145.000.000
            </div>

            <div class="title">
                Rupiah
            </div>

            <p>
                6 pending invoices
            </p>

        </div>

    </div>

    <div class="contract-section">

    <h2>
        Contracts Near Expiration — Attention Needed
    </h2>

    <div class="contract-box">

        <div class="contract-item">

            <div class="contract-info">

                <h3>PT Xxxxx Xxxxxxxx — Paket Enterprise</h3>

                <p>
                    AM: Budi Santoso · End date: 29/05/2026 · Rp 5.000.000/month
                </p>

            </div>

            <div class="days red">
                0 days left
            </div>

            <div class="detail">
                detail >
            </div>

        </div>



        <div class="contract-item">

            <div class="contract-info">

                <h3>PT Xxxxx Xxxxxxxx — Paket Enterprise</h3>

                <p>
                    AM: Rina Dewi · End date: 29/05/2026 · Rp 4.500.000/month
                </p>

            </div>

            <div class="days red">
                3 days left
            </div>

            <div class="detail">
                detail >
            </div>

        </div>



        <div class="contract-item">

            <div class="contract-info">

                <h3>PT Xxxxx Xxxxxxxx — Paket Enterprise</h3>

                <p>
                    AM: Budi Santoso · End date: 31/05/2026 · Rp 12.000.000/month
                </p>

            </div>

            <div class="days yellow">
                12 days left
            </div>

            <div class="detail">
                detail >
            </div>

        </div>



        <div class="contract-item">

            <div class="contract-info">

                <h3>PT Xxxxx Xxxxxxxx — Paket Enterprise</h3>

                <p>
                    AM: Budi Santoso · End date: 01/06/2026 · Rp 10.000.000/month
                </p>

            </div>

            <div class="days yellow">
                23 days left
            </div>

            <div class="detail">
                detail >
            </div>

        </div>

    </div>

</div>

<div class="summary-section">

    <h2>
        Summary by Account Manager
    </h2>

    <div class="summary-box">

        <table>

            <thead>

                <tr>

                    <th>Account Manager</th>

                    <th>Total Clients</th>

                    <th>Active</th>

                    <th>Expiring &lt; 30 days</th>

                    <th>Monthly Value</th>

                    <th>Billing Pending</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>Account Manager 1</td>

                    <td>16</td>

                    <td>14</td>

                    <td>3</td>

                    <td>Rp 24.000.000</td>

                    <td>4 invoices</td>

                </tr>

                <tr>

                    <td>Account Manager 2</td>

                    <td>12</td>

                    <td>12</td>

                    <td>2</td>

                    <td>Rp 23.000.000</td>

                    <td>2 invoices</td>

                </tr>

                <tr>

                    <td>Account Manager 3</td>

                    <td>14</td>

                    <td>11</td>

                    <td>1</td>

                    <td>Rp 25.000.000</td>

                    <td>3 invoices</td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection

