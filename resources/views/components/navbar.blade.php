@php
    $user = auth()->user();

    $initials = collect(explode(' ', $user->name))
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp


<header class="vt-navbar">
    <div>
        <p>Welcome back,</p>
        <h2>{{ $user->name }}</h2>
    </div>

    <div class="vt-navbar-actions">
        <button class="vt-notification-btn">
            !
            <small>3</small>
        </button>

        <button class="vt-profile-btn">{{ $initials }}</button>
    </div>
</header>

