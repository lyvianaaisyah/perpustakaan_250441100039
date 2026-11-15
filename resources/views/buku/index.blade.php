<!-- resources/views/buku/index.blade.php -->
@extends('layouts.main')
@section('judul', 'Katalog Buku')
@section('konten')
    <h2 style="color: #1e1605; text-align: center; margin-bottom: 20px;">Daftar Koleksi Tersedia</h2>

    <div id="alert-area"></div>

    <div class="layout-samping">
        {{-- <- modul 5 (Soal 4 & 5): Form Tambah/Ubah Buku HANYA muncul jika punya permission 'kelola data buku' (Admin & Petugas) --}}
        @can('kelola data buku')
        <aside id="kartu-form" class="panel-kiri">
            <h3 id="form-title" class="judul-panel">Tambah Buku Baru</h3>
            <form id="form-buku" novalidate>
                <input type="hidden" id="buku-id">

                <div class="field-vertikal">
                    <label for="buku-kategori_id">Kategori Buku</label>
                    <select id="buku-kategori_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(\App\Models\Kategori::all() as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                    <small class="teks-error" id="err-kategori_id"></small>
                </div>

                <div class="field-vertikal">
                    <label for="buku-judul">Judul Buku</label>
                    <input type="text" id="buku-judul" placeholder="Ketik judul buku...">
                    <small class="teks-error" id="err-judul"></small>
                </div>

                <div class="field-vertikal">
                    <label for="buku-penulis">Nama Penulis</label>
                    <input type="text" id="buku-penulis" placeholder="Contoh: Lyviana Aisyah">
                    <small class="teks-error" id="err-penulis"></small>
                </div>

                <div class="field-vertikal">
                    <label for="buku-tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="buku-tahun_terbit" placeholder="2026">
                    <small class="teks-error" id="err-tahun_terbit"></small>
                </div>

                <div class="grup-tombol-form">
                    <button type="submit" class="tombol-simpan" id="btn-simpan">Simpan Buku</button>
                    <button type="button" class="btn-batal" id="btn-batal" style="display: none;">Batal</button>
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
                        <tr><td colspan="6" style="text-align: center;">Memuat data buku...</td></tr>
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

@push('scripts')
<script>
    // Mengirim status hak akses ke JavaScript agar tombol Ubah/Hapus disembunyikan untuk Siswa
    window.BISA_KELOLA_BUKU = @json(auth()->check() && auth()->user()->can('kelola data buku'));
</script>
<script src="{{ asset('js/buku-client.js') }}"></script>
@endpush                                                