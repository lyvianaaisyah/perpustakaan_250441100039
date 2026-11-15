<!-- resources/views/layouts/main.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Perpustakaan Digital Lyviana')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="atas">
        <h1>Perpustakaan Digital Lyviana</h1>
        @auth
            <p style="margin: 6px 0 0 0; font-size: 14px;">
                Login sebagai: <b>{{ auth()->user()->name }}</b>
                <span class="badge-kat" style="background: #3e2800; margin-left: 6px;">
                    Role: {{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'User') }}
                </span>
            </p>
        @endauth
    </header>

    <nav class="menu">
        <a href="{{ route('home') }}">Beranda Utama</a>

        @auth
            {{-- Menu khusus Role Admin --}}
            @role('admin')
                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
            @endrole

            {{-- Menu khusus Role Petugas --}}
            @role('petugas')
                <a href="{{ route('petugas.dashboard') }}">Dashboard Petugas</a>
            @endrole

            {{-- Menu Katalog Buku (Semua role yang punya izin lihat daftar buku) --}}
            @can('lihat daftar buku')
                <a href="{{ route('buku.index') }}">Katalog Buku</a>
            @endcan

            {{-- Menu Kelola Pengguna & Role (Hanya tampil untuk Admin) --}}
            @can('kelola data pengguna')
                <a href="{{ route('pengguna.index') }}">Kelola Pengguna</a>
            @endcan

            {{-- Menu Peminjaman & Pengembalian (Admin & Petugas) --}}
            @can('kelola peminjaman')
                <a href="{{ route('peminjaman.index') }}">Peminjaman & Pengembalian</a>
            @endcan

            {{-- Menu Laporan Perpustakaan (Admin & Petugas) --}}
            @can('lihat laporan perpustakaan')
                <a href="{{ route('laporan.index') }}">Laporan</a>
            @endcan

            {{-- Menu Riwayat Peminjaman Sendiri (Siswa / Pengguna Login) --}}
            @role('siswa')
                <a href="{{ route('riwayat.saya') }}">Riwayat Peminjaman Saya</a>
            @endrole

            {{-- Menu Profil Sendiri --}}
            @can('lihat profil sendiri')
                <a href="{{ route('profil.saya') }}">Profil Saya</a>
            @endcan

            {{-- Tombol Logout (Soal 1 & Skenario 15) --}}
            <form action="{{ route('logout') }}" method="POST" style="display: inline; margin-left: 10px;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        @endauth

        @guest
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endguest
    </nav>

    <main class="isi">
        @if(session('sukses'))
            <div class="alert-box alert-success">{{ session('sukses') }}</div>
        @endif
        @if(session('galat'))
            <div class="alert-box alert-danger">{{ session('galat') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-box alert-warning">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('konten')
    </main>

    <footer class="bawah">
        <p>&copy; 2026 - Lyviana Aisyah Putri (250441100039)</p>
    </footer>

    @stack('scripts')
</body>
</html>