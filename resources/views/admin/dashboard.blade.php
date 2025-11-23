@extends('admin.layouts.admin')

@section('content')
<h1>Dashboard Admin</h1>
<p style="margin-top:-10px; color:#7a5a3a;">Selamat datang di panel admin Mayra D'Light</p>

{{-- Dropdown Filter Kategori --}}
<div style="margin-top:25px; margin-bottom:20px;">

    <form action="{{ route('admin.dashboard') }}" method="GET">
        <label style="font-weight:bold; color:#7a4e2d;">Pilih Kategori</label><br>

        <select name="category" onchange="this.form.submit()"
            style="padding:10px; width:300px; border-radius:8px; border:1px solid #c49b66; margin-top:8px;">

            <option value="">-- Semua Kategori --</option>

            @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach

        </select>
    </form>

    {{-- Menampilkan kategori aktif --}}
    @php
    $activeCategory = $categories->firstWhere('id', $selectedCategory);
    @endphp

    @if($selectedCategory && $activeCategory)
    <p style="margin-top:10px; color:#7a5a3a;">
        Menampilkan menu kategori:
        <b>{{ $activeCategory->name }}</b>
    </p>
    @elseif($selectedCategory && !$activeCategory)
    <p style="margin-top:10px; color:red;">
        Kategori tidak ditemukan.
    </p>
    @else
    <p style="margin-top:10px; color:#7a5a3a;">
        Menampilkan semua menu roti
    </p>
    @endif
</div>


{{-- Statistik --}}
<div style="display:flex; gap:20px; margin-top:20px;">
    <div style="
        background:#f8eee2; 
        padding:15px 20px; 
        border-radius:10px; 
        border-left:5px solid #c49b66;
        flex:1;">
        <h3 style="margin:0; color:#7a4e2d;">Total Kategori</h3>
        <p style="font-size:22px; font-weight:bold; margin:5px 0;">{{ $totalCategories }}</p>
    </div>

    <div style="
        background:#f8eee2; 
        padding:15px 20px; 
        border-radius:10px; 
        border-left:5px solid #c49b66;
        flex:1;">
        <h3 style="margin:0; color:#7a4e2d;">Total Menu Roti</h3>
        <p style="font-size:22px; font-weight:bold; margin:5px 0;">{{ $totalBreads }}</p>
    </div>
</div>



{{-- Daftar Menu --}}
<h2 style="margin-top:40px;">Daftar Menu Roti</h2>
<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
        @forelse($breads as $bread)
        <tr>
            <td>{{ $bread->name }}</td>
            <td style="width:300px;">{{ $bread->description }}</td>
            <td>{{ $bread->category->name ?? '-' }}</td>
            <td>Rp {{ number_format($bread->price, 0, ',', '.') }}</td>
            <td>
                @if($bread->image)
                <img src="{{ asset('storage/' . $bread->image) }}" width="80" height="80" style="object-fit:cover;">
                @else -
                @endif
            </td>
        </tr>

        {{-- Baris kosong seperti desain --}}
        <tr>
            <td colspan="5" style="height:35px; background:#fdf7f1;"></td>
        </tr>

        @empty
        <tr>
            <td colspan="5">Belum ada data roti.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection