@extends('layouts.app')

@section('title', 'Detail AM | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detailam.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $amName = 'Xxxxxxxx Xxxxxxxx';
    $amEmail = 'xxxx.xxxxxx@perusahaan.com';

    $activeCount = 6;
    $criticalCount = 1;
    $expiringCount = 2;

    $contracts = collect([
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2026-03-08',
            'end' => '2026-05-29',
            'price' => 3000000,
            'status' => 'followup',
            'label' => 'Follow-up pending',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2025-11-17',
            'end' => '2026-05-28',
            'price' => 5000000,
            'status' => 'expiring',
            'label' => 'Expiring soon',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2025-02-06',
            'end' => '2026-05-27',
            'price' => 19000000,
            'status' => 'expiring',
            'label' => 'Expiring soon',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2025-11-13',
            'end' => '2026-05-30',
            'price' => 7000000,
            'status' => 'active',
            'label' => 'Active',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2025-08-06',
            'end' => '2026-06-01',
            'price' => 17000000,
            'status' => 'active',
            'label' => 'Active',
        ],
        [
            'client' => 'PT Maju Bersama',
            'contract' => '1234567890',
            'package' => 'Enterprise',
            'start' => '2026-04-20',
            'end' => '2026-07-06',
            'price' => 11000000,
            'status' => 'active',
            'label' => 'Active',
        ],
    ]);
@endphp

<div class="am-page">

    <div class="am-header">
        <a href="{{ url('/dashboard') }}" class="am-back-btn">‹</a>

        <div>
            <h1>Account Manager</h1>
            <p>Monitor contract performance, urgency, and billing status by account manager.</p>
        </div>
    </div>

    <section class="am-profile-card">

        <div class="am-profile-top">

            <div class="am-profile-left">
                <div class="am-avatar">AM</div>

                <div class="am-profile-info">
                    <h2>{{ $amName }}</h2>
                    <p>
                        Account Manager
                        <span>•</span>
                        {{ $amEmail }}
                    </p>
                </div>
            </div>

            <button type="button" class="am-dropdown-btn">
                ⌄
            </button>

        </div>

        <div class="am-summary-grid">

            <div class="am-summary-card active">
                <div class="am-summary-icon">✓</div>

                <div>
                    <p>Active</p>
                    <h3>{{ $activeCount }}</h3>
                    <span>Contracts in healthy status</span>
                </div>
            </div>

            <div class="am-summary-card critical">
                <div class="am-summary-icon">!</div>

                <div>
                    <p>Critical</p>
                    <h3>{{ $criticalCount }}</h3>
                    <span>Need immediate follow-up</span>
                </div>
            </div>

            <div class="am-summary-card expiring">
                <div class="am-summary-icon">⏱</div>

                <div>
                    <p>Expiring Soon</p>
                    <h3>{{ $expiringCount }}</h3>
                    <span>Will expire within 30 days</span>
                </div>
            </div>

        </div>

    </section>

    <section class="am-filter-card">

        <form method="GET" action="{{ route('detailam') }}" class="am-filter-bar">

            <select name="status">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expiring">Expiring Soon</option>
                <option value="followup">Follow-up Pending</option>
            </select>

            <select name="package">
                <option value="">All Packages</option>
                <option value="enterprise">Enterprise</option>
                <option value="premium">Premium</option>
                <option value="basic">Basic</option>
            </select>

            <div class="am-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client, contract ID, or package..."
                >

                <button type="submit">⌕</button>
            </div>

            <a href="#" class="am-download-btn">
                <span>↓</span>
                Download
            </a>

        </form>

    </section>

    <section class="am-table-card">

        <div class="am-table-wrapper">

            <table class="am-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>ID Contract</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Price/month</th>
                        <th>Billing State</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($contracts as $contract)

                        <tr class="am-row {{ $contract['status'] }}">
                            <td>{{ $contract['client'] }}</td>
                            <td>{{ $contract['contract'] }}</td>
                            <td>{{ $contract['package'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($contract['start'])->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($contract['end'])->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($contract['price'], 0, ',', '.') }}</td>
                            <td>
                                <span class="am-status {{ $contract['status'] }}">
                                    {{ $contract['label'] }}
                                </span>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection