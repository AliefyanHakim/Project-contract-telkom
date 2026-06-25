<aside class="vt-sidebar">
    <div class="vt-brand">
        <div class="vt-logo-image">
            <img src="{{ asset('images/logo-vastrack.png') }}" alt="Logo VasTrack">
        </div>

        <div>
            <h1>Vas<span>Track</span></h1>
            <p>Track • Manage • Secure</p>
        </div>
    </div>

    <nav class="vt-menu">
        <p class="vt-menu-title">Menu</p>

        <a href="{{ url('/dashboard') }}"
           class="vt-menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="vt-menu-icon">D</span>
            <span>Dashboard</span>
            <b>›</b>
        </a>

        <a href="{{ url('/contract-list') }}"
           class="vt-menu-item {{ request()->is('contract-list') || request()->is('closed-contract') || request()->is('contracts*') ? 'active' : '' }}">
            <span class="vt-menu-icon">▤</span>
            <span>Contract List</span>
            <b>›</b>
        </a>

        <a href="{{ url('/billing') }}"
           class="vt-menu-item {{ request()->is('billing*') ? 'active' : '' }}">
            <span class="vt-menu-icon">▥</span>
            <span>Billing & Invoices</span>
            <b>›</b>
        </a>

        @if(auth()->user()->isManager() || auth()->user()->isSupportInputter())
            <a href="{{ url('/detailam') }}"
               class="vt-menu-item {{ request()->is('detailam') || request()->is('account-manager*') ? 'active' : '' }}">
                <span class="vt-menu-icon">AM</span>
                <span>By Account Manager</span>
                <b>›</b>
            </a>
        @endif

        @if(auth()->user()->isAccountManager() || auth()->user()->isSupportInputter())
            <a href="{{ url('/contract-alerts') }}"
               class="vt-menu-item {{ request()->is('contract-alerts') ? 'active' : '' }}">
                <span class="vt-menu-icon">!</span>
                <span>Contract Alerts</span>
                <b>›</b>
            </a>
        @endif

        @if(auth()->user()->isManager() || auth()->user()->isAccountManager() || auth()->user()->isSupportInputter())
            <a href="{{ url('/transfer-request') }}"
               class="vt-menu-item {{ request()->is('transfer-request') || request()->is('acceptreject-transfer') || request()->is('accepted-transfer') || request()->is('rejected-transfer') || request()->is('direct-transfer') || request()->is('transfer-contract') ? 'active' : '' }}">
                <span class="vt-menu-icon">⇄</span>
                <span>Transfer Request</span>
                <b>›</b>
            </a>
        @endif

        <p class="vt-menu-title">Settings</p>

        @if(auth()->user()->isAccountManager() || auth()->user()->isSupportInputter())
            <a href="{{ url('/email-notifications') }}"
               class="vt-menu-item {{ request()->is('email-notifications') ? 'active' : '' }}">
                <span class="vt-menu-icon">✉</span>
                <span>Email Notifications</span>
                <b>›</b>
            </a>
        @endif

        @if(auth()->user()->isManager())
            <a href="{{ url('/activity-logs') }}"
               class="vt-menu-item {{ request()->is('activity-logs') ? 'active' : '' }}">
                <span class="vt-menu-icon">LOG</span>
                <span>Activity Logs</span>
                <b>›</b>
            </a>
        @endif

        <a href="{{ url('/profile') }}"
           class="vt-menu-item {{ request()->is('profile') || request()->is('settings/profile') ? 'active' : '' }}">
            <span class="vt-menu-icon">⚙</span>
            <span>Profile</span>
            <b>›</b>
        </a>
    </nav>

    @php
        $user = auth()->user();

        $roleName = match($user->role_id) {
            \App\Models\User::ROLE_MANAGER => 'Manager',
            \App\Models\User::ROLE_ACCOUNT_MANAGER => 'Account Manager',
            \App\Models\User::ROLE_SUPPORT_INPUTTER => 'Support Inputter',
            \App\Models\User::ROLE_SUPPORT_PAYCALL => 'Support Paycall',
            default => 'User',
        };
    @endphp

    <div class="vt-user-card">
        <div class="vt-avatar">
            @if($user->profile_photo)
                <img
                    src="{{ asset('storage/' . $user->profile_photo) }}"
                    alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>

        <div>
            <h4>{{ $user->name }}</h4>
            <p>{{ $roleName }}</p>
        </div>
    </div>
</aside>