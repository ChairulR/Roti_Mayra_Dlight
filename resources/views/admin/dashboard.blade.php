@extends('admin.layouts.admin')

@section('content')
<h1>Dashboard Admin</h1>

<p>
    Selamat datang di panel admin Mayra D'Light!
</p>

<div class="stats">
    <div class="card">
        <h3>Total Kategori</h3>
        <p>{{ $categories->count() }}</p>
    </div>

    <div class="card">
        <h3>Total Menu Roti</h3>
        <p>{{ $breads->count() }}</p>
    </div>
</div>

<hr>

<h2>Daftar Menu Roti</h2>
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
        @foreach ($breads as $bread)
        <tr>
            <td>{{ $bread->name }}</td>
            <td>{{ $bread->description }}</td>
            <td>{{ $bread->category ? $bread->category->name : '-' }}</td>
            <td>Rp {{ number_format($bread->price, 0, ',', '.') }}</td>
            <td>
                @if ($bread->image)
                <img src="{{ asset('storage/' . $bread->image) }}" width="60">
                @else
                <small>Tidak ada gambar</small>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection