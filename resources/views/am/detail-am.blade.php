@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/detailam.css') }}">

@endsection


@section('content')

<div class="am-page">

    <div class="page-title">

        <a
            href="{{ route('contract.list') }}"
            class="back-arrow"
            style="text-decoration:none;">
            &#8249;
        </a>

        <h1>
            Account Manager
        </h1>

    </div>

    {{-- AM CARD --}}
    <div class="am-card">

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
                        Account Manager · {{ $user->email }}
                    </p>

                </div>

            </div>

            <div>

                <select
                    onchange="
                    if(this.value){
                        window.location=this.value;
                    }"
                    class="am-dropdown">

                    @foreach($accountManagers as $am)

                        <option
                            value="{{ route('account-managers.show', $am->id) }}"
                            @selected($am->id == $user->id)>

                            {{ $am->name }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="summary-cards">

            <div class="summary-card active-card">

                <h3>
                    Active
                </h3>

                <span>
                    {{ $activeCount }}
                </span>

            </div>

            <div class="summary-card critical-card">

                <h3>
                    Critical
                </h3>

                <span>
                    {{ $criticalCount }}
                </span>

            </div>

            <div class="summary-card expiring-card">

                <h3>
                    Expiring Soon
                </h3>

                <span>
                    {{ $expiringCount }}
                </span>

            </div>

        </div>

    </div>

    {{-- FILTER --}}
    <form method="GET">

        <div class="filter-bar">

            <select name="status">

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

                <option
                    value="terminated"
                    @selected(request('status') == 'terminated')>

                    Terminated

                </option>

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

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search ...">

            <a href="{{ route('account-managers.export', $user->id) }}">
                <button type="button" class="download-btn">
                    Download Report
                </button>
            </a>

        </div>

    </form>

    {{-- TABLE --}}
    <div class="contract-table">

        <table>

            <thead>

                <tr>

                    <th>Client Name</th>
                    <th>ID Contract</th>
                    <th>Package</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Price / Month</th>
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

                        @if($contract->status === 'active')

                            <span class="green-text">

                                Active

                            </span>

                        @elseif($contract->status === 'expiring')

                            <span class="yellow-text">

                                Expiring Soon

                            </span>

                        @elseif($contract->status === 'followup')

                            <span class="red-text">

                                Follow-up Pending

                            </span>

                        @elseif($contract->status === 'expired')

                            <span>

                                Expired

                            </span>

                        @elseif($contract->status === 'terminated')

                            <span>

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

    <div style="margin-top:20px">

        {{ $contracts->links() }}

    </div>

</div>

@endsection