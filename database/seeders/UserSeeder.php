<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name' => 'Lyviana Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles(['admin']);

        // 2. Akun Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password123'),
            ]
        );
        $petugas->syncRoles(['petugas']);

        // 3. Akun Siswa 1 (Utama)
        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa@perpustakaan.com'],
            [
                'name' => 'Siswa Lyviana',
                'password' => Hash::make('password123'),
            ]
        );
        $siswa1->syncRoles(['siswa']);

        $anggota1 = Anggota::firstOrCreate(
            ['email' => $siswa1->email],
            ['nama' => $siswa1->name, 'no_telepon' => '081234567890']
        );

        // 4. Akun Siswa 2 (Untuk uji Skenario 14: akses riwayat siswa lain ditolak)
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            [
                'name' => 'Siswa Lainnya',
                'password' => Hash::make('password123'),
            ]
        );
        $siswa2->syncRoles(['siswa']);

        $anggota2 = Anggota::firstOrCreate(
            ['email' => $siswa2->email],
            ['nama' => $siswa2->name, 'no_telepon' => '089876543210']
        );

        // Buat contoh riwayat peminjaman untuk Siswa 1 dan Siswa 2
        $bukuPertama = Buku::first();
        if ($bukuPertama) {
            Peminjaman::firstOrCreate([
                'anggota_id' => $anggota1->id,
                'buku_id' => $bukuPertama->id,
                'tanggal_pinjam' => now()->subDays(3)->toDateString(),
            ], [
                'tanggal_kembali' => null,
            ]);

            Peminjaman::firstOrCreate([
                'anggota_id' => $anggota2->id,
                'buku_id' => $bukuPertama->id,
                'tanggal_pinjam' => now()->subDays(5)->toDateString(),
            ], [
                'tanggal_kembali' => now()->subDay()->toDateString(),
            ]);
        }
    }
}