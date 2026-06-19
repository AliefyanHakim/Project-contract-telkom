@extends('layouts.app')

@section('styles')

<link rel="stylesheet"
href="{{ asset('css/contract-list.css') }}">

@endsection

@section('content')

<div class="contract-page">

    <div class="page-title">

        <span class="back-arrow">
            &#8249;
        </span>

        <h1>
            Contract List
        </h1>

    </div>

    <div class="tabs">

        <a href="{{ route('contract.list') }}"
           class="tab active">
            Current Contracts
        </a>

        <a href="/closed-contract"
           class="tab">
            Closed Contracts
        </a>

    </div>

    <form method="GET">

        <div class="filters">

            @if(!auth()->user()->isAccountManager())

                <select name="account_manager">

                    <option value="">
                        All Account Managers
                    </option>

                    @foreach($accountManagers as $am)

                        <option
                            value="{{ $am->id }}"
                            @selected(
                                request('account_manager') == $am->id
                            )
                        >
                            {{ $am->name }}
                        </option>

                    @endforeach

                </select>

            @endif

            <select name="status">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="active"
                    @selected(
                        request('status') == 'active'
                    )
                >
                    Active
                </option>

                <option
                    value="expiring"
                    @selected(
                        request('status') == 'expiring'
                    )
                >
                    Expiring Soon
                </option>

                <option
                    value="followup"
                    @selected(
                        request('status') == 'followup'
                    )
                >
                    Follow Up
                </option>

            </select>

            <select>

                <option>
                    All Packages
                </option>

            </select>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search ..."
            >

            <button type="submit">
                Search
            </button>

        </div>

    </form>

    <div class="table-container">

        <div class="table-wrapper">

            <table>

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

                    <tr>

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
                            -
                        </td>

                        <td>
                            {{ $contract->start_date?->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $contract->end_date?->format('d/m/Y') }}
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

                            @else

                                {{ ucfirst($contract->status) }}

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            style="text-align:center;padding:20px">

                            No contracts found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div style="margin-top:20px">

        {{ $contracts->links() }}

    </div>

</div>

@endsection