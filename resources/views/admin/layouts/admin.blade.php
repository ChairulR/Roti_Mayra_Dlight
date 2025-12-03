<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mayra D'Light</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
</head>

<body>

    {{-- Sidebar Admin --}}
    <div class="sidebar">
        <h2>Mayra D'Light</h2>

        <div class="menu-list">
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.addmenu') }}"
                class="{{ request()->routeIs('admin.addmenu') ? 'active' : '' }}">
                Add Menu
            </a>

            <a href="{{ route('admin.breads.index') }}"
                class="{{ request()->routeIs('admin.breads.index') ? 'active' : '' }}">
                Manajemen Menu
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                Order Masuk
            </a>

            {{-- Lihat Toko --}}
            <a href="{{ url('/') }}" target="_blank">
                Lihat Toko
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="logout-btn">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    {{-- Konten Utama --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('script')
</body>

</html>