<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - OneShop</title>
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

        .register-container {
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

        .btn-login {
            background: #c7783a;
            color: white;
        }

        .btn-login:hover {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6b5744;
            font-size: 14px;
        }

        .login-link a {
            color: #c7783a;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            font-size: 11px;
            color: rgba(107, 87, 68, 0.6);
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .register-container {
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
    <div class="logo-circle"></div>
    
    <div class="register-container">
        <div class="logo">
            <div class="logo-icon">Mayra D'Light</div>
            <div class="tagline">Roti hangat, resep keluarga.</div>
        </div>

        <h2 class="welcome-text">Create Account</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" id="registerForm">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="John Doe" 
                    value="{{ old('name') }}"
                    required
                >
                <div class="validation-message success" id="nameValidation" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="john.doe @ gmail.com" 
                    value="{{ old('email') }}"
                    required
                >
                <div class="validation-message success" id="emailValidation" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••••" 
                    required
                >
                <span class="password-toggle" onclick="togglePassword('password')" id="togglePassword">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </span>
                <div class="password-requirements">Must be at least 8 characters long</div>
                <div class="validation-message" id="passwordValidation" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="••••••••••" 
                    required
                >
                <span class="password-toggle" onclick="togglePassword('password_confirmation')" id="toggleConfirm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </span>
                <div class="validation-message" id="confirmValidation" style="display: none;"></div>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>

        <div class="footer-links">
            <a href="#">Term of use</a>
            <a href="#">Privacy policy</a>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn-action btn-login" onclick="window.location.href='{{ route('login') }}'">Sign In</button>
        <button class="btn-action btn-help">Need Help?</button>
    </div>

    <script>
        // Name validation
        document.getElementById('name').addEventListener('input', function(e) {
            const nameValidation = document.getElementById('nameValidation');
            
            if (e.target.value.length >= 3) {
                nameValidation.style.display = 'block';
                nameValidation.classList.remove('error');
                nameValidation.classList.add('success');
                nameValidation.textContent = 'Looks good!';
            } else if (e.target.value.length > 0) {
                nameValidation.style.display = 'block';
                nameValidation.classList.remove('success');
                nameValidation.classList.add('error');
                nameValidation.textContent = 'Name must be at least 3 characters';
            } else {
                nameValidation.style.display = 'none';
            }
        });

        // Email validation
        document.getElementById('email').addEventListener('input', function(e) {
            const emailValidation = document.getElementById('emailValidation');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (emailRegex.test(e.target.value)) {
                emailValidation.style.display = 'block';
                emailValidation.classList.remove('error');
                emailValidation.classList.add('success');
                emailValidation.textContent = 'Perfect!';
            } else if (e.target.value.length > 0) {
                emailValidation.style.display = 'block';
                emailValidation.classList.remove('success');
                emailValidation.classList.add('error');
                emailValidation.textContent = 'Invalid email format';
            } else {
                emailValidation.style.display = 'none';
            }
        });

        // Password validation
        document.getElementById('password').addEventListener('input', function(e) {
            const passwordValidation = document.getElementById('passwordValidation');
            const confirmInput = document.getElementById('password_confirmation');
            
            if (e.target.value.length >= 8) {
                passwordValidation.style.display = 'block';
                passwordValidation.classList.remove('error');
                passwordValidation.classList.add('success');
                passwordValidation.textContent = 'Your password is strong';
            } else if (e.target.value.length > 0) {
                passwordValidation.style.display = 'block';
                passwordValidation.classList.remove('success');
                passwordValidation.classList.add('error');
                passwordValidation.textContent = 'Password must be at least 8 characters';
            } else {
                passwordValidation.style.display = 'none';
            }

            // Check confirmation match
            if (confirmInput.value) {
                checkPasswordMatch();
            }
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const confirmValidation = document.getElementById('confirmValidation');
            
            if (confirm.length === 0) {
                confirmValidation.style.display = 'none';
                return;
            }

            if (password === confirm) {
                confirmValidation.style.display = 'block';
                confirmValidation.classList.remove('error');
                confirmValidation.classList.add('success');
                confirmValidation.textContent = 'Passwords match!';
            } else {
                confirmValidation.style.display = 'block';
                confirmValidation.classList.remove('success');
                confirmValidation.classList.add('error');
                confirmValidation.textContent = 'Passwords do not match';
            }
        }

        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1).replace('_', ''));
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change icon based on password visibility
            if (type === 'text') {
                // Eye slash icon (hidden)
                toggleIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                    </svg>
                `;
            } else {
                // Eye icon (visible)
                toggleIcon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                `;
            }
        }

        // Form validation before submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });
    </script>
</body>
</html>
