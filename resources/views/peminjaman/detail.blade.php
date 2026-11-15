@extends('layouts.main')
@section('judul', 'Detail Peminjaman')
@section('konten')
    <div class="detail">
        <h2 style="margin-top: 0; color: #3e2800;">Detail Data Peminjaman #{{ $peminjaman->id }}</h2>
        <hr>
        <p>ID Peminjaman: <b>#{{ $peminjaman->id }}</b></p>
        <p>Nama Peminjam: <b>{{ $peminjaman->anggota->nama ?? '-' }}</b></p>
        <p>Email Peminjam: <b>{{ $peminjaman->anggota->email ?? '-' }}</b></p>
        <p>Judul Buku: <b>{{ $peminjaman->buku->judul ?? '-' }}</b></p>
        <p>Tanggal Pinjam: <b>{{ $peminjaman->tanggal_pinjam }}</b></p>
        <p>Status / Tanggal Kembali: 
            @if($peminjaman->tanggal_kembali)
                <span class="badge-kat" style="background:#28a745;">Sudah Dikembalikan ({{ $peminjaman->tanggal_kembali }})</span>
            @else
                <span class="badge-kat" style="background:#d68910;">Sedang Dipinjam</span>
            @endif
        </p>
        <br>
        <a href="{{ route('buku.index') }}" class="tombol">Kembali</a>
    </div>
@endsection