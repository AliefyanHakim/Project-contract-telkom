<div class="sidebar">

    <div class="menu-btn">
        ☰
    </div>

    <div class="menu-title">
        Menu
    </div>

    <ul>

        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <span>Dashboard</span>
                <span>›</span>
            </a>
        </li>

        <li class="{{
            request()->routeIs('contract.list')
            || request()->routeIs('contract.closed')
                ? 'active'
                : ''
        }}">
            <a href="{{ route('contract.list') }}">
                <span>Contract List</span>
                <span>›</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span>Billing & Invoices</span>
                <span>›</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span>By Account Manager</span>
                <span>›</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span>Contract Alerts</span>
            </a>
        </li>

        <li>
            <a href="#">
                <span>Transfer Request</span>
                <span>›</span>
            </a>
        </li>

    </ul>

    <div class="setting-title">
        Settings
    </div>

    <ul>

        <li class="{{ request()->routeIs('settings.email-notifications') ? 'active' : '' }}">
            <a href="{{ route('settings.email-notifications') }}">
                <span>Email Notifications</span>
                <span>›</span>
            </a>
        </li>

    </ul>

</div>