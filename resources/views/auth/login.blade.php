<form method="POST" action="{{ route('login.store') }}">
    @csrf

    <div>
        <label>Email / Username</label>

        <input
            type="text"
            name="login"
            value="{{ old('login') }}"
            required
        >
    </div>

    <div>
        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >
    </div>

    <button type="submit">
        Login
    </button>
</form>