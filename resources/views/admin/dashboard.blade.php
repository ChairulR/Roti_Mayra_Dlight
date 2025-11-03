<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/simple.css') }}">
    <style>
        body {
            margin: 2rem;
            font-family: sans-serif;
            background: #f9f9f9;
        }

        h1,
        h2 {
            margin-bottom: .5rem;
        }

        form {
            margin-bottom: 2rem;
        }

        input,
        textarea,
        select,
        button {
            padding: .5rem;
            margin-top: .4rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
            background: white;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: .5rem;
            text-align: center;
        }

        .alert {
            background: #def;
            padding: .5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #39f;
        }

        img {
            border-radius: 5px;
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