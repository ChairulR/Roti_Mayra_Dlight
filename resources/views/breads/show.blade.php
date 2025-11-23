<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $bread->name }} — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
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
        <a class="btn-back" href="{{ route('home') }}">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Katalog
        </a>

        <div class="product-detail-wrapper">
            <div class="product-image-section">
                @if($bread->image)
                    <img class="product-main-image" src="{{ asset('storage/' . $bread->image) }}" alt="{{ $bread->name }}">
                @else
                    <img class="product-main-image" src="{{ asset('images/placeholder.png') }}" alt="{{ $bread->name }}">
                @endif
            </div>

            <div class="product-info-section">
                <h1 class="product-title">{{ $bread->name }}</h1>
                
                {{-- Price Section --}}
                <div class="product-price-section">
                    <div class="price-row">
                        <span class="price-current">Rp {{ number_format($bread->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="product-description">
                    <h3>Deskripsi Produk</h3>
                    <p>{{ $bread->description }}</p>
                </div>

                {{-- Category --}}
                @if($bread->category)
                <div class="product-meta">
                    <span class="meta-label">Kategori:</span>
                    <span class="meta-value">{{ $bread->category->name }}</span>
                </div>
                @endif

                {{-- Quantity Selector --}}
                <form action="{{ route('cart.add', $bread) }}" method="POST">
                    @csrf
                    <div class="quantity-section">
                        <span class="quantity-label">Kuantitas</span>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" onclick="decreaseQty()">−</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="action-buttons">
                        <button type="submit" class="btn-cart">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Masukkan Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function increaseQty() {
            let qty = document.getElementById('quantity');
            qty.value = parseInt(qty.value) + 1;
        }
        
        function decreaseQty() {
            let qty = document.getElementById('quantity');
            if(parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        }
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
