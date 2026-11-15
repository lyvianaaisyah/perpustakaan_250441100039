<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'kategori'         => $this->kategori?->nama ?? '-', 
            'judul_buku'       => $this->judul,                  
            'nama_penulis'     => $this->penulis,                
            'tahun'            => (int) $this->tahun_terbit,     
            'total_peminjaman' => $this->whenCounted('peminjamans'),

           
            'kategori_id'      => $this->kategori_id,
            'judul'            => $this->judul,
            'penulis'          => $this->penulis,
            'tahun_terbit'     => (int) $this->tahun_terbit,
        ];
    }
}