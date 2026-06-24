@extends('layouts.app')

@section('title', 'Email Notifications | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/email-notifications.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="email-page">

    <div class="email-header">
        <h1>Email Notifications</h1>
        <p>Configure automatic email alerts and delivery schedules for important contract notifications.</p>
    </div>

    @if ($errors->any())
        <div class="form-error-box">
            <strong>Unable to save settings.</strong>
            Please check the required fields below.
        </div>
    @endif
    
        <form
            action="{{ route('settings.email-notifications.update') }}"
            method="POST"
            class="email-form"
        >
        @csrf

        <section class="email-card">
            <div class="email-card-title">
                <div class="email-title-icon">✉</div>

                <div>
                    <h2>Automatic Email Alert Settings</h2>
                    <p>Set recipients for critical contract and billing notifications.</p>
                </div>
            </div>

            <div class="email-grid">
                <div class="email-field">
                    <label>Notification Recipient Email</label>
                    <p>Recipient of all critical alerts</p>

                    <div class="email-input">
                        <span>✉</span>
                        <input
                            type="email"
                            name="manager_email"
                            value="{{ old('manager_email', $settings->manager_email) }}"
                            required
                        >
                    </div>

                    <small class="email-help">
                        Set the email address that will receive contract reminder notifications.
                    </small>

                    @error('manager_email')
                        <div class="email-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="email-field">
                <label>Support Recipients</label>
                <p>
                Recipient of all critical alerts</p>

                    <div class="email-input readonly">
                        <span>✉</span>
                        <input
                            type="text"
                            value="Automatic from user database"
                            readonly
                        >
                    </div>
                    <small class="email-help">
                        All Support users will automatically receive notifications.
                    </small>
                </div>
            </div>
        </section>

        <section class="email-card">
            <div class="email-card-title">
                <div class="email-title-icon warning">🔔</div>

                <div>
                    <h2>Alert Email Delivery Schedule</h2>
                    <p>Manage delivery frequency for contract expiration reminders.</p>
                </div>
            </div>

            <div class="schedule-list">

                <div class="schedule-row">
                    <div class="schedule-left">
                        <div class="schedule-icon urgent">📅</div>

                        <div>
                            <h3>Contracts expiring in &lt; 7 days</h3>
                            <p>Receive daily emails for contracts nearing expiration within the next 7 days.</p>
                        </div>
                    </div>

                    <div class="schedule-right">
                        <span class="schedule-badge urgent">Daily</span>

                        <select name="daily_schedule">

                            <option
                                value="08:00"
                                {{ $settings->daily_schedule == '08:00' ? 'selected' : '' }}
                            >
                                08:00 WIB
                            </option>

                            <option
                                value="09:00"
                                {{ $settings->daily_schedule == '09:00' ? 'selected' : '' }}
                            >
                                09:00 WIB
                            </option>

                            <option
                                value="10:00"
                                {{ $settings->daily_schedule == '10:00' ? 'selected' : '' }}
                            >
                                10:00 WIB
                            </option>

                        </select>
                    </div>
                </div>

                <div class="schedule-row">
                    <div class="schedule-left">
                        <div class="schedule-icon expiring">📅</div>

                        <div>
                            <h3>Contracts expiring in 8–30 days</h3>
                            <p>Receive weekly summary emails for contracts expiring in 8–30 days.</p>
                        </div>
                    </div>

                    <div class="schedule-right">
                        <span class="schedule-badge expiring">Weekly</span>

                        <select name="weekly_schedule">

                            <option
                                value="monday_morning"
                                {{ $settings->weekly_schedule == 'monday_morning' ? 'selected' : '' }}
                            >
                                Monday morning
                            </option>

                            <option
                                value="monday_afternoon"
                                {{ $settings->weekly_schedule == 'monday_afternoon' ? 'selected' : '' }}
                            >
                                Monday afternoon
                            </option>

                            <option
                                value="friday_morning"
                                {{ $settings->weekly_schedule == 'friday_morning' ? 'selected' : '' }}
                            >
                                Friday morning
                            </option>

                        </select>
                    </div>
                </div>

            </div>

            <div class="email-footer">
                <div class="email-note">
                    <span>i</span>
                    <p>Emails will be sent to the recipients above based on the schedule you set.</p>
                </div>

                <button type="submit" class="email-save-btn">
                    <span>✓</span>
                    Save Settings
                </button>
            </div>
        </section>

    </form>

</div>

@endsection