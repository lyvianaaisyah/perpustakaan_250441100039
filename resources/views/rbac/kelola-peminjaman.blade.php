@extends('layouts.main')
@section('judul', 'Kelola Peminjaman & Pengembalian')
@section('konten')
    <h2 style="color:#3e2800;">Kelola Peminjaman & Pengembalian Buku</h2>
    <div class="layout-samping">
        <aside class="panel-kiri">
            <h3 class="judul-panel">Catat Peminjaman</h3>
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <div class="field-vertikal">
                    <label>Pilih Anggota / Siswa</label>
                    <select name="anggota_id" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($anggotas as $a)
                            <option value="{{ $a->id }}">{{ $a->nama }} ({{ $a->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-vertikal">
                    <label>Pilih Buku</label>
                    <select name="buku_id" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($bukus as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-vertikal">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required>
                </div>
                <button type="submit" class="tombol-simpan" style="width:100%;">Setujui & Catat</button>
            </form>
        </aside>
        <section class="panel-kanan">
            <h3 class="judul-panel">Daftar Transaksi Peminjaman</h3>
            <div class="bungkus-tabel">
                <table class="tabel-api">
                    <thead>
                        <tr><th>No</th><th>Peminjam</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Status / Tgl Kembali</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($peminjamans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><b>{{ $p->anggota->nama ?? '-' }}</b></td>
                            <td>{{ $p->buku->judul ?? '-' }}</td>
                            <td>{{ $p->tanggal_pinjam }}</td>
                            <td>
                                @if($p->tanggal_kembali)
                                    <span class="badge-kat" style="background:#28a745;">Kembali: {{ $p->tanggal_kembali }}</span>
                                @else
                                    <span class="badge-kat" style="background:#d68910;">Sedang Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                @if(!$p->tanggal_kembali)
                                <form action="{{ route('peminjaman.kembali', $p->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn-aksi btn-ubah">Catat Kembali</button>
                                </form>
                                @else
                                    Selesai
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
