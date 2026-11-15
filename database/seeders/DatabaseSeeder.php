<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
            RoleSeeder::class, // <- modul 5: seeder 3 role & 16 permission
            UserSeeder::class, // <- modul 5: seeder akun Admin, Petugas, dan Siswa
        ]);
    }
}