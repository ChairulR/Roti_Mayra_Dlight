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

@endsection