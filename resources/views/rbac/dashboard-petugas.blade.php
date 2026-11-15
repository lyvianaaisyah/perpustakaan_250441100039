@extends('layouts.main')
@section('judul', 'Dashboard Petugas')
@section('konten')
    <div class="detail">
        <h2 style="margin-top:0; color:#3e2800;">Dashboard Petugas Perpustakaan</h2>
        <p>Selamat datang, <b>{{ auth()->user()->name }}</b>. Anda login sebagai <b>Petugas</b> untuk melayani operasional buku, peminjaman, pengembalian, dan laporan.</p>
        <div class="wadah" style="justify-content:flex-start;">
            <div class="kotak"><h3>Total Koleksi Buku</h3><p style="font-size:24px; font-weight:bold;">{{ $totalBuku }}</p></div>
            <div class="kotak"><h3>Peminjaman Aktif</h3><p style="font-size:24px; font-weight:bold;">{{ $totalPeminjaman }}</p></div>
            <div class="kotak"><h3>Sudah Dikembalikan</h3><p style="font-size:24px; font-weight:bold;">{{ $totalKembali }}</p></div>
        </div>
        <div style="margin-top:20px;">
            @can('kelola data buku')<a href="{{ route('buku.index') }}" class="tombol">Kelola Buku</a>@endcan
            @can('kelola peminjaman')<a href="{{ route('peminjaman.index') }}" class="tombol">Proses Peminjaman & Pengembalian</a>@endcan
            @can('lihat laporan perpustakaan')<a href="{{ route('laporan.index') }}" class="tombol">Lihat Laporan</a>@endcan
        </div>
    </div>
@endsection
