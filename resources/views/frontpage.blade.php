<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mayra D'Light — Toko Roti</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
    @livewireStyles

    <!-- Swiper Slider CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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

    {{-- Hero Slider Section --}}
    <div class="hero-slider-wrapper">
        <div class="hero-slider">
            <div class="slider-container">
                <div class="slide active">
                    <div class="slide-content">
                        <div class="slide-text">
                            <h1>Temukan <span class="highlight">Kelezatan</span> Sejati</h1>
                            <p class="slide-description">Nikmati koleksi roti premium kami yang dibuat dengan bahan berkualitas tinggi dan resep tradisional</p>
                            <a class="cta-button" href="#catalog">Jelajahi Produk</a>
                        </div>
                        <div class="slide-image">
                            <div class="image-decoration"></div>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide-content">
                        <div class="slide-text">
                            <h1>Roti <span class="highlight">Segar</span> Setiap Hari</h1>
                            <p class="slide-description">Dipanggang fresh setiap pagi untuk memberikan pengalaman terbaik</p>
                            <a class="cta-button" href="#top-rated">Produk Terpopuler</a>
                        </div>
                        <div class="slide-image">
                            <div class="image-decoration decoration-2"></div>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide-content">
                        <div class="slide-text">
                            <h1>Resep <span class="highlight">Keluarga</span> Turun Temurun</h1>
                            <p class="slide-description">Cita rasa autentik yang telah dipercaya selama bertahun-tahun</p>
                            <a class="cta-button" href="#catalog">Lihat Katalog</a>
                        </div>
                        <div class="slide-image">
                            <div class="image-decoration decoration-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="slider-btn prev" onclick="changeSlide(-1)">‹</button>
            <button class="slider-btn next" onclick="changeSlide(1)">›</button>
            <div class="slider-dots">
                <span class="dot active" onclick="currentSlide(0)"></span>
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
            </div>
        </div>
    </div>

    <main class="container">
        {{-- Top Rated Products Section --}}
        @if($topRatedProducts && $topRatedProducts->count() > 0)
        <section id="top-rated" class="top-rated-section">
            <div class="section-header">
                <h2>Produk Unggulan</h2>
                <p class="section-subtitle">Pilihan terbaik berdasarkan rating pelanggan kami</p>
            </div>
            <div class="top-rated-grid">
                @foreach($topRatedProducts as $product)
                <a href="{{ route('breads.show', $product) }}" class="top-rated-card">
                    <div class="card-badge">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ number_format($product->averageRating(), 1) }}
                    </div>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-image">
                    @else
                        <div class="card-image-placeholder"></div>
                    @endif
                    <div class="card-content">
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <div class="card-rating">
                            @php $rating = $product->averageRating(); @endphp
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($rating))
                                        <span class="star filled">★</span>
                                    @elseif($i - 0.5 <= $rating)
                                        <span class="star half">★</span>
                                    @else
                                        <span class="star">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-count">({{ $product->ratings->count() }})</span>
                        </div>
                        <div class="card-footer">
                            <span class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if($product->category)
                                <span class="card-category">{{ $product->category->name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- =======================
          SLIDER IKLAN DINAMIS
        ======================= -->
        <div class="swiper mySwiper" style="width:100%; height:300px; border-radius:12px; margin-bottom:40px; overflow:hidden;">

        @php
            $banners = \App\Models\Banner::all();
        @endphp

        <div class="swiper-wrapper">
            @foreach($banners as $b)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $b->gambar) }}"
                         style="width:100%; height:100%; object-fit:cover;">
                </div>
            @endforeach
        </div>

        <!-- Navigasi -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
        </div>

        <!-- ======================= -->

        <section id="catalog">
            <h2>Katalog Roti</h2>

            {{-- Livewire Search Component --}}
            @livewire('livesearch')

        </section>
    </main>
    
    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        
        function showSlide(n) {
            if (n >= slides.length) currentSlideIndex = 0;
            if (n < 0) currentSlideIndex = slides.length - 1;
            
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            slides[currentSlideIndex].classList.add('active');
            dots[currentSlideIndex].classList.add('active');
        }
        
        function changeSlide(n) {
            currentSlideIndex += n;
            showSlide(currentSlideIndex);
        }
        
        function currentSlide(n) {
            currentSlideIndex = n;
            showSlide(currentSlideIndex);
        }
        
        // Auto slide every 5 seconds
        setInterval(() => {
            currentSlideIndex++;
            showSlide(currentSlideIndex);
        }, 5000);
    </script>

    @livewireScripts
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>

</body>

</html>