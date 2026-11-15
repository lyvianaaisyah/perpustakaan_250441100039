<!-- resource/view/buku/detail.blade.php -->
@extends('layouts.main') 
@section('judul', 'Detail Informasi')
@section('konten')
    <div class="detail">
        @if($buku_detail)
            <h2 style="margin-top: 0;">{{ $buku_detail['judul'] }}</h2>
            <hr>
            <p>ID Buku: <b>{{ $buku_detail['id'] }}</b></p>
            <p>Penulis: <b>{{ $buku_detail['penulis'] }}</b></p>
            <p>Tahun: <b>{{ $buku_detail['tahun_terbit'] }}</b></p>
            <p>Kategori: <b>{{ $buku_detail->kategori->nama ?? '-' }}</b></p>
            <br>
            <a href="{{ route('buku.index') }}" class="tombol">Kembali</a>
        @else
            <h2 style="color: red;">Buku Tidak Valid!</h2>
            <p>Sistem tidak menemukan buku dengan ID tersebut.</p>
            <br>
            <a href="{{ route('buku.index') }}" class="tombol">Kembali</a>
        @endif
    </div>
@endsection