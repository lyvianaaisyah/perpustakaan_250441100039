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

// Rute yang dilindungi Middleware 'auth' (Wajib Login)
Route::middleware(['auth'])->group(function () {

    // Pengarah Dashboard otomatis sesuai Role
    Route::get('/dashboard', [PerpustakaanRbacController::class, 'redirectDashboard'])->name('dashboard');

    // SOAL 3 MODUL 6: Gate 'kelola-buku' (WAJIB DI ATAS /buku/{id})
    Route::middleware(['can:kelola-buku'])->group(function () {
        Route::get('/kelola-buku', [BukuController::class, 'index'])->name('buku.kelola');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // 1. Katalog & Detail Buku
    Route::middleware(['permission:lihat daftar buku'])->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->whereNumber('id')->name('buku.show');
    });

    // SOAL 1 & 2 MODUL 6: Route Pencarian Buku & Detail Peminjaman
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // 2. Dashboard Admin
    Route::middleware(['role:admin', 'permission:akses dashboard admin'])->group(function () {
        Route::get('/admin/dashboard', [PerpustakaanRbacController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // 3. Kelola Pengguna & Role
    Route::middleware(['role:admin', 'permission:kelola data pengguna|kelola role pengguna'])->group(function () {
        Route::get('/kelola-pengguna', [PerpustakaanRbacController::class, 'kelolaPengguna'])->name('pengguna.index');
        Route::post('/kelola-pengguna', [PerpustakaanRbacController::class, 'simpanPengguna'])->name('pengguna.store');
        Route::put('/kelola-pengguna/{user}/role', [PerpustakaanRbacController::class, 'ubahRole'])->name('pengguna.role');
        Route::delete('/kelola-pengguna/{user}', [PerpustakaanRbacController::class, 'hapusPengguna'])->name('pengguna.destroy');
    });

    // 4. Dashboard Petugas
    Route::middleware(['role:petugas', 'permission:akses dashboard petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PerpustakaanRbacController::class, 'petugasDashboard'])->name('petugas.dashboard');
    });

    // 5. Kelola Peminjaman, Pengembalian & Laporan
    Route::middleware(['role:admin|petugas', 'permission:kelola peminjaman|kelola pengembalian'])->group(function () {
        Route::get('/kelola-peminjaman', [PerpustakaanRbacController::class, 'kelolaPeminjaman'])->name('peminjaman.index');
        Route::post('/kelola-peminjaman', [PerpustakaanRbacController::class, 'simpanPeminjaman'])->name('peminjaman.store');
        Route::put('/kelola-peminjaman/{peminjaman}/kembali', [PerpustakaanRbacController::class, 'kembalikanBuku'])->name('peminjaman.kembali');
    });

    Route::middleware(['role:admin|petugas', 'permission:lihat laporan perpustakaan'])->group(function () {
        Route::get('/laporan-perpustakaan', [PerpustakaanRbacController::class, 'laporan'])->name('laporan.index');
    });

    // 6. Riwayat Peminjaman Milik Sendiri & Ajukan Pinjam
    Route::middleware(['permission:melihat riwayat peminjaman sendiri'])->group(function () {
        Route::get('/riwayat-peminjaman/{userId?}', [PerpustakaanRbacController::class, 'riwayatSaya'])->name('riwayat.saya');
    });
    Route::post('/ajukan-peminjaman', [PerpustakaanRbacController::class, 'ajukanPinjam'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('pinjam.ajukan');

    // 7. Profil Milik Sendiri
    Route::middleware(['permission:lihat profil sendiri'])->group(function () {
        Route::get('/profil-saya/{userId?}', [PerpustakaanRbacController::class, 'profilSaya'])->name('profil.saya');
    });
    Route::put('/profil-saya', [PerpustakaanRbacController::class, 'updateProfil'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profil.saya.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';