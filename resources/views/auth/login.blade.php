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

    <div class="logo">
        <img src="{{ asset('images/telkom-logo.png') }}" alt="">
    </div>

    <div class="login-card">

        <input
                type="text"
                class="input-box"
                placeholder="Manager">

        <input
                type="password"
                class="input-box"
                placeholder="Password">

        <button class="login-btn">
            LOGIN
        </button>

    </div>

    <div class="footer">
        Contract Management System<br>
        PT Telkom Indonesia
    </div>

</div>

</body>
</html>