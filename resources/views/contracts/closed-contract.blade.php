@extends('layouts.app')

@section('title', 'Closed Contracts | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contract-list.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $rows = collect([
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-11', 'end' => '2026-05-29'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-18', 'end' => '2026-06-15'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-04', 'end' => '2026-05-31'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-16', 'end' => '2026-05-30'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-18', 'end' => '2026-06-18'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-17', 'end' => '2026-06-20'],
        ['client' => 'PT Maju Bersama', 'am' => 'XXXXXXX', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-13', 'end' => '2026-05-29'],
        ['client' => 'PT Maju Bersama', 'am' => 'BBBBBbbb', 'id' => '1234567890', 'package' => 'Enterprise', 'start' => '2026-04-12', 'end' => '2026-06-22'],
    ]);
@endphp

<div class="contract-page">

    <div class="contract-header">
        <a href="{{ url('/dashboard') }}" class="contract-back-btn">‹</a>

        <div>
            <h1>Contract List</h1>
            <p>Review contracts that have been closed or expired.</p>
        </div>
    </div>

    <div class="contract-tabs">
    <a href="{{ url('/contract-list') }}" class="contract-tab">
        Current Contracts
    </a>

    <a href="{{ url('/closed-contract') }}" class="contract-tab active">
        Closed Contracts
    </a>
</div>

    <section class="contract-toolbar-card">
    <form method="GET"
        action="{{ route('contract.closed') }}"
        class="contract-toolbar">

            <select name="account_manager">

                <option value="">
                    All Account Managers
                </option>

                @foreach($accountManagers as $am)

                    <option
                        value="{{ $am->id }}"
                        @selected(
                            request('account_manager') == $am->id
                        )>

                        {{ $am->name }}

                    </option>

                @endforeach

            </select>

            <select name="status">
                <option value="">All Statuses</option>
                <option value="expired">Expired</option>
                <option value="terminated">Terminated</option>
            </select>

            <select name="service">

                <option value="">
                    All Packages
                </option>

                @foreach($services as $service)

                    <option
                        value="{{ $service->id }}"
                        @selected(
                            request('service') == $service->id
                        )>

                        {{ $service->service_name }}

                    </option>

                @endforeach

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

                @forelse($contracts as $contract)

                <tr
                    onclick="window.location='{{ route('contracts.show', $contract->id) }}'"
                    style="cursor:pointer;">

                    <td>
                        {{ $contract->contract_name }}
                    </td>

                    <td>
                        {{ $contract->owner?->name }}
                    </td>

                    <td>
                        {{ $contract->contract_number }}
                    </td>

                    <td>

                        @foreach($contract->services as $contractService)

                            {{ $contractService->service?->service_name }}

                            @if(!$loop->last)
                                ,
                            @endif

                        @endforeach

                    </td>

                    <td>
                        {{ $contract->start_date?->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ $contract->end_date?->format('d/m/Y') }}
                    </td>

                    <td>

                        <span class="contract-status expired">

                            Expired

                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="contract-empty">

                        No closed contracts found.

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection