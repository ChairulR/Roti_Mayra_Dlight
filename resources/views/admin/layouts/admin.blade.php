<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mayra D'Light</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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

</body>

</html>