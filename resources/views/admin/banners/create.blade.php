@extends('admin.layouts.admin')

@section('content')
<h2>Tambah Banner</h2>

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Judul Banner (opsional)</label>
    <input type="text" name="judul" class="input">

    <label>Upload Gambar Banner</label>
    <input type="file" name="gambar" required>

    <button type="submit" class="btn">Simpan</button>
</form>
@endsection
