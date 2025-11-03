<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $bread->name }} — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple.css') }}">
</head>
<body>
    <div class="container">
        <a class="back" href="{{ route('home') }}">← Kembali ke Katalog</a>

        <div class="hero">
            @if($bread->image)
                <img class="image" src="{{ asset($bread->image) }}" alt="{{ $bread->name }}">
            @else
                <img class="image" src="{{ $bread->name }}">
            @endif

            <h1 style="margin-top:12px">{{ $bread->name }}</h1>
            <div style="color:#6b7280">{{ $bread->description }}</div>
            <div class="price">Rp {{ number_format($bread->price, 0, ',', '.') }}</div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
