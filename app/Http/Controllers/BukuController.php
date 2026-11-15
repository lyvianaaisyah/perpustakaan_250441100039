<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $data = Buku::with('kategori')->latest()->get();

        return view('buku.index', ['buku' => $data]);
    }

    public function show($id)
    {
        $detail = Buku::with('kategori')->find($id);
        
        return view('buku.detail', ['buku_detail' => $detail]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'min:3', 'max:150'],
            'penulis'      => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil ditambahkan.');
    }

    // SOAL 1 (A2) Celah SQL Injection
    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($hasil);
    }

    // SOAL 1 (A3) Menghapus buku
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil dihapus.');
    }
}