@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer-contract.css') }}?v={{ time() }}">
@endsection

@section('content')

@php
    $user = auth()->user();

    $canTransfer = $user->isManager() || $user->isAccountManager();

    $buttonLabel = $user->isManager()
        ? 'Make Direct Transfer'
        : 'Make Transfer Request';
@endphp

<div class="transfer-page">

    <div class="page-title">

        <a href="{{ url('/transfer-request') }}" class="back-arrow">
            &#8249;
        </a>

        <h1>
            Contract List
        </h1>

    </div>

    @if(session('success'))
        <div class="transfer-alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="transfer-alert error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="transfer-alert error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="transfer-toolbar">

        <form method="GET" action="{{ url('/transfer-contract') }}" class="search-box">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search ..."
            >

            <button type="submit" class="search-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

    </div>

    <form method="POST" action="{{ url('/transfer-contract') }}">
        @csrf

        <div class="table-container">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>Client Name</th>
                            <th>ID Contract</th>
                            <th>AM</th>
                            <th>Package</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Transfer</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse(($contracts ?? collect()) as $contract)

                            @php
                                $packageName = data_get($contract, 'services.0.service.service_name', 'Enterprise');
                            @endphp

                            <tr>
                                <td>{{ $contract->contract_name }}</td>

                                <td>{{ $contract->contract_number }}</td>

                                <td>{{ data_get($contract, 'owner.name', '-') }}</td>

                                <td>{{ $packageName }}</td>

                                <td>
                                    {{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '-' }}
                                </td>

                                <td>
                                    @if($canTransfer)
                                        <input
                                            type="radio"
                                            name="contract_id"
                                            value="{{ $contract->id }}"
                                            required
                                        >
                                    @else
                                        <span class="readonly-badge">
                                            View Only
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" style="text-align:center;">
                                    No contract data available.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($canTransfer)

            <div class="recipient-card">

                <label>Recipient</label>

                <select name="target_am_id" required>
                    <option value="">Select Account Manager</option>

                    @foreach(($accountManagers ?? collect()) as $am)
                        <option value="{{ $am->id }}">
                            {{ $am->name }} - {{ $am->email }}
                        </option>
                    @endforeach
                </select>

                <textarea
                    name="reason"
                    placeholder="Enter the reason why you want to transfer this contract..."
                >{{ old('reason') }}</textarea>

                <button type="submit" class="transfer-btn">
                    {{ $buttonLabel }}
                </button>

            </div>

        @else

            <div class="recipient-card">
                <label>View Only</label>

                <p class="view-only-text">
                    You can view contract transfer data, but you cannot make a transfer request.
                </p>
            </div>

        @endif

    </form>

</div>

@endsection