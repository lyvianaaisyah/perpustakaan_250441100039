<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // SOAL 1 (A4) Celah IDOR (mengambil data tanpa memeriksa pemilik)
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        return view('peminjaman.detail', compact('peminjaman'));
    }
}