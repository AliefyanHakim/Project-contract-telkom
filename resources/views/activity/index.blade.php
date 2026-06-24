@extends('layouts.app')

@section('title', 'Activity Logs | VasTrack')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/activity-log.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="activity-page">

    <div class="activity-header">
        <a href="{{ url('/dashboard') }}" class="activity-back-btn">
            ‹
        </a>

        <div>
            <h1>Activity Logs</h1>
            <p>Monitor user activities and system changes across VasTrack.</p>
        </div>
    </div>

    <section class="activity-toolbar-card">
        <form method="GET" action="{{ url('/activity-logs') }}" class="activity-toolbar">

            <select name="module" onchange="this.form.submit()">
                <option value="">
                    All Modules
                </option>

                @foreach($modules as $module)
                    <option value="{{ $module }}" @selected(request('module') === $module)>
                        {{ ucwords(str_replace('_', ' ', strtolower($module))) }}
                    </option>
                @endforeach
            </select>

            <div class="activity-search-box">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search user, module, or activity..."
                >

                <button type="submit">
                    ⌕
                </button>
            </div>

        </form>
    </section>

    <section class="activity-table-card">

        <div class="activity-table-wrapper">

            <table class="activity-table">

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Module</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($logs as $log)

                        <tr>
                            <td>
                                {{ $log->user?->name ?? 'System' }}
                            </td>

                            <td>
                                {{ $log->user?->email ?? '-' }}
                            </td>

                            <td>
                                <span class="activity-module-badge">
                                    {{ ucwords(str_replace('_', ' ', strtolower($log->module ?? '-'))) }}
                                </span>
                            </td>

                            <td>
                                {{ $log->activity ?? '-' }}
                            </td>

                            <td>
                                {{ $log->created_at ? $log->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                {{ $log->created_at ? $log->created_at->format('H:i') : '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="activity-empty">
                                No activity logs found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($logs->hasPages())
    <div class="activity-pagination">

        @if($logs->onFirstPage())
            <span class="activity-page-btn disabled">
                ‹ Previous
            </span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="activity-page-btn">
                ‹ Previous
            </a>
        @endif

        <span class="activity-page-info">
            Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
        </span>

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="activity-page-btn">
                Next ›
            </a>
        @else
            <span class="activity-page-btn disabled">
                Next ›
            </span>
        @endif

    </div>
@endif

    </section>

</div>

@endsection