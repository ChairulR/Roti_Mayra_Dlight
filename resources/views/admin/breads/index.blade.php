@extends('admin.layouts.admin')

@section('content')
<h1 class="page-title">Manajemen Menu</h1>

<div class="menu-wrapper">
    @foreach ($breads as $bread)
    <div class="menu-card">

        <img src="{{ asset('storage/' . $bread->image) }}" alt="menu" class="menu-img">

        <h3>{{ $bread->name }}</h3>

        <div class="price">
            Rp {{ number_format($bread->price, 0, ',', '.') }}
        </div>

        <p>{{ Str::limit($bread->description, 70) }}</p>

        <div class="btn-group">
            <a href="{{ route('admin.breads.edit', $bread->id) }}" class="btn-edit">Edit</a>

            <form action="{{ route('admin.breads.destroy', $bread->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</button>
            </form>
        </div>

    </div>
    @endforeach
</div>

@endsection