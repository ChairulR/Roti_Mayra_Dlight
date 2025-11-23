<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">
    <div class="decor-top-left" aria-hidden="true"></div>
    <div class="auth-card">
        <div class="logo">
            <div class="logo-header">
                <div class="logo-icon"></div>
                <div class="brand-name">Mayra D'Light</div>
            </div>
            <div class="tagline">Roti hangat, resep keluarga</div>
        </div>

        <h1 class="welcome">Welcome Back!</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="auth-input-row">
                    <input type="password" id="password" name="password" placeholder="••••••••••" required>
                    <button type="button" data-toggle="password" data-target="password" class="toggle-btn" aria-label="Toggle password">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>

            <div class="remember-row">
                <a href="#" class="forgot-link">Forget My Password</a>
            </div>
        </form>

        <p class="auth-footer">Don't have an account? <a href="{{ route('register') }}" class="register-link">Register here</a></p>
        <div class="footer-links">
            <a href="#">Term of use</a>
            <a href="#">Privacy policy</a>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn-action btn-login">Request An Account</button>
        <button class="btn-action btn-help">Need Help?</button>
    </div>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
