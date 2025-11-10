<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tentang — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
    <style>
        .about-hero { padding: 4rem 0; text-align: center; }
        .about-card { max-width: 760px; margin: 1rem auto; padding: 1.2rem; background:#fff; border:1px solid #eee; border-radius:6px; }
    </style>
</head>

<body>
    <header class="topnav">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}">Mayra D'Light</a>
            <nav class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('about') }}">Tentang</a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif

                    <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="cart-link">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="cart-badge">{{ array_sum(array_column(session('cart'), 'quantity')) }}</span>
                    @endif
                </a>
            </nav>
        </div>
    </header>

    <main class="container">
        <header class="about-hero">
            <h1>Mayra D'Light</h1>
            <p class="lead">Toko roti kecil dengan rasa rumahan — resep keluarga dan bahan berkualitas.</p>
        </header>

        <section class="about-card">
            <h2>Tentang Kami</h2>
            <p>Mayra D'Light didirikan untuk menyajikan roti hangat dan lezat kepada tetangga dan komunitas. Kami memperhatikan kualitas bahan, kebersihan, serta teknik pemanggangan tradisional yang diwariskan.</p>

            <h3>Kontak</h3>
            <p>Untuk pertanyaan atau pesanan, kunjungi halaman utama kami atau hubungi admin melalui panel Admin.</p>

            <p><a href="{{ route('home') }}">Kembali ke beranda</a></p>
        </section>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
