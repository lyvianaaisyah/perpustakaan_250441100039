@extends('layouts.main')
@section('judul', 'Profil Saya')
@section('konten')
     <div class="detail" style="max-width:550px; margin:0 auto;">
         <h2 style="margin-top:0; color:#3e2800;">Profil Pribadi Saya</h2>
         <p>Role Akun: <span class="badge-kat">{{ strtoupper(auth()->user()->getRoleNames()->first() ?? 'Siswa') }}</span></p>
         <form action="{{ route('profil.saya.update') }}" method="POST">
             @csrf @method('PUT')
             <div class="field-vertikal">
                 <label>Nama Lengkap</label>
                 <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
             </div>
             <div class="field-vertikal">
                 <label>Alamat Email</label>
                 <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
             </div>
             <div class="field-vertikal">
                 <label>Nomor Telepon</label>
                 <input type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}">
             </div>
             <div class="field-vertikal">
                 <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                 <input type="password" name="password" placeholder="Minimal 6 karakter">
             </div>
             @can('ubah profil sendiri')
                 <button type="submit" class="tombol-simpan">Simpan Perubahan Profil</button>
             @endcan
         </form>
     </div>
@endsection
