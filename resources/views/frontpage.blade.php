<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mayra D'Light — Toko Roti</title>
    <link rel="stylesheet" href="{{ asset('css/simple.css') }}">
</head>

<body>
    <header class="hero">
        <div class="container">
            <h1>Mayra D'Light</h1>
            <p class="lead">Roti hangat, resep keluarga.</p>
            <p><a class="cta" href="#catalog">Lihat Katalog</a></p>
        </div>
    </header>

    <main class="container">
        <section id="catalog">
            <h2>Katalog Roti</h2>

            {{-- search form --}}
            <form method="get" action="{{ route('home') }}" class="search-form">
                <input type="search" name="q" placeholder="Cari roti..." value="{{ request('q') }}" class="search-input">
                <button type="submit" class="search-btn">Cari</button>
                @if(request('q'))
                <div class="search-note">Hasil pencarian untuk: "{{ request('q') }}"</div>
                @endif
            </form>

            @if($breads->isEmpty())
            <p>Tidak ada produk tersedia saat ini.</p>
            @else
            <div class="grid">
                @foreach($breads as $bread)
                <article class="card">
                    <a class="card-link" href="{{ route('breads.show', $bread) }}">
                        @if($bread->image)
                        <img src="{{ asset('storage/' . $bread->image) }}" class="thumb">
                        @else
                        <img src="{{ $bread->name }}" class="thumb">
                        @endif
                        <div class="card-body">
                            <h3 class="title">{{ $bread->name }}</h3>
                            <p class="excerpt">{{ Str::limit($bread->description, 120) }}</p>
                            <div class="price">Rp {{ number_format($bread->price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            @endif
        </section>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>