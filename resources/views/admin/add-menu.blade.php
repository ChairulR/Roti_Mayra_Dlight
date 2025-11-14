@extends('admin.layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Menu & Kategori</h1>

    {{-- ✅ Form Tambah Kategori (AJAX) --}}
    <section class="form-section">
        <h2>Tambah Kategori</h2>
        <form id="category-form" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <input type="text" name="name" id="category-name" placeholder="Nama kategori" required>
            <button type="submit">Tambah</button>
        </form>

        <p id="category-success" style="color: green; display:none; margin-top:5px;">
            ✅ Kategori berhasil ditambahkan!
        </p>
    </section>

    {{-- ✅ Form Tambah Roti --}}
    <section class="form-section">
        <h2>Tambah Roti</h2>
        <form action="{{ route('admin.breads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Nama roti" required>
            <textarea name="description" placeholder="Deskripsi"></textarea>
            <input type="number" name="price" placeholder="Harga (Rp)" required>

            {{-- Dropdown kategori --}}
            <select name="category_id" id="category-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <input type="file" name="image" accept="image/*">
            <button type="submit">Tambah Roti</button>
        </form>
    </section>

    {{-- ✅ Daftar Roti --}}
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
                @forelse ($breads as $bread)
                <tr>
                    <td>{{ $bread->name }}</td>
                    <td>{{ $bread->description }}</td>
                    <td>{{ $bread->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($bread->price, 0, ',', '.') }}</td>
                    <td>
                        @if ($bread->image)
                        <img src="{{ asset('storage/' . $bread->image) }}" width="60" alt="{{ $bread->name }}">
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.breads.destroy', $bread) }}" method="POST" onsubmit="return confirm('Hapus roti ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Hapus</button>
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

{{-- ✅ Script AJAX supaya kategori langsung muncul --}}
<script>
    document.getElementById('category-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const name = document.getElementById('category-name').value.trim();
        if (!name) return alert('Nama kategori wajib diisi!');

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name
            })
        });

        const data = await response.json();

        if (data.success) {
            // Tambahkan kategori ke dropdown
            const select = document.getElementById('category-select');
            const option = document.createElement('option');
            option.value = data.category.id;
            option.textContent = data.category.name;
            select.appendChild(option);

            // Reset input
            document.getElementById('category-name').value = '';
            document.getElementById('category-success').style.display = 'block';
            setTimeout(() => document.getElementById('category-success').style.display = 'none', 2500);
        } else {
            alert('Gagal menambahkan kategori.');
        }
    });
</script>
@endsection