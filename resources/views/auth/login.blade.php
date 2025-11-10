<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OneShop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 60px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .logo-circle {
            position: absolute;
            top: -100px;
            left: 40px;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #c7783a 0%, #8b5a2b 100%);
            border-radius: 50% 50% 50% 0%;
            opacity: 0.3;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo-icon {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 32px;
            font-weight: 700;
            color: #c7783a;
            margin-bottom: 5px;
        }

        .logo-icon::before {
            content: '';
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #c7783a 0%, #8b5a2b 100%);
            border-radius: 50% 50% 50% 0%;
            display: inline-block;
        }

        .tagline {
            color: #8b7355;
            font-size: 14px;
            margin-bottom: 40px;
        }

        .welcome-text {
            color: #c7783a;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            color: #6b5744;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        input {
            width: 100%;
            padding: 15px;
            border: none;
            border-bottom: 2px solid rgba(199, 120, 58, 0.3);
            background: transparent;
            color: #3d2f1f;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        input::placeholder {
            color: rgba(107, 87, 68, 0.5);
        }

        input:focus {
            border-bottom-color: #c7783a;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #3d2f1f;
            -webkit-box-shadow: 0 0 0px 1000px transparent inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .validation-message {
            font-size: 12px;
            margin-top: 5px;
        }

        .validation-message.success {
            color: #4a9b3a;
        }

        .validation-message.error {
            color: #d9534f;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 40px;
            cursor: pointer;
            color: #c7783a;
            user-select: none;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #c7783a 0%, #a65a2f 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(199, 120, 58, 0.4);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #6b5744;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            color: #c7783a;
        }

        .footer-links {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(199, 120, 58, 0.2);
        }

        .footer-links a {
            color: rgba(107, 87, 68, 0.7);
            text-decoration: none;
            font-size: 12px;
            margin: 0 15px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #c7783a;
        }

        .action-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 25px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-request {
            background: #c7783a;
            color: white;
        }

        .btn-request:hover {
            background: #a65a2f;
            transform: translateY(-2px);
        }

        .btn-help {
            background: white;
            color: #c7783a;
        }

        .btn-help:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(74, 155, 58, 0.15);
            color: #4a9b3a;
            border: 1px solid rgba(74, 155, 58, 0.3);
        }

        .alert-error {
            background: rgba(217, 83, 79, 0.15);
            color: #d9534f;
            border: 1px solid rgba(217, 83, 79, 0.3);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #6b5744;
            font-size: 14px;
        }

        .register-link a {
            color: #c7783a;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .login-container {
                padding: 40px 30px;
            }

            .action-buttons {
                bottom: 15px;
                right: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <h1>Masuk — Mayra D'Light</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>

            <label for="password">Kata Sandi</label>
            <div style="display:flex;gap:.5rem;align-items:center">
                <input type="password" id="password" name="password" required>
                <button type="button" data-toggle="password" data-target="password" class="btn" style="background:#eee;color:#333;border-radius:6px;padding:.5rem">Tampilkan</button>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.5rem">
                <label style="font-size:.9rem"><input type="checkbox" name="remember"> Ingat saya</label>
                <a href="#" style="font-size:.9rem;color:#6b5744;text-decoration:none">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="btn" style="margin-top:.6rem">Masuk</button>
        </form>

        <p class="auth-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </div>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
