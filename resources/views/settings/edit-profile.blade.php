@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/profile.css') }}">

@endsection

@section('content')

@php

    $user = auth()->user();

    $roleName = match($user->role_id) {

        \App\Models\User::ROLE_MANAGER
            => 'Manager',

        \App\Models\User::ROLE_ACCOUNT_MANAGER
            => 'Account Manager',

        \App\Models\User::ROLE_SUPPORT_INPUTTER
            => 'Support Inputter',

        \App\Models\User::ROLE_SUPPORT_PAYCALL
            => 'Support Paycall',

        default
            => 'User'
    };

@endphp

<div class="profile-page">

    <div class="page-title">

        <a
            href="{{ route('profile') }}"
            class="back-arrow"
            style="text-decoration:none;">

            &#8249;

        </a>

        <h1>

            Edit Profile

        </h1>

    </div>

    @if(session('success'))

        <div
            style="
                background:#d4edda;
                color:#155724;
                padding:12px;
                border-radius:8px;
                margin-bottom:20px;
            ">

            {{ session('success') }}

        </div>

    @endif

    @if($errors->any())

        <div
            style="
                background:#f8d7da;
                color:#721c24;
                padding:12px;
                border-radius:8px;
                margin-bottom:20px;
            ">

            <ul style="margin:0;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Header --}}
    <div class="profile-card">

        <div class="profile-section">

            <div class="profile-left">

                <div class="profile-icon">

                    👤

                </div>

                <div>

                    <h2>

                        {{ $user->name }}

                    </h2>

                    <p>

                        {{ $roleName }}
                        ·
                        {{ $user->email }}

                    </p>

                </div>

            </div>

        </div>

    </div>

    <form
        action="{{ route('profile.update') }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="profile-card">

            <div class="form-group">

                <label>

                    Full Name

                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required>

            </div>

            <div class="form-group">

                <label>

                    Email

                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required>

            </div>

            <div class="form-group">

                <label>

                    Position

                </label>

                <input
                    type="text"
                    value="{{ $roleName }}"
                    readonly>

            </div>

            <hr style="margin:20px 0;">

            <div class="form-group">

                <label>

                    New Password

                </label>

                <input
                    type="password"
                    name="password">

            </div>

            <div class="form-group">

                <label>

                    Confirm Password

                </label>

                <input
                    type="password"
                    name="password_confirmation">

            </div>

        </div>

        <div class="save-area">

            <button
                type="submit"
                class="edit-btn">

                Save Changes

            </button>

            <a
                href="{{ route('profile') }}"
                class="logout-btn">

                Cancel

            </a>

        </div>

    </form>

</div>

@endsection