<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $bread->name }} — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple.css') }}">
</head>
<body>
    <header class="topnav">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}">Mayra D'Light</a>
            <nav class="nav-links">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('about') }}">Tentang</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <a class="back" href="{{ route('home') }}">← Kembali ke Katalog</a>

        <div class="hero bread-hero">
                    <div class="bread-media">
                        @if($bread->image)
                            <img class="image" src="{{ asset('storage/' . $bread->image) }}" alt="{{ $bread->name }}">
                        @else
                            <img class="image" src="{{ asset('images/placeholder.png') }}" alt="{{ $bread->name }}">
                        @endif
                    </div>

                    <div class="bread-details">
                        <h1>{{ $bread->name }}</h1>
                        <div class="muted">{{ $bread->description }}</div>
                        <div class="price">Rp {{ number_format($bread->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
