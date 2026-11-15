<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

// custom endpoint
Route::get('buku/rekap/kategori', [BukuController::class, 'rekapKategori']);
Route::get('buku/rekap/peminjaman', [BukuController::class, 'rekapPeminjaman']);

Route::apiResource('buku', BukuController::class)->names('api.buku');