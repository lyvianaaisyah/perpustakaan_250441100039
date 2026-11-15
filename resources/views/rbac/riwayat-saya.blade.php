@extends('layouts.main')
@section('judul', 'Riwayat Peminjaman Saya')
@section('konten')
    <h2 style="color:#3e2800;">Riwayat & Status Peminjaman Milik: {{ auth()->user()->name }}</h2>
    <div class="layout-samping">
        @can('mengajukan peminjaman buku')
        <aside class="panel-kiri">
            <h3 class="judul-panel">Ajukan Peminjaman Buku</h3>
            <form action="{{ route('pinjam.ajukan') }}" method="POST">
                @csrf
                <div class="field-vertikal">
                    <label>Pilih Buku yang Ingin Dipinjam</label>
                    <select name="buku_id" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }} ({{ $b->penulis }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="tombol-simpan" style="width:100%;">Ajukan Peminjaman</button>
            </form>
        </aside>
        @endcan
        <section class="panel-kanan">
            <h3 class="judul-panel">Daftar Riwayat Peminjaman Saya (ID User: {{ auth()->id() }})</h3>
            <div class="bungkus-tabel">
                <table class="tabel-api">
                    <thead><tr><th>No</th><th>Judul Buku</th><th>Tanggal Pinjam</th><th>Status Peminjaman</th></tr></thead>
                    <tbody>
                        @forelse($riwayat as $i => $r)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><b>{{ $r->buku->judul ?? '-' }}</b></td>
                            <td>{{ $r->tanggal_pinjam }}</td>
                            <td>
                                @if($r->tanggal_kembali)
                                    <span class="badge-kat" style="background:#28a745;">Sudah Dikembalikan ({{ $r->tanggal_kembali }})</span>
                                @else
                                    <span class="badge-kat" style="background:#d68910;">Sedang Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;">Anda belum memiliki riwayat peminjaman buku.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
