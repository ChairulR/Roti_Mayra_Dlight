@extends('admin.layouts.admin')

@section('content')
<div class="container mt-4">

    <h2 class="fw-bold mb-4 text-brown">Manajemen Menu</h2>

    {{-- ===========================
        FILTERING & SEARCH BAR
    ============================ --}}
    <div class="d-flex gap-3 mb-4 flex-wrap">

        {{-- Filter kategori --}}
        <form action="{{ route('admin.breads.index') }}" method="GET" class="d-flex">
            <select name="category" class="form-select me-2">
                <option value="">Semua Kategori</option>

                @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>

            <button class="btn btn-warning text-white">Filter</button>
        </form>

        {{-- Search menu --}}
        <form action="{{ route('admin.breads.index') }}" method="GET" class="d-flex ms-auto">
            <input type="text" name="search" class="form-control me-2"
                placeholder="Cari menu..." value="{{ request('search') }}">
            <button class="btn btn-brown">Cari</button>
        </form>

    </div>

    {{-- KOTAK PENGATURAN ADS ADMIN --}}
    <div class="p-3 mb-4 rounded" style="border: 2px solid #c49b66; background: #fff8f0;">
        <h5 class="fw-bold text-brown mb-3">⭐ Pengaturan Menu Promosi (Maks. 3 Ads)</h5>

        @php
        // Ambil menu yang sedang dipromosikan.
        // (Asumsi: Anda mungkin perlu memuat ulang data ini dari DB di Controller jika $breads hanya berisi hasil filter)
        // Untuk memastikan keakuratan, kita panggil Model di sini (meskipun lebih baik di Controller)
        $promotedBreads = \App\Models\Bread::where('is_promoted', true)->limit(3)->get();
        @endphp

        @forelse ($promotedBreads as $pBread)
        <div class="d-flex align-items-center mb-2 p-2 border rounded" style="background: #f8eee2;">
            @if ($pBread->image)
            <img src="{{ asset('storage/' . $pBread->image) }}" alt="{{ $pBread->name }}" width="40" height="40" style="object-fit: cover; border-radius: 4px;" class="me-3">
            @else
            <div class="me-3" style="width: 40px; height: 40px; background: #ddd; border-radius: 4px;"></div>
            @endif
            <span class="fw-bold text-success me-auto">{{ $pBread->name }}</span>
            <span class="badge bg-warning text-dark me-2">AD #{{ $loop->iteration }}</span>
            <a href="{{ route('admin.breads.edit', $pBread->id) }}" class="btn btn-sm btn-brown text-white">Edit</a>
        </div>
        @empty
        <p class="text-secondary mb-0">Belum ada menu yang dipromosikan. Silakan klik ikon hati pada menu di bawah untuk menjadikannya iklan.</p>
        @endforelse

        @if($promotedBreads->count() >= 3)
        <p class="text-danger mt-2 mb-0" style="font-size: small;">* Batas maksimum 3 menu promosi sudah tercapai.</p>
        @endif
    </div>

    {{-- ===========================
        LIST KATEGORI
    ============================ --}}
    <div class="category-box mb-4 p-3 rounded">

        <h5 class="fw-bold text-brown mb-3">Daftar Kategori</h5>

        @foreach ($categories as $category)
        <div class="d-flex justify-content-between align-items-center category-item">

            <span class="fw-semibold">{{ $category->name }}</span>

            <form action="{{ route('admin.categories.delete', $category->id) }}"
                method="POST"
                onsubmit="return confirm('Hapus kategori ini?')">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>

        </div>
        @endforeach

    </div>

    {{-- ===========================
        CARD GRID 3 KOLOM
    ============================ --}}
    <div class="row menu-row">
        @forelse ($breads as $bread)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 menu-card">

                {{-- Gambar --}}
                @if ($bread->image)
                <img src="{{ asset('storage/' . $bread->image) }}"
                    class="card-img-top menu-img"
                    alt="{{ $bread->name }}">
                @endif

                {{-- TOMBOL TOGGLE PROMOSI --}}
                <button type="button"
                    class="btn btn-sm promoted-toggle-btn {{ $bread->is_promoted ? 'btn-danger' : 'btn-outline-danger' }}"
                    data-bread-id="{{ $bread->id }}"
                    data-url="{{ route('admin.breads.toggle_promoted', $bread->id) }}"
                    title="Toggle Promosi"
                    style="position: absolute; top: 10px; right: 10px; z-index: 10; border-radius: 50%; width: 40px; height: 40px; padding: 0;">
                    <i class="{{ $bread->is_promoted ? 'fas fa-heart' : 'far fa-heart' }}"></i>
                </button>

                <div class="card-body">

                    <h5 class="fw-bold">{{ $bread->name }}</h5>

                    <p class="text-muted small mb-1">
                        {{ $bread->category->name ?? '-' }}
                    </p>

                    <h6 class="text-brown fw-bold mb-2">
                        Rp {{ number_format($bread->price, 0, ',', '.') }}
                    </h6>

                    <p class="small text-secondary menu-description">
                        {{ Str::limit($bread->description, 70) }}
                    </p>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('admin.breads.edit', $bread->id) }}"
                            class="btn btn-primary btn-sm">Edit</a>

                        <form action="{{ route('admin.breads.destroy', $bread->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">Tidak ada menu ditemukan.</p>
        @endforelse
    </div>

</div>

{{-- ================================
    CSS Styling Tambahan
================================ --}}
<style>
    /* --- Kategori Box --- */
    .category-box {
        background: #fff5e6;
        border: 1px solid #e7d2b8;
    }

    .category-item {
        background: #fdf2dd;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 8px;

        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-item button {
        padding: 4px 10px;
        font-size: 13px;
    }

    /* =========================================
       FIX GRID CARD — agar benar-benar 3 kolom
    ========================================== */
    .menu-row {
        display: flex;
        flex-wrap: wrap;
    }

    .menu-row>.col-md-4 {
        flex: 0 0 33.333%;
        max-width: 33.333%;
        display: flex;
        /* penting agar semua card sama tinggi */
    }

    /* --- Card Menu --- */
    .menu-card {
        border-radius: 12px;
        width: 100%;
        height: 100%;
        transition: 0.25s;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
    }

    .menu-img {
        height: 180px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    /* ================================
       deskripsi agar tidak keluar dari card
    ================================= */
    .menu-description {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 55px;
        /* tinggi konsisten */
    }

    .text-brown {
        color: #8B5E3C;
    }

    .btn-brown {
        background-color: #8B5E3C;
        color: white;
    }

    .btn-brown:hover {
        background-color: #714a2f;
        color: white;
    }
</style>

@endsection

{{-- SKRIP AJAX ADS --}}
@section('script')
{{-- Pastikan jQuery sudah dimuat di layout admin Anda (admin.layouts.admin) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ambil CSRF token dari meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Debug: log CSRF token untuk memastikan ada
        console.log('CSRF Token:', csrfToken);

        $('.promoted-toggle-btn').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const url = $button.data('url');
            const breadId = $button.data('bread-id');
            
            // Debug: log URL dan data
            console.log('Toggle URL:', url);
            console.log('Bread ID:', breadId);
            console.log('CSRF:', csrfToken);

            // Validasi CSRF token
            if (!csrfToken) {
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            $button.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                data: {
                    _token: csrfToken
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response);
                    
                    // Update tampilan tombol dan ikon
                    if (response.is_promoted) {
                        $button.removeClass('btn-outline-danger').addClass('btn-danger');
                        $button.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
                    } else {
                        $button.removeClass('btn-danger').addClass('btn-outline-danger');
                        $button.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                    }

                    alert(response.message);
                    // Reload window agar Kotak Pengaturan Promosi (Admin Ads Box) terupdate
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error,
                        headers: xhr.getAllResponseHeaders()
                    });
                    
                    let errorMessage = 'Terjadi kesalahan tidak terduga.';
                    
                    if (xhr.status === 419) {
                        errorMessage = 'Session expired (419). Silakan refresh halaman dan coba lagi.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Akses ditolak (403). Anda tidak memiliki izin.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Route tidak ditemukan (404). URL: ' + url;
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan di server (500). Silakan coba lagi.';
                        // Tampilkan detail error jika ada
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage += '\nDetail: ' + xhr.responseJSON.message;
                        }
                    } else if (xhr.status === 0) {
                        errorMessage = 'Tidak dapat terhubung ke server. Cek koneksi internet Anda.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        // Coba parse error dari response text
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error('Failed to parse error response:', e);
                        }
                    }
                    
                    alert(errorMessage);
                    $button.prop('disabled', false);
                },
                complete: function() {
                    console.log('AJAX request completed');
                }
            });
        });
    });
</script>
@endsection