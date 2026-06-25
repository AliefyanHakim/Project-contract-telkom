@php
    use App\Models\Contract;

    $user = auth()->user();

    $initials = collect(explode(' ', $user->name))
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');


    $alertQuery = Contract::query()
        ->whereIn('status', [
            'expiring',
            'followup',
            'expired',
        ]);

    if ($user->isAccountManager()) {
        $alertQuery->where('owner_am_id', $user->id);
    }

    $navbarAlertCount = $alertQuery->count();

    $canOpenContractAlerts = $user->isAccountManager()
        || $user->isSupportInputter();

    $navbarAlertUrl = $canOpenContractAlerts
        ? url('/contract-alerts')
        : url('/contract-list');
@endphp

<header class="vt-navbar">
    <div>
        <p>Welcome back,</p>
        <h2>{{ $user->name }}</h2>
    </div>

    <div class="vt-navbar-actions">
        <a
            href="{{ $navbarAlertUrl }}"
            class="vt-notification-btn {{ $navbarAlertCount <= 0 ? 'is-empty' : '' }}"
            title="{{ $navbarAlertCount }} contract alerts">
            !
            
            @if($navbarAlertCount > 0)
                <small>{{ $navbarAlertCount > 99 ? '99+' : $navbarAlertCount }}</small>
            @endif
        </a>

        <button class="vt-profile-btn">
            @if($user->profile_photo)
                <div class="vt-avatar">
                    <img
                        src="{{ asset('storage/' . $user->profile_photo) }}"
                        alt="{{ $user->name }}">
                </div>
            @else
                <div class="vt-avatar">
                    {{ $initials }}
                </div>
            @endif
        </button>
    </div>
</header>