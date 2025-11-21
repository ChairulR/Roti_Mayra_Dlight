<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard — Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h2>Mayra D'Light</h2>
            <div class="menu-list">
                <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
                <a href="{{ route('admin.addmenu') }}">Kelola Menu</a>
                <a href="{{ route('admin.categories.index') }}">Kategori</a>
                <a href="{{ route('home') }}">Lihat Toko</a>
            </div>
        </div>
        <div class="logout-btn">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>

        {{-- Notifikasi sukses --}}
        @if(session('success'))
        <div class="alert" style="background: #d4edda; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid #28a745; border-radius: 5px; color: #155724;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Tambah Kategori --}}
        <section class="form-section">
            <h2>Tambah Kategori</h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="text" name="name" placeholder="Nama kategori" required>
                <button type="submit">Tambah</button>
            </form>
        </section>

        {{-- Tambah Roti --}}
        <section class="form-section">
            <h2>Tambah Roti</h2>
            <form method="POST" action="{{ route('admin.breads.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" name="name" placeholder="Nama roti" required>
                <textarea name="description" placeholder="Deskripsi"></textarea>
                <input type="number" name="price" placeholder="Harga (Rp)" min="0" step="100" required>
                <select name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <input type="file" name="image" accept="image/*">
                <button type="submit">Tambah Roti</button>
            </form>
        </section>

        {{-- Daftar Roti --}}
        <section class="table-section">
            <h2>Daftar Roti</h2>
            <table>
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
                            <form method="POST" action="{{ route('admin.breads.destroy', $bread) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus roti ini?')" class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">Belum ada data roti.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

</body>

</html>
