@extends('admin.layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4 text-brown">Manajemen Menu Toko</h2>

    <form action="{{ route('admin.breads.index') }}" method="GET" class="mb-4 d-flex">
        <input type="text" name="search" class="form-control me-2"
            placeholder="Cari menu roti..." value="{{ $search ?? '' }}">
        <button class="btn btn-brown">Cari</button>
    </form>

    @foreach ($breads as $bread)
    <div class="card mb-3 shadow-sm border-warning-subtle">
        <div class="card-body d-flex justify-content-between align-items-center">

            {{-- Kiri: Gambar + Info --}}
            <div class="d-flex align-items-center">
                @if ($bread->image)
                <img src="{{ asset('storage/' . $bread->image) }}"
                    alt="{{ $bread->name }}"
                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                @endif
                <div>
                    <h5 class="fw-bold mb-1">{{ $bread->name }}</h5>
                    <p class="mb-1 text-secondary">
                        {{ $bread->category ? $bread->category->name : '-' }}
                        — Rp {{ number_format($bread->price, 0, ',', '.') }}
                    </p>
                    <small class="text-muted">{{ $bread->description }}</small>
                </div>
            </div>

            {{-- Kanan: Tombol Aksi --}}
            <div class="d-flex">
                <a href="{{ route('admin.breads.edit', $bread->id) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                <form action="{{ route('admin.breads.destroy', $bread->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus roti ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </div>

        </div>
    </div>
    @endforeach
</div>
@endsection