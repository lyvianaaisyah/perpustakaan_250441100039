<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;


class BukuController extends Controller
{

    // --- modul1 datanya masih manual menggunakan array ---
    // private $koleksi_buku = [
    //     ['id' => '101', 'judul' => 'Sistem Enterprise', 'penulis' => 'Lyviana Aisyah', 'tahun_terbit' => 2026, 'kategori' => 'Teknologi'],
    //     ['id' => '102', 'judul' => 'Pemrograman Web', 'penulis' => 'Iqbal Hasan', 'tahun_terbit' => 2025, 'kategori' => 'Pendidikan'],
    //     ['id' => '103', 'judul' => 'Desain UI/UX', 'penulis' => 'Safira', 'tahun_terbit' => 2024, 'kategori' => 'Desain'],
    //     ['id' => '104', 'judul' => 'Basis Data SQL', 'penulis' => 'Riski', 'tahun_terbit' => 2023, 'kategori' => 'Teknologi'],
    //     ['id' => '105', 'judul' => 'Cerita Sistem Informasi', 'penulis' => 'Asmi', 'tahun_terbit' => 2026, 'kategori' => 'Fiksi']
    // ];

    public function index()
    {
        $data = Buku::all();

        return view('buku.index', ['buku' => $data]);

        // --- modul1 ---
        // return view('buku.index', ['buku' => $this->koleksi_buku]);
    }

    public function show($id)
    {
        $detail = Buku::find($id);
        
        return view('buku.detail', ['buku_detail' => $detail]);


        // --- modul1 ---
        // $detail = null;
        // foreach ($this->koleksi_buku as $item) {
        //     if ($item['id'] == $id) {
        //         $detail = $item;
        //         break;
        //     }
        // }
        // return view('buku.detail', ['buku_detail' => $detail]);
    }
}