<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'), 
        ];
    }

    // atribut ramah
    public function attributes(): array
    {
        return [
            'kategori_id' => 'kategori',
            'tahun_terbit' => 'tahun terbit',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa format angka.',
            'min' => [
                'string' => ':attribute minimal berisi :min karakter.',
                'numeric' => ':attribute tidak boleh kurang dari :min.',
            ],
            'max' => [
                'string' => ':attribute maksimal berisi :max karakter.',
                'numeric' => ':attribute tidak boleh lebih dari :max.',
            ],
            'kategori_id.exists' => 'Kategori yang dipilih tidak terdaftar di sistem.',
        ];
    }
}