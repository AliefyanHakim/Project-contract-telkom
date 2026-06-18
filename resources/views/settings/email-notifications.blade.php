@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/email-notifications.css') }}">
@endsection

@section('content')

<div class="email-page">

    <div class="email-container">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="email-title">
            ✉ Automatic Email Alert Settings
        </div>

        <hr>

        <form
            method="POST"
            action="{{ route('settings.email-notifications.update') }}"
        >
            @csrf

            <!-- Email -->

            <div class="form-group">

                <label>
                    Email (notifications will be sent to this email address)
                </label>

                <input
                    type="email"
                    name="notification_email"
                    value="{{ old(
                        'notification_email',
                        $setting?->notification_email
                    ) }}"
                    placeholder="manager@perusahaan.com"
                    required
                >

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

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save Settings
                </button>

            </div>

        </form>

    </div>

</div>

@endsection