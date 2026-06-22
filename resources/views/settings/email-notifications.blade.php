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

    <form action="#" method="POST" class="email-form">
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
                    <label>Manager Email</label>
                    <p>Recipient of all critical alerts</p>

                    <div class="email-input">
                        <span>✉</span>
                        <input type="email" name="manager_email" value="manager@perusahaan.com">
                    </div>
                </div>

                <div class="email-field">
                    <label>Admin / Support Email</label>
                    <p>Recipient of overdue billing alerts</p>

                    <div class="email-input">
                        <span>✉</span>
                        <input type="email" name="admin_email" value="admin@perusahaan.com">
                    </div>
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
                            <option selected>08:00 WIB</option>
                            <option>09:00 WIB</option>
                            <option>10:00 WIB</option>
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
                            <option selected>Monday morning</option>
                            <option>Monday afternoon</option>
                            <option>Friday morning</option>
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