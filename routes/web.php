<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerpustakaanRbacController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda Publik (Modul 1)
Route::get('/', function () {
    return view('beranda');
})->name('home');

// Rute yang dilindungi Middleware 'auth' (Wajib Login - Soal 2 & 5)
Route::middleware(['auth'])->group(function () {

    // Pengarah Dashboard otomatis sesuai Role
    Route::get('/dashboard', [PerpustakaanRbacController::class, 'redirectDashboard'])->name('dashboard');

    // 1. Katalog & Detail Buku (Admin, Petugas, Siswa memiliki permission 'lihat daftar buku')
    Route::middleware(['permission:lihat daftar buku'])->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->whereNumber('id')->name('buku.show');
    });

    // Rute khusus kelola buku (Jika Siswa akses URL /kelola-buku langsung -> Ditolak 403 sesuai Skenario 13)
    Route::get('/kelola-buku', [BukuController::class, 'index'])
        ->middleware('permission:kelola data buku')
        ->name('buku.kelola');

    // SOAL 1 MODUL 6: Route Celah A2, A3, dan A4
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
    Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy'])->name('buku.hapus.get');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // 2. Dashboard Admin (Khusus Role Admin - Skenario 1, 2, 13)
    Route::middleware(['role:admin', 'permission:akses dashboard admin'])->group(function () {
        Route::get('/admin/dashboard', [PerpustakaanRbacController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // 3. Kelola Pengguna & Role (Khusus Role Admin - Skenario 4 & 9)
    Route::middleware(['role:admin', 'permission:kelola data pengguna|kelola role pengguna'])->group(function () {
        Route::get('/kelola-pengguna', [PerpustakaanRbacController::class, 'kelolaPengguna'])->name('pengguna.index');
        Route::post('/kelola-pengguna', [PerpustakaanRbacController::class, 'simpanPengguna'])->name('pengguna.store');
        Route::put('/kelola-pengguna/{user}/role', [PerpustakaanRbacController::class, 'ubahRole'])->name('pengguna.role');
        Route::delete('/kelola-pengguna/{user}', [PerpustakaanRbacController::class, 'hapusPengguna'])->name('pengguna.destroy');
    });

    // 4. Dashboard Petugas (Khusus Role Petugas - Skenario 6)
    Route::middleware(['role:petugas', 'permission:akses dashboard petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PerpustakaanRbacController::class, 'petugasDashboard'])->name('petugas.dashboard');
    });

    // 5. Kelola Peminjaman, Pengembalian & Laporan (Admin & Petugas - Skenario 5 & 8)
    Route::middleware(['role:admin|petugas', 'permission:kelola peminjaman|kelola pengembalian'])->group(function () {
        Route::get('/kelola-peminjaman', [PerpustakaanRbacController::class, 'kelolaPeminjaman'])->name('peminjaman.index');
        Route::post('/kelola-peminjaman', [PerpustakaanRbacController::class, 'simpanPeminjaman'])->name('peminjaman.store');
        Route::put('/kelola-peminjaman/{peminjaman}/kembali', [PerpustakaanRbacController::class, 'kembalikanBuku'])->name('peminjaman.kembali');
    });

    Route::middleware(['role:admin|petugas', 'permission:lihat laporan perpustakaan'])->group(function () {
        Route::get('/laporan-perpustakaan', [PerpustakaanRbacController::class, 'laporan'])->name('laporan.index');
    });

    // 6. Riwayat Peminjaman Milik Sendiri & Ajukan Pinjam (Skenario 12 & 14)
    Route::middleware(['permission:melihat riwayat peminjaman sendiri'])->group(function () {
        Route::get('/riwayat-peminjaman/{userId?}', [PerpustakaanRbacController::class, 'riwayatSaya'])->name('riwayat.saya');
    });
    Route::post('/ajukan-peminjaman', [PerpustakaanRbacController::class, 'ajukanPinjam'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('pinjam.ajukan');

    // 7. Profil Milik Sendiri (Skenario 11)
    Route::middleware(['permission:lihat profil sendiri'])->group(function () {
        Route::get('/profil-saya/{userId?}', [PerpustakaanRbacController::class, 'profilSaya'])->name('profil.saya');
    });
    Route::put('/profil-saya', [PerpustakaanRbacController::class, 'updateProfil'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profil.saya.update');

    // Rute profil bawaan Breeze tetap dipertahankan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';