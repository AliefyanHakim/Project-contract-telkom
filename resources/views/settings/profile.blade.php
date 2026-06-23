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
            href="{{ route('dashboard') }}"
            class="back-arrow"
            style="text-decoration:none;">

            &#8249;

        </a>

        <h1>

            Profile

        </h1>

    </div>

    {{-- Profile Header --}}
    <div class="profile-card">

        <div class="profile-section">

            <div class="profile-left">

            <div class="profile-icon">
                <img
                    src="{{ $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : asset('images/default-avatar.png') }}"
                    alt="{{ $user->name }}"
                >
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

    {{-- Detail User --}}
    <div class="profile-card">

        <div class="form-group">

            <label>
                Full Name
            </label>

            <input
                type="text"
                value="{{ $user->name }}"
                readonly>

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

        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="text"
                value="{{ $user->email }}"
                readonly>

        </div>

        <div class="form-group">

            <label>
                Status
            </label>

            <input
                type="text"
                value="{{ ucfirst($user->status) }}"
                readonly>

        </div>

    </div>

    {{-- Action Button --}}
    <div class="save-area">

        <a
            href="{{ route('profile.edit') }}"
            class="edit-btn">

            Edit Profile

        </a>

        <form
            action="{{ route('logout') }}"
            method="POST"
            style="display:inline;">

            @csrf

            <button
                type="submit"
                class="logout-btn">

                Logout

            </button>

        </form>

    </div>

</div>

@endsection