<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Http\Resources\BukuResource; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    // GET /api/buku (Soal 1)
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjamans');

        // Filter kategori
        if ($request->filled('kategori_id') || $request->filled('kategori')) {
            $kategori = $request->input('kategori_id', $request->input('kategori'));
            $query->where('kategori_id', $kategori);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Pencarian judul atau penulis
        if ($request->filled('search') || $request->filled('cari')) {
            $kataKunci = $request->input('search', $request->input('cari'));
            $query->where(function ($q) use ($kataKunci) {
                $q->where('judul', 'like', '%' . $kataKunci . '%')->orWhere('penulis', 'like', '%' . $kataKunci . '%');
            });
        }

        $bukus = $query->latest()->paginate(10)->withQueryString();

        return BukuResource::collection($bukus)->additional([
            'status' => 'success',
            'message' => 'Data buku berhasil diambil',
        ]);
    }

    // POST /api/buku (Soal 2 & Ketentuan Poin 4: validasi dengan $request->validate())
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'judul'        => ['required', 'string', 'min:3', 'max:150', 'regex:/^(?=.*[a-zA-Z]).+$/'],
            'penulis'      => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s\.,\'-]+$/'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
        ], [
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak terdaftar di sistem.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal berisi 3 karakter.',
            'judul.max'             => 'Judul buku maksimal berisi 150 karakter.',
            'judul.regex'           => 'Judul buku harus mengandung huruf, tidak boleh hanya angka.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.min'           => 'Nama penulis minimal berisi 3 karakter.',
            'penulis.max'           => 'Nama penulis maksimal berisi 100 karakter.',
            'penulis.regex'         => 'Nama penulis hanya boleh berisi huruf dan spasi, tidak boleh angka.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa format angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak boleh kurang dari 1900.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh lebih dari tahun ' . date('Y') . '.',
        ]);

        $buku = Buku::create($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan',
            'data' => new BukuResource($buku)
        ], 201);
    }

    // GET /api/buku/{buku} (Soal 3)
    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'peminjamans'])->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Detail buku berhasil diambil',
            'data' => new BukuResource($buku)
        ], 200);
    }

    // PUT /api/buku/{buku} (Soal 3 & Ketentuan Poin 4)
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'kategori_id'  => ['sometimes', 'required', 'exists:kategoris,id'],
            'judul'        => ['sometimes', 'required', 'string', 'min:3', 'max:150', 'regex:/^(?=.*[a-zA-Z]).+$/'],
            'penulis'      => ['sometimes', 'required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s\.,\'-]+$/'],
            'tahun_terbit' => ['sometimes', 'required', 'integer', 'min:1900', 'max:' . date('Y')],
        ], [
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak terdaftar di sistem.',
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.min'             => 'Judul buku minimal berisi 3 karakter.',
            'judul.max'             => 'Judul buku maksimal berisi 150 karakter.',
            'judul.regex'           => 'Judul buku harus mengandung huruf, tidak boleh hanya angka.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.min'           => 'Nama penulis minimal berisi 3 karakter.',
            'penulis.max'           => 'Nama penulis maksimal berisi 100 karakter.',
            'penulis.regex'         => 'Nama penulis hanya boleh berisi huruf dan spasi, tidak boleh angka.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa format angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak boleh kurang dari 1900.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh lebih dari tahun ' . date('Y') . '.',
        ]);

        $buku->update($validated);
        $buku->load('kategori')->loadCount('peminjamans');

        return response()->json([
            'status' => 'success',
            'message' => 'Data buku berhasil diperbarui',
            'data' => new BukuResource($buku)
        ], 200);
    }

    // DELETE /api/buku/{buku} (Soal 4)
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    // Custom Endpoint 1
    public function rekapKategori()
    {
        $rekap = DB::table('kategoris')
            ->leftJoin('bukus', 'kategoris.id', '=', 'bukus.kategori_id')
            ->select('kategoris.nama as kategori', DB::raw('count(bukus.id) as total_buku'))
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap jumlah buku per kategori berhasil diambil',
            'data' => $rekap
        ], 200);
    }

    // Custom Endpoint 2
    public function rekapPeminjaman()
    {
        $rekap = DB::table('bukus')
            ->leftJoin('peminjamans', 'bukus.id', '=', 'peminjamans.buku_id')
            ->select('bukus.judul', DB::raw('count(peminjamans.id) as total_dipinjam'))
            ->groupBy('bukus.id', 'bukus.judul')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap total peminjaman per buku berhasil diambil',
            'data' => $rekap
        ], 200);
    }
}