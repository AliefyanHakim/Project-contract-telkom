@extends('layouts.app')

@section('title', 'Email Notifications | VasTrack')

@section('content')

<div class="email-notif-page">

    <div class="email-notif-header">
        <h1>Email Notifications</h1>
        <p>Configure automatic email alerts and delivery schedules for important contract notifications.</p>
    </div>

    <form action="#" method="POST" class="email-notif-form">
        @csrf

        <section class="email-notif-card">
            <div class="email-notif-card-header">
                <div class="email-notif-header-icon mail">
                    ✉
                </div>

                <div>
                    <h2>Automatic Email Alert Settings</h2>
                </div>
            </div>

            <div class="email-field-group">
                <label for="manager_email">Manager Email</label>
                <p>Recipient of all critical alerts</p>

                <div class="email-input-box">
                    <div class="email-input-icon">✉</div>
                    <input 
                        type="email" 
                        id="manager_email" 
                        name="manager_email" 
                        value="manager@perusahaan.com"
                    >
                </div>
            </div>

            <div class="email-field-group">
                <label for="support_email">Admin / Support Email</label>
                <p>Recipient of overdue billing alerts</p>

                <div class="email-input-box">
                    <div class="email-input-icon">✉</div>
                    <input 
                        type="email" 
                        id="support_email" 
                        name="support_email" 
                        value="admin@perusahaan.com"
                    >
                </div>
            </div>
        </section>

        <section class="email-notif-card schedule-section">
            <div class="email-notif-card-header">
                <div class="email-notif-header-icon alert">
                    🔔
                </div>

                <div>
                    <h2>Alert Email Delivery Schedule</h2>
                </div>
            </div>

            <div class="schedule-table-card">
                <div class="schedule-row">
                    <div class="schedule-info">
                        <div class="schedule-icon urgent">
                            📅
                        </div>

                        <div>
                            <h3>Contracts expiring in &lt; 7 days</h3>
                            <p>Receive daily emails for contracts nearing expiration within the next 7 days.</p>
                        </div>
                    </div>

                    <div class="schedule-action">
                        <span class="schedule-badge urgent">Daily</span>

                        <select name="daily_schedule">
                            <option selected>08:00 WIB</option>
                            <option>09:00 WIB</option>
                            <option>10:00 WIB</option>
                        </select>
                    </div>
                </div>

                <div class="schedule-row">
                    <div class="schedule-info">
                        <div class="schedule-icon warning">
                            📅
                        </div>

                        <div>
                            <h3>Contracts expiring in 8–30 days</h3>
                            <p>Receive weekly summary emails for contracts expiring in 8–30 days.</p>
                        </div>
                    </div>

                    <div class="schedule-action">
                        <span class="schedule-badge warning">Weekly</span>

                        <select name="weekly_schedule">
                            <option selected>Monday morning</option>
                            <option>Monday afternoon</option>
                            <option>Friday morning</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="email-notif-footer">
                <div class="email-note">
                    <span>i</span>
                    <p>Emails will be sent to the recipients above based on the schedule you set.</p>
                </div>

                <button type="submit" class="save-email-btn">
                    <span>✓</span>
                    Save Settings
                </button>
            </div>
        </section>

    </form>

</div>

@endsection