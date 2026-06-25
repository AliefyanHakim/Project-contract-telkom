@extends('layouts.app')

@section('title', 'Closed Contracts | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contract-list.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $user = auth()->user();

    $isAccountManager = $user->isAccountManager();

    $canAddContract = $user->isAccountManager()
    || $user->isSupportInputter();

    $toolbarClass = 'contract-toolbar';

    if ($isAccountManager) {
        $toolbarClass .= ' am-toolbar';
    }

    if (!$canAddContract) {
        $toolbarClass .= ' no-add';
    }
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
        <form method="GET" action="{{ url('/closed-contract') }}" class="{{ $toolbarClass }}">

            @unless($isAccountManager)
                <select 
                    name="account_manager"
                    onchange="this.form.submit()">

                    <option value="">
                        All Account Managers
                    </option>

                    @foreach($accountManagers as $am)
                        <option
                            value="{{ $am->id }}"
                            @selected(request('account_manager') == $am->id)>

                            {{ $am->name }}

                        </option>
                    @endforeach

                </select>
            @endunless

            <select 
                name="status"
                onchange="this.form.submit()">

                <option value="">
                    All Statuses
                </option>

                <option value="expired" @selected(request('status') === 'expired')>
                    Expired
                </option>

                <option value="terminated" @selected(request('status') === 'terminated')>
                    Terminated
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
                        @selected(request('service') == $service->id)>

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

            @if($canAddContract)
                <a href="{{ url('/add-contract') }}" class="contract-add-btn">
                    <span>＋</span>
                    Add Contract
                </a>
            @endif

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
                                {{ $contract->owner?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $contract->contract_number }}
                            </td>

                            <td>
                                @forelse($contract->services as $contractService)

                                    {{ $contractService->service?->service_name }}

                                    @if(!$loop->last)
                                        ,
                                    @endif

                                @empty
                                    -
                                @endforelse
                            </td>

                            <td>
                                {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '-' }}
                            </td>

                        <td>
                            @if($contract->status === 'terminated')
                                <span class="contract-status followup">
                                    Terminated
                                </span>
                            @else
                                <span class="contract-status expired">
                                    Expired
                                </span>
                            @endif
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