<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mayra D'Light — Toko Roti</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
    @livewireStyles
</head>

<body>
    <header class="topnav">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}">Mayra D'Light</a>
            <nav class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="#catalog">Katalog</a>
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
        <header class="hero">
            <div class="container">
                <h1>Mayra D'Light</h1>
                <p class="lead">Roti hangat, resep keluarga.</p>
                <p><a class="cta" href="#catalog">Pilih Kategori</a></p>
            </div>
        </header>

        <section id="catalog">
            <h2>Katalog Roti</h2>

            {{-- Livewire Search Component --}}
            @livewire('livesearch')

        </section>
        </main>
    </div>

    @livewireScripts
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>