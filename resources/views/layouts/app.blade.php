<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VasTrack')</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/transfer-request.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/billing.css') }}?v={{ time() }}">
@yield('styles')
</head>
<body>

@yield('scripts')
<div class="vt-app">
    @include('components.sidebar')

    <div class="vt-page">
        @include('components.navbar')

        <main class="vt-main">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>