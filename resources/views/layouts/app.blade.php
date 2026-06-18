<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Management System</title>

    <link rel="stylesheet" href="{{ asset('css/navbarsidebar.css') }}">
    @yield('styles')>
</head>
<body>

@include('components.sidebar')

<div class="main">

    @include('components.navbar')

    @yield('content')

</div>

</body>
</html>