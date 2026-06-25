@extends('layouts.app')

@section('title', 'Detail AM | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/detailam.css') }}?v={{ time() }}">
@endsection

@section('content')

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
                    <h2>{{ $user->name }}</h2>
                    <p>
                        Account Manager
                        <span>•</span>
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <select
                class="am-dropdown"
                onchange="if(this.value){window.location=this.value;}">

                @foreach($accountManagers as $am)

                    <option
                        value="{{ route('account-managers.show', $am->id) }}"
                        @selected($am->id == $user->id)>

                        {{ $am->name }}

                    </option>

                @endforeach

            </select>

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

    <form
        method="GET"
        action="{{ route('account-managers.show', $user->id) }}"
        class="am-filter-bar">

            <select 
            name="status"
            onchange="this.form.submit()">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="active"
                    @selected(request('status') == 'active')>

                    Active

                </option>

                <option
                    value="expiring"
                    @selected(request('status') == 'expiring')>

                    Expiring Soon

                </option>

                <option
                    value="followup"
                    @selected(request('status') == 'followup')>

                    Follow-up Pending

                </option>

                <option
                    value="expired"
                    @selected(request('status') == 'expired')>

                    Expired

                </option>

            </select>

            <select 
            name="service"
            onchange="this.form.submit()">

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

            <div class="am-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search client, contract ID, or package..."
                >

                <button type="submit">⌕</button>
            </div>

            <a
                href="{{ route('account-managers.export', $user->id) }}"
                class="am-download-btn">
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

                @forelse($contracts as $contract)

                <tr
                    onclick="
                        window.location='{{ route('contracts.show', $contract->id) }}'
                    "
                    style="cursor:pointer;">

                    <td>

                        {{ $contract->contract_name }}

                    </td>

                    <td>

                        {{ $contract->contract_number }}

                    </td>

                    <td>

                        @foreach($contract->services as $contractService)

                            {{ $contractService->service->service_name }}

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

                        Rp {{ number_format(

                            $contract->services->sum(
                                fn($item)
                                => $item->service->monthly_fee
                            ),

                            0,
                            ',',
                            '.'

                        ) }}

                    </td>

                    <td>
                        @if($contract->calculated_status === 'active')

                            <span class="am-status active">

                                Active

                            </span>

                        @elseif($contract->calculated_status === 'expiring')

                            <span class="am-status expiring">

                                Expiring Soon

                            </span>

                        @elseif($contract->calculated_status === 'followup')

                            <span class="am-status followup">

                                Follow-up Pending

                            </span>

                        @elseif($contract->calculated_status === 'expired')

                            <span class="am-status expired">

                                Expired

                            </span>

                        @elseif($contract->calculated_status === 'terminated')

                            <span class="am-status terminated">

                                Terminated

                            </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="7"
                        style="
                            text-align:center;
                            padding:20px;
                        ">

                        No contracts found

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>

    <div style="margin-top:20px">

    {{ $contracts->links() }}

    </div>

</div>

@endsection