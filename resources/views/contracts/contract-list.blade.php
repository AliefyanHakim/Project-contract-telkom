@extends('layouts.app')

@section('title', 'Contract List | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contract-list.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $rows = collect([
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-11', 'end' => '2026-05-29', 'status' => 'followup', 'label' => 'Follow-up pending'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-18', 'end' => '2026-06-15', 'status' => 'active', 'label' => 'Active'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-04', 'end' => '2026-05-31', 'status' => 'expiring', 'label' => 'Expiring Soon'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-16', 'end' => '2026-05-30', 'status' => 'expiring', 'label' => 'Expiring Soon'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-18', 'end' => '2026-06-18', 'status' => 'active', 'label' => 'Active'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-17', 'end' => '2026-06-20', 'status' => 'active', 'label' => 'Active'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-13', 'end' => '2026-05-29', 'status' => 'followup', 'label' => 'Follow-up pending'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-12', 'end' => '2026-06-22', 'status' => 'active', 'label' => 'Active'],
    ]);
@endphp

<div class="contract-page">

    <div class="contract-header">
        <a href="{{ url('/dashboard') }}" class="contract-back-btn">‹</a>

        <div>
            <h1>Contract List</h1>
            <p>Manage current and closed contracts in one place.</p>
        </div>
    </div>

    <div class="contract-tabs">
    <a href="{{ url('/contract-list') }}" class="contract-tab active">
        Current Contracts
    </a>

    <a href="{{ url('/closed-contract') }}" class="contract-tab">
        Closed Contracts
    </a>
</div>

    <section class="contract-toolbar-card">
        <form method="GET" action="{{ url('contracts.index') }}" class="contract-toolbar">

            <select name="account_manager">
                <option value="">All Account Managers</option>
                <option value="am1">Account Manager 1</option>
                <option value="am2">Account Manager 2</option>
            </select>

            <select name="status">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expiring">Expiring Soon</option>
                <option value="followup">Follow-up Pending</option>
            </select>

            <select name="package">
                <option value="">All Packages</option>
                <option value="Enterprise">Enterprise</option>
                <option value="Premium">Premium</option>
                <option value="Basic">Basic</option>
            </select>

            <div class="contract-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client, AM, or contract ID..."
                >

                <button type="submit">
                    ⌕
                </button>
            </div>

            <a href="{{ url('/add-contract') }}" class="contract-add-btn">
                <span>＋</span>
                Add Contract
            </a>

        </form>
    </section>

    <section class="contract-table-card">

        <div class="contract-table-wrapper">

            <table class="contract-table">

                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>AM</th>
                        <th>ID Contract</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Contract State</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($rows as $row)

                        <tr class="contract-row {{ $row['status'] }}">
                            <td>{{ $row['client'] }}</td>
                            <td>{{ $row['am'] }}</td>
                            <td>{{ $row['id'] }}</td>
                            <td>{{ $row['package'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($row['start'])->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($row['end'])->format('d/m/Y') }}</td>
                            <td>
                                <span class="contract-status {{ $row['status'] }}">
                                    {{ $row['label'] }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="contract-empty">
                                No contracts found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection