@extends('admin.layouts.admin')

@section('content')

{{-- ======================================= --}}
{{-- 🔥 STYLES DAN SCRIPT UNTUK NOTIFIKASI (Diposisikan di atas untuk loading) 🔥 --}}
{{-- ======================================= --}}
<style>
    /* --- Penataan Umum Header --- */
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title p {
        margin-top: -10px;
        color: #7a5a3a;
    }

    /* --- Styling Notifikasi --- */
    .notification-container {
        position: relative;
        margin-left: auto;
        /* Mendorong ke kanan */
        flex-shrink: 0;
        align-self: flex-start;
        padding-top: 15px;
        /* Sesuaikan agar sejajar dengan h1 */
    }

    .notification-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 24px;
        color: #7a4e2d;
        /* Warna cokelat */
        position: relative;
    }

    /* Bintang Lonceng yang awalnya dikirim dobel, sekarang hanya ikon */
    .notification-btn i.fa-bell {
        font-size: 24px;
    }

    .notification-btn:hover {
        color: #5d442c;
    }

    .notification-badge {
        position: absolute;
        top: 5px;
        right: -5px;
        background-color: #e63946;
        /* Merah */
        color: white;
        font-size: 10px;
        padding: 3px 6px;
        border-radius: 50%;
        line-height: 1;
        transform: translate(0, -50%);
    }

    .notification-dropdown {
        display: none;
        /* Awalnya tersembunyi */
        position: absolute;
        top: 45px;
        /* Jarak dari tombol */
        right: 0;
        width: 320px;
        background: white;
        border: 1px solid #e0d6c7;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-dropdown.active {
        display: block;
        /* Tampilkan saat aktif */
    }

    .notification-dropdown h6 {
        padding: 10px;
        margin: 0;
        border-bottom: 1px solid #e0d6c7;
        color: #7a4e2d;
    }

    .notification-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background-color: #fdf7f1;
    }

    .notification-item p {
        margin: 3px 0;
        font-size: 14px;
    }

    .notification-item .user-name {
        font-weight: bold;
    }

    .star-filled {
        color: #FFD700;
    }

    .star-empty {
        color: #d1d1d1;
    }

    .comment-text {
        font-style: italic;
        font-size: 14px;
    }
</style>

{{-- CONTAINER JUDUL & NOTIFIKASI --}}
<div class="dashboard-header">

    {{-- Judul dan Sambutan (Aligned Left) --}}
    <div style="flex-shrink: 0;" class="header-title">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang di panel admin Mayra D'Light</p>
    </div>

    {{-- 🔥 WADAH NOTIFIKASI BARU (Pushed to Right menggunakan margin-left: auto pada CSS) 🔥 --}}
    <div class="notification-container">

        {{-- Tombol Notifikasi --}}
        <button class="notification-btn" id="notification-toggle">
            <i class="fas fa-bell"></i>

            {{-- Badge, muncul hanya jika ada rating baru (Menggunakan $newRatingsCount) --}}
            @if (isset($newRatingsCount) && $newRatingsCount > 0)
            <span class="notification-badge">{{ $newRatingsCount }}</span>
            @endif
        </button>

        {{-- Dropdown Notifikasi --}}
        <div class="notification-dropdown" id="notification-dropdown">
            <h6>Notifikasi Rating ({{ $latestRatings->count() ?? 0 }})</h6>

            {{-- Loop untuk menampilkan 10 rating terbaru --}}
            @forelse ($latestRatings as $rating)
            <div class="notification-item">
                <p class="user-name">Dari: {{ $rating->user->name ?? 'Pengguna' }}</p>

                {{-- Menampilkan Nama Menu Roti dari Relasi Polymorphic rateable --}}
                @php
                $breadName = 'Menu Roti';
                if ($rating->rateable_type === App\Models\Bread::class && $rating->rateable) {
                $breadName = $rating->rateable->name;
                }
                @endphp
                <p>Menu: {{ $breadName }}</p>

                {{-- Tampilkan rating dengan bintang --}}
                <p>
                    Rating:
                    @for ($i = 0; $i < 5; $i++)
                        <span class="{{ $i < $rating->rating ? 'star-filled' : 'star-empty' }}">★</span>
                        @endfor
                </p>

                <p class="comment-text">"{{ Str::limit($rating->comment, 50) }}"</p>
            </div>
            @empty
            <div class="notification-item">
                <p style="margin:0;">Tidak ada rating baru.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>


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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('notification-toggle');
        const dropdown = document.getElementById('notification-dropdown');

        // Fungsi untuk menampilkan/menyembunyikan dropdown
        toggleButton.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('active');
        });

        // Menutup dropdown jika klik di luar area notifikasi
        document.addEventListener('click', function(event) {
            if (!dropdown.contains(event.target) && !toggleButton.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Mencegah dropdown tertutup saat mengklik di dalamnya
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>

@endsection