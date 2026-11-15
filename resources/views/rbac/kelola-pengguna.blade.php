@extends('layouts.main')
@section('judul', 'Kelola Pengguna & Role')
@section('konten')
    <h2 style="color:#3e2800;">Manajemen Akun & Role Pengguna (Khusus Admin)</h2>
    <div class="layout-samping">
        <aside class="panel-kiri">
            <h3 class="judul-panel">Tambah Pengguna</h3>
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="field-vertikal">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Nama pengguna">
                </div>
                <div class="field-vertikal">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@perpustakaan.com">
                </div>
                <div class="field-vertikal">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter">
                </div>
                <div class="field-vertikal">
                    <label>Tetapkan Role</label>
                    <select name="role" required>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}">{{ strtoupper($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="tombol-simpan" style="width:100%;">Simpan Pengguna</button>
            </form>
        </aside>
        <section class="panel-kanan">
            <h3 class="judul-panel">Daftar Pengguna Sistem</h3>
            <div class="bungkus-tabel">
                <table class="tabel-api">
                    <thead>
                        <tr><th>No</th><th>Nama</th><th>Email</th><th>Role Saat Ini</th><th>Ubah Role</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $u)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><b>{{ $u->name }}</b></td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge-kat">{{ strtoupper($u->getRoleNames()->first() ?? '-') }}</span></td>
                            <td>
                                <form action="{{ route('pengguna.role', $u->id) }}" method="POST" style="display:flex; gap:5px;">
                                    @csrf @method('PUT')
                                    <select name="role" style="padding:4px 8px; border-radius:4px; border:1px solid #cbbba0;">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->name }}" @selected($u->hasRole($r->name))>{{ strtoupper($r->name) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn-aksi btn-ubah">Simpan</button>
                                </form>
                            </td>
                            <td>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('pengguna.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-hapus">Hapus</button>
                                </form>
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
