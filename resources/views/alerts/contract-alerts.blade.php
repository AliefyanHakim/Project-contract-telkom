@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/contract-alerts.css') }}">

@endsection

@section('content')

<div class="alerts-page">

    <div class="alerts-container">

        <div class="alert-title">
            Automatic Email Alert Settings
        </div>

        <hr>

        <div class="alert-summary">

            <div class="summary-card red">

                <h1>7 days before</h1>

                <p>Daily email to AM & Manager</p>

            </div>


            <div class="summary-card yellow">

                <h1>30 days before</h1>

                <p>Weekly email to AM</p>

            </div>

        </div>

        <div class="contract-section">

            <h2>⚠ Critical Contracts (≤7 days)</h2>

            <div class="contract-box">

                <div class="contract-item">

                    <div class="contract-left">

                        <div class="dot red-dot"></div>

                        <div>

                            <h3>PT Maju Bersama</h3>

                            <p>
                                AM: Budi Santoso · Enterprise · Rp 12.000.000/month ·
                                <span class="email-red">
                                    Email terkirim hari ini
                                </span>
                            </p>

                        </div>

                    </div>

                    <span class="days-red">
                        4 days
                    </span>

                </div>


                <div class="contract-item">

                    <div class="contract-left">

                        <div class="dot red-dot"></div>

                        <div>

                            <h3>CV Sinar Terang</h3>

                            <p>
                                AM: Rina Dewi · Pro · Rp 4.500.000/month ·
                                <span class="email-red">
                                    Email terkirim hari ini
                                </span>
                            </p>

                        </div>

                    </div>

                    <span class="days-red">
                        5 days
                    </span>

                </div>

            </div>

        </div>

        <div class="contract-section">

            <h2>
                🟡 Contracts Requiring Attention (8–30 days)
            </h2>

            <div class="contract-box">

                <div class="contract-item">

                    <div class="contract-left">

                        <div class="dot yellow-dot"></div>

                        <div>

                            <h3>PT Karya Nusantara</h3>

                            <p>
                                AM: Agus Wijaya · Standard · Rp 2.800.000/month
                            </p>

                        </div>

                    </div>

                    <span class="days-yellow">
                        20 days
                    </span>

                </div>



                <div class="contract-item">

                    <div class="contract-left">

                        <div class="dot yellow-dot"></div>

                        <div>

                            <h3>UD Berkah Jaya</h3>

                            <p>
                                AM: Siti Rahayu · Basic · Rp 1.200.000/month
                            </p>

                        </div>

                    </div>

                    <span class="days-yellow">
                        27 days
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection