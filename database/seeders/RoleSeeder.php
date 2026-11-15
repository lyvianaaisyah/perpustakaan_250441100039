<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // <- modul 5 (Soal 4): Daftar 16 Permission sesuai tabel penugasan
        $daftarPermission = [
            'akses dashboard admin',            // 1
            'akses dashboard petugas',          // 2
            'lihat daftar buku',                // 3
            'kelola data buku',                 // 4
            'kelola data pengguna',             // 5
            'kelola role pengguna',             // 6
            'kelola peminjaman',                // 7
            'kelola pengembalian',              // 8
            'mengajukan peminjaman buku',       // 9
            'memproses persetujuan peminjaman', // 10
            'mencatat pengembalian buku',       // 11
            'melihat status peminjaman sendiri',// 12
            'melihat riwayat peminjaman sendiri',// 13
            'lihat laporan perpustakaan',       // 14
            'lihat profil sendiri',             // 15
            'ubah profil sendiri',              // 16
        ];

        foreach ($daftarPermission as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Membuat 3 Role: admin, petugas, siswa
        $admin   = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $petugas = Role::firstOrCreate(['name' => 'petugas', 'guard_name' => 'web']);
        $siswa   = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // 1. Hak akses Role Admin sesuai tabel Soal 4
        $admin->syncPermissions([
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // 2. Hak akses Role Petugas sesuai tabel Soal 4
        $petugas->syncPermissions([
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // 3. Hak akses Role Siswa sesuai tabel Soal 4
        $siswa->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);
    }
}