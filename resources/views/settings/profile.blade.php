@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/profile.css') }}">

@endsection

@section('content')

<div class="profile-page">

    <div class="page-title">

        <span class="back-arrow">

            &#8249;

        </span>

        <h1>

            Account Manager

        </h1>

    </div>


    <div class="profile-card">

        <div class="profile-section">

            <div class="profile-left">

                <div class="profile-icon">

                    👤

                </div>

                <div>

                    <h2>

                        Nama Manager

                    </h2>

                    <p>

                        Manager · manager@telkom.com

                    </p>

                </div>

            </div>
        </div>

    </div>

    <div class="profile-card">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text">
        </div>

        <div class="form-group">
            <label>Employee ID</label>
            <input type="text">
        </div>

        <div class="form-group">
            <label>Position</label>
            <input type="text">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text">
        </div>

    </div>

    <div class="save-area">

        <a href="#"
        class="edit-btn">
            Edit
        </a>

        <a href="#"
        class="logout-btn">
            Logout
        </a>

    </div>

</div>

@endsection