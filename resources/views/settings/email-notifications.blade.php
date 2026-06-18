@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css\email-notifications.css') }}">
@endsection

@section('content')

<div class="email-page">

    <div class="email-container">

        <div class="email-title">
            ✉ Automatic Email Alert Settings
        </div>

        <hr>

        <!-- Manager Email -->
        <div class="form-group">

            <label>
                Manager Email (recipient of all critical alerts)
            </label>

            <input
                type="text"
                value="manager@perusahaan.com">

        </div>


        <!-- Admin Email -->
        <div class="form-group">

            <label>
                Admin / Support Email (recipient of overdue billing alerts)
            </label>

            <input
                type="text"
                value="admin@perusahaan.com">

        </div>


        <!-- Schedule Box -->

        <div class="schedule-box">

            <h3>
                Alert Email Delivery Schedule
            </h3>


            <div class="schedule-row">

                <div>
                    Contracts expiring in &lt; 7 days
                </div>

                <div class="daily">
                    Daily email (08:00 WIB)
                </div>

            </div>


            <div class="schedule-row">

                <div>
                    Contracts expiring in 8–30 days
                </div>

                <div class="weekly">
                    Weekly email (Monday morning)
                </div>
            </div>
        </div>


        <div class="button-wrapper">
            <button class="save-btn">
                Save settings

            </button>
        </div>
    </div>
</div>


@endsection