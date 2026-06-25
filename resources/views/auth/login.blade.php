<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Management System</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="wrapper">

    <div class="login-card">

        <img
            src="{{ asset('images/logo-vastrack2.png') }}"
            class="logo">

        <div class="brand">
            Vas<span>Track</span>
        </div>

        <div class="tagline">
            Track • Manage • Secure
        </div>

        <form
            action="{{ route('login.post') }}"
            method="POST"
            class="login-form">

            @csrf

            @if ($errors->any())
                <div class="login-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <input
                type="text"
                name="login"
                class="input-box"
                placeholder="Username / Email"
                value="{{ old('login') }}"
                required>

            <input
                type="password"
                name="password"
                class="input-box"
                placeholder="Password"
                required>

            <button
                type="submit"
                class="login-btn">
                LOGIN
            </button>

        </form>

    </div>

    <div class="footer">
        Contract Management System<br>
        PT Telkom Indonesia
    </div>

</div>

</body>
</html>