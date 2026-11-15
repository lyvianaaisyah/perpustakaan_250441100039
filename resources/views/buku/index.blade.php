<!-- resources/views/buku/index.blade.php -->
@extends('layouts.main')
@section('judul', 'Katalog Buku')
@section('konten')
    <h2 style="color: #1e1605; text-align: center; margin-bottom: 20px;">Daftar Koleksi Tersedia</h2>

    <div id="alert-area"></div>

    <div class="layout-samping">
        @can('kelola data buku')
        <aside id="kartu-form" class="panel-kiri">
            <h3 id="form-title" class="judul-panel">Tambah Buku Baru</h3>
            <form action="{{ route('buku.store') }}" method="POST">
                @csrf
                <div class="field-vertikal">
                    <label for="buku-kategori_id">Kategori Buku</label>
                    <select id="buku-kategori_id" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\Kategori::all() as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-vertikal">
                    <label for="buku-judul">Judul Buku</label>
                    <input type="text" id="buku-judul" name="judul" placeholder="Ketik judul buku..." required>
                </div>

                <div class="field-vertikal">
                    <label for="buku-penulis">Nama Penulis</label>
                    <input type="text" id="buku-penulis" name="penulis" placeholder="Contoh: Lyviana Aisyah" required>
                </div>

                <div class="field-vertikal">
                    <label for="buku-tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="buku-tahun_terbit" name="tahun_terbit" placeholder="2026" required>
                </div>

                <div class="grup-tombol-form">
                    <button type="submit" class="tombol-simpan" id="btn-simpan">Simpan Buku</button>
                </div>
            </form>
        </aside>
        @endcan

        <section class="panel-kanan">
            <h3 class="judul-panel">Tabel Koleksi Buku</h3>
            <div class="bungkus-tabel">
                <table class="tabel-api">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-buku">
                        @forelse($buku as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge-kat">{{ $item->kategori->nama ?? '-' }}</span></td>
                            {{-- SOAL 1 (A1): Celah Stored XSS tanpa escaping --}}
                            <td><b>{!! $item->judul !!}</b></td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            <td>
                                <a href="{{ route('buku.show', $item->id) }}" class="btn-aksi btn-ubah">Detail</a>
                                @can('kelola data buku')
                                    {{-- SOAL 1 (A3): Celah CSRF hapus buku via GET --}}
                                    <a href="{{ url('/buku/' . $item->id . '/hapus') }}" class="btn-aksi btn-hapus" onclick="return confirm('Hapus buku ini?')">Hapus</a>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align: center;">Belum ada data buku.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="wadah" style="margin-top: 30px;">
        @foreach($buku as $item)
            <x-buku-card :judul="$item['judul']" :penulis="$item['penulis']" :tahun="$item['tahun_terbit']">
                <a href="{{ route('buku.show', $item['id']) }}" class="tombol">Lihat Detail</a>
            </x-buku-card>
        @endforeach
    </div>
@endsection