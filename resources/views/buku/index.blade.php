<!-- resources/views/buku/index.blade.php -->
@extends('layouts.main')
@section('judul', 'Katalog Buku')
@section('konten')
    <h2 style="color: #1e1605; text-align: center; margin-bottom: 20px;">Daftar Koleksi Tersedia</h2>

    <div id="alert-area"></div>

    <div class="layout-samping">
        @can('kelola-buku')
        <aside id="kartu-form" class="panel-kiri">
            <h3 id="form-title" class="judul-panel">
                {{ isset($bukuEdit) ? 'Ubah Buku #' . $bukuEdit->id : 'Tambah Buku Baru' }}
            </h3>
            <form action="{{ isset($bukuEdit) ? route('buku.update', $bukuEdit->id) : route('buku.store') }}" method="POST">
                @csrf
                @if(isset($bukuEdit))
                    @method('PUT')
                @endif

                <div class="field-vertikal">
                    <label for="buku-kategori_id">Kategori Buku</label>
                    <select id="buku-kategori_id" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\Kategori::all() as $kat)
                            <option value="{{ $kat->id }}" @selected(old('kategori_id', $bukuEdit->kategori_id ?? '') == $kat->id)>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-vertikal">
                    <label for="buku-judul">Judul Buku</label>
                    <input type="text" id="buku-judul" name="judul" value="{{ old('judul', $bukuEdit->judul ?? '') }}" placeholder="Ketik judul buku..." required>
                </div>

                <div class="field-vertikal">
                    <label for="buku-penulis">Nama Penulis</label>
                    <input type="text" id="buku-penulis" name="penulis" value="{{ old('penulis', $bukuEdit->penulis ?? '') }}" placeholder="Contoh: Lyviana Aisyah" required>
                </div>

                <div class="field-vertikal">
                    <label for="buku-tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="buku-tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $bukuEdit->tahun_terbit ?? '') }}" placeholder="2026" required>
                </div>

                <div class="grup-tombol-form">
                    <button type="submit" class="tombol-simpan" id="btn-simpan">
                        {{ isset($bukuEdit) ? 'Simpan Perubahan' : 'Simpan Buku' }}
                    </button>
                    @if(isset($bukuEdit))
                        <a href="{{ route('buku.index') }}" class="btn-batal" style="text-decoration: none; text-align: center;">Batal</a>
                    @endif
                </div>
            </form>
        </aside>
        @endcan

        <section class="panel-kanan">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 class="judul-panel" style="margin: 0;">Tabel Koleksi Buku</h3>
                @can('kelola-buku')
                    <a href="{{ route('buku.create') }}" class="tombol">+ Tambah Buku</a>
                @endcan
            </div>
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
                            <td><b>{{ $item->judul }}</b></td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->tahun_terbit }}</td>
                            <td>
                                <a href="{{ route('buku.show', $item->id) }}" class="btn-aksi btn-ubah">Detail</a>
                                @can('kelola-buku')
                                    <a href="{{ route('buku.edit', $item->id) }}" class="btn-aksi btn-ubah">Ubah</a>
                                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-aksi btn-hapus">Hapus</button>
                                    </form>
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