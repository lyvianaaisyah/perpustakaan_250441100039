@extends('layouts.main')
@section('judul', 'Dashboard Admin')
@section('konten')
    <div class="detail">
        <h2 style="margin-top:0; color:#3e2800;">Dashboard Administrator</h2>
        <p>Selamat datang, <b>{{ auth()->user()->name }}</b>. Anda login dengan role <b>Admin</b> dan memiliki akses penuh ke seluruh administrasi perpustakaan.</p>
        <div class="wadah" style="justify-content:flex-start;">
            <div class="kotak"><h3>Total Buku</h3><p style="font-size:24px; font-weight:bold;">{{ $totalBuku }}</p></div>
            <div class="kotak"><h3>Total Pengguna</h3><p style="font-size:24px; font-weight:bold;">{{ $totalUser }}</p></div>
            <div class="kotak"><h3>Sedang Dipinjam</h3><p style="font-size:24px; font-weight:bold;">{{ $totalPeminjaman }}</p></div>
        </div>
        <div style="margin-top:20px;">
            @can('kelola data buku')<a href="{{ route('buku.index') }}" class="tombol">Kelola Data Buku</a>@endcan
            @can('kelola data pengguna')<a href="{{ route('pengguna.index') }}" class="tombol">Kelola Pengguna & Role</a>@endcan
            @can('kelola peminjaman')<a href="{{ route('peminjaman.index') }}" class="tombol">Kelola Peminjaman</a>@endcan
            @can('lihat laporan perpustakaan')<a href="{{ route('laporan.index') }}" class="tombol">Lihat Laporan</a>@endcan
        </div>
    </div>
@endsection
