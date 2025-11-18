@extends('admin.layouts.admin')

@section('content')
<h2>Kelola Banner Slider</h2>

<a href="{{ route('admin.banners.create') }}" class="btn">Tambah Banner</a>

<table class="table">
    <tr>
        <th>Gambar</th>
        <th>Aksi</th>
    </tr>

    @foreach($banners as $b)
    <tr>
        <td><img src="{{ asset('storage/' . $b->gambar) }}" width="200"></td>
        <td>
            <form action="{{ route('admin.banners.destroy', $b->id) }}" method="POST">
                @csrf @method('DELETE')
                <button class="btn-delete">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
