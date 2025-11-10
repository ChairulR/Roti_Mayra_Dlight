<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Keranjang Belanja — Mayra D'Light</title>
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

                <a href="{{ route('cart.index') }}" class="active">Keranjang</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1 class="page-title">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(!empty($cart) && count($cart) > 0)
            <div class="cart-wrapper">
                <div class="cart-items">
                    @foreach($cart as $id => $item)
                    <div class="cart-item">
                        <div class="cart-item-image">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" alt="{{ $item['name'] }}">
                            @endif
                        </div>
                        <div class="cart-item-details">
                            <h3 class="cart-item-name">{{ $item['name'] }}</h3>
                            <p class="cart-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <div class="cart-item-quantity">
                            <form action="{{ route('cart.update') }}" method="POST" class="qty-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="button" class="qty-btn-sm" onclick="updateQty(this, -1)">−</button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" readonly class="qty-input-sm">
                                <button type="button" class="qty-btn-sm" onclick="updateQty(this, 1)">+</button>
                            </form>
                        </div>
                        <div class="cart-item-subtotal">
                            <p class="subtotal-label">Subtotal</p>
                            <p class="subtotal-amount">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                        </div>
                        <div class="cart-item-remove">
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <button type="submit" class="btn-remove" title="Hapus">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Ringkasan Belanja</h3>
                    <div class="summary-row">
                        <span>Total Item</span>
                        <span>{{ array_sum(array_column($cart, 'quantity')) }} item</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total Harga</span>
                        <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <button class="btn-checkout">Checkout</button>
                    <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 1rem;">
                        @csrf
                        <button type="submit" class="btn-clear" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">Kosongkan Keranjang</button>
                    </form>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <svg width="100" height="100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2>Keranjang Kosong</h2>
                <p>Belum ada produk di keranjang Anda</p>
                <a href="{{ route('home') }}" class="btn-shop-now">Belanja Sekarang</a>
            </div>
        @endif
    </main>

    <script>
        function updateQty(btn, change) {
            const form = btn.closest('.qty-form');
            const input = form.querySelector('input[name="quantity"]');
            const newVal = parseInt(input.value) + change;
            
            if(newVal >= 1) {
                input.value = newVal;
                form.submit();
            }
        }
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
