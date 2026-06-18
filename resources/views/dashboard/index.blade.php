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

            <p>
                6 pending invoices
            </p>

        </div>

    </div>

</div>

@endsection

