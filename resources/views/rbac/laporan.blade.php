@extends('layouts.main')
@section('judul', 'Laporan Perpustakaan')
@section('konten')
    <h2 style="color:#3e2800;">Laporan Rekapitulasi Perpustakaan</h2>
    <div class="layout-samping">
        <aside class="panel-kiri">
            <h3 class="judul-panel">Rekap Buku per Kategori</h3>
            <table class="tabel-api">
                <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
                <tbody>
                    @foreach($kategoris as $k)
                        <tr><td><b>{{ $k->nama }}</b></td><td>{{ $k->bukus_count }} Buku</td></tr>
                    @endforeach
                </tbody>
            </table>
        </aside>
        <section class="panel-kanan">
            <h3 class="judul-panel">Laporan Seluruh Riwayat Peminjaman</h3>
            <div class="bungkus-tabel">
                <table class="tabel-api">
                    <thead><tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th></tr></thead>
                    <tbody>
                        @foreach($peminjamans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->anggota->nama ?? '-' }}</td>
                            <td><b>{{ $p->buku->judul ?? '-' }}</b></td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>{{ $p->tanggal_kembali ?? 'Belum Dikembalikan' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
