<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profil Saya — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
    <style>
        .profile-card { max-width:640px; margin:2.5rem auto; padding:1.2rem; background:#fff; border-radius:6px; border:1px solid #eee }
        .profile-row { display:flex; justify-content:space-between; padding:.6rem 0 }
    </style>
</head>
<body>
    <main class="container">
        <div class="profile-card">
            <h1>Halo, {{ $user->name }}</h1>

            <div class="profile-row">
                <strong>Email</strong>
                <span>{{ $user->email }}</span>
            </div>

            @if(method_exists($user, 'isAdmin') && $user->isAdmin())
                <div class="profile-row">
                    <strong>Sebagai</strong>
                    <span>Admin</span>
                </div>
            @endif

            <div style="margin-top:1rem">
                <a class="btn-primary" href="{{ route('home') }}">Lanjut Belanja</a>
                <a class="btn-logout" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">@csrf</form>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
