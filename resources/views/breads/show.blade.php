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
                @php
                    $avg = $bread->averageRating();
                    $ratingsCount = $bread->ratings()->count();
                    $userRating = auth()->check() ? $bread->ratings->firstWhere('user_id', auth()->id()) : null;
                @endphp

                <div class="product-rating">
                    <div class="rating-summary">
                        <div class="stars-display">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avg))
                                    <span class="star filled">★</span>
                                @elseif($i - 0.5 <= $avg)
                                    <span class="star half">★</span>
                                @else
                                    <span class="star">★</span>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-value">{{ $avg }} / 5</span>
                        <span class="rating-count">({{ $ratingsCount }} ulasan)</span>
                    </div>
                    @if($ratingsCount > 0)
                        <div class="rating-list">
                            <h4>Ulasan Pelanggan</h4>
                            <div class="reviews-container">
                                @foreach($bread->ratings()->with('user')->latest()->get() as $r)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <div class="reviewer-avatar">
                                                    {{ substr($r->user ? $r->user->name : 'A', 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong class="reviewer-name">{{ $r->user ? $r->user->name : 'Anonim' }}</strong>
                                                    <div class="review-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="star {{ $i <= $r->rating ? 'filled' : '' }}">★</span>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="review-date">{{ $r->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($r->comment)
                                            <p class="review-comment">{{ $r->comment }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
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

                {{-- Rating Form --}}
                <div class="rating-form-section">
                    @if(session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif

                    @auth
                        <form action="{{ route('breads.rating', $bread) }}" method="POST" class="rating-form">
                            @csrf
                            <label for="rating" class="rating-label"><strong>Berikan Rating Anda</strong></label>
                            <div class="star-rating">
                                @for($i=1; $i<=5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ ($userRating && $userRating->rating == $i) ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="star-label" title="{{ $i }} bintang">★</label>
                                @endfor
                            </div>
                            
                            <div class="comment-section">
                                <label for="comment" class="comment-label">
                                    <strong>Tulis Komentar Anda</strong>
                                    <span class="optional-text">(Opsional)</span>
                                </label>
                                <textarea 
                                    name="comment" 
                                    id="comment" 
                                    rows="4" 
                                    maxlength="500"
                                    class="comment-textarea"
                                    placeholder="Bagikan pengalaman Anda tentang produk ini..."
                                >{{ $userRating ? $userRating->comment : '' }}</textarea>
                                <div class="comment-counter">
                                    <span id="charCount">{{ $userRating && $userRating->comment ? strlen($userRating->comment) : 0 }}</span>/500 karakter
                                </div>
                            </div>

                            <div class="rating-submit">
                                <button type="submit" class="btn-submit-rating">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Rating & Komentar
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="login-prompt">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ route('login') }}">Login</a> untuk memberi rating dan ulasan
                        </p>
                    @endauth
                </div>
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

        // Star rating hover effect
        document.addEventListener('DOMContentLoaded', function() {
            const starRating = document.querySelector('.star-rating');
            if (starRating) {
                const labels = starRating.querySelectorAll('.star-label');
                
                labels.forEach((label, index) => {
                    label.addEventListener('mouseenter', function() {
                        // Highlight this star and all previous stars
                        for (let i = 0; i <= index; i++) {
                            labels[i].style.color = '#fbbf24';
                            labels[i].style.transform = 'scale(1.1)';
                        }
                        // Reset stars after this one
                        for (let i = index + 1; i < labels.length; i++) {
                            labels[i].style.color = '#d1d5db';
                            labels[i].style.transform = 'scale(1)';
                        }
                    });
                });
                
                starRating.addEventListener('mouseleave', function() {
                    // Reset all stars to default or checked state
                    const checkedInput = starRating.querySelector('input:checked');
                    labels.forEach((label, index) => {
                        if (checkedInput) {
                            const checkedValue = parseInt(checkedInput.value);
                            if (index < checkedValue) {
                                label.style.color = '#fbbf24';
                            } else {
                                label.style.color = '#d1d5db';
                            }
                        } else {
                            label.style.color = '#d1d5db';
                        }
                        label.style.transform = 'scale(1)';
                    });
                });

                // Add click animation
                labels.forEach(label => {
                    label.addEventListener('click', function() {
                        label.style.animation = 'starPulse 0.3s ease';
                        setTimeout(() => {
                            label.style.animation = '';
                        }, 300);
                    });
                });
            }
            
            // Character counter for comment
            const commentTextarea = document.getElementById('comment');
            const charCount = document.getElementById('charCount');
            
            if (commentTextarea && charCount) {
                commentTextarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
