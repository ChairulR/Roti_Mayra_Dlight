<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple-modular.css') }}">
    <style>
        /* === Layout Umum === */
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background-color: #f8f4ec;
  display: flex;
  min-height: 100vh;
  color: #4a3b2d;
}

/* === Sidebar === */
.sidebar {
  width: 220px;
  background-color: #c49b66;
  color: white;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
}

.sidebar h2 {
  text-align: center;
  font-size: 20px;
  margin-bottom: 20px;
}

.menu-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.sidebar a {
  color: white;
  text-decoration: none;
  padding: 0.6rem 0.9rem;
  border-radius: 8px;
  transition: 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
  background-color: #b17e45;
}

/* === Tombol Logout === */
.logout-btn {
  text-align: center;
  margin-top: auto;
}

.logout-btn button {
  width: 100%;
  padding: 0.6rem;
  background-color: #8a3b2f;
  border-radius: 8px;
  color: white;
  border: none;
  cursor: pointer;
  font-weight: bold;
}

.logout-btn button:hover {
  background-color: #6f2f24;
}

/* === Konten Utama === */
.main-content {
  flex: 1;
  padding: 2rem 3rem;
  background-color: #fffaf4;
  margin-left: 240px;
  min-height: 100vh;
}

h1, h2 {
  color: #7a4e2d;
  margin-top: 0;
}

/* === Tabel (kalau digunakan di Dashboard) === */
table {
  width: 100%;
  border-collapse: collapse;
  background-color: #ffffff;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 1rem;
}

table th, table td {
  padding: 0.8rem;
  text-align: left;
  border-bottom: 1px solid #e0d6c7;
}

table th {
  background-color: #f1e3d3;
  color: #5a3921;
}

img {
  border-radius: 6px;
  width: 80px;
}

/* === Tombol Umum === */
button {
  background-color: #c49b66;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background-color: #b17e45;
}

/* ====== MANAGE MENU ====== */

.menu-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.menu-card {
    background: white;
    border-radius: 10px;
    border: 1px solid #f0d9b5;
    padding: 15px;
    text-align: center;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
}

.menu-card img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 10px;
}

.price {
    font-weight: bold;
    color: #c47a2c;
    margin: 5px 0;
}

.btn-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}

.btn-edit {
    background: #ffc107;
    padding: 6px 12px;
    border-radius: 6px;
    color: #000;
}

.btn-delete {
    background: #dc3545;
    padding: 6px 12px;
    border-radius: 6px;
    color: white;
    border: none;
}

/* === Add Menu Page === */
.container {
  max-width: 900px;
  margin: 0 auto;
}

.form-section {
  background-color: #fffaf4;
  border: 1px solid #e1c9a9;
  border-radius: 10px;
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
}

.form-section form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}

.form-section input,
.form-section textarea,
.form-section select {
  flex: 1 1 200px;
  padding: 0.5rem;
  border: 1px solid #d1b894;
  border-radius: 6px;
}

.table-section table {
  margin-top: 1rem;
}

    </style>
</head>

<body>

    <h1>Admin Dashboard</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
    @endif

    {{-- Tambah Kategori --}}
    <section>
        <h2>Tambah Kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori" required>
            <button type="submit">Tambah</button>
        </form>
    </section>

    {{-- Tambah Roti --}}
    <section>
        <h2>Tambah Roti</h2>
        <form method="POST" action="{{ route('admin.breads.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Nama roti" required><br>
            <textarea name="description" placeholder="Deskripsi"></textarea><br>

            {{-- Input harga berbentuk angka, bukan dropdown --}}
            <input type="number" name="price" placeholder="Harga (Rp)" min="0" step="100" required><br>

            <select name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select><br>

            <input type="file" name="image" accept="image/*"><br>
            <button type="submit">Tambah Roti</button>
        </form>
    </section>


    {{-- Daftar Roti --}}
    <section>
        <h2>Daftar Roti</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breads as $bread)
                <tr>
                    <td>{{ $bread->name }}</td>
                    <td>{{ $bread->description ?? '-' }}</td>
                    <td>{{ $bread->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($bread->price, 0, ',', '.') }}</td>
                    <td>
                        @if($bread->image)
                        <img src="{{ asset('storage/'.$bread->image) }}" alt="gambar roti" width="60">
                        @else
                        <span>-</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.breads.destroy', $bread) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus roti ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Belum ada data roti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </section>

</body>

</html>
