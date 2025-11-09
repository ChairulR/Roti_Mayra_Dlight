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
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Mayra D'Light</h1>
                    <p class="lead">Roti hangat, resep keluarga.</p>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    @auth
                        <span style="color: white;">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" style="color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 5px;">Admin Panel</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 5px;">Login</a>
                        <a href="{{ route('register') }}" style="color: white; text-decoration: none; background: rgba(255,255,255,0.3); padding: 0.5rem 1rem; border-radius: 5px;">Register</a>
                    @endauth
                </div>
            </div>
            <p><a class="cta" href="#Filter">Pilih Kategori</a></p>
        </div>
    </header>

    <main class="container">
        {{-- Notifications --}}
        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; margin-bottom: 1rem; border-radius: 5px; border-left: 4px solid #28a745;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 5px; border-left: 4px solid #dc3545;">
            {{ session('error') }}
        </div>
        @endif

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