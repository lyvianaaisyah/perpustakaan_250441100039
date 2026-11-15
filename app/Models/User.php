<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <- modul 5 (Soal 4): import trait Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // <- modul 5: mengaktifkan fitur Role & Permission

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // <- modul 5 (Soal 1): memastikan password otomatis di-hash
        ];
    }

    // <- modul 5 (Soal 3 & 4): menghubungkan User dengan data Anggota berdasarkan email
    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'email', 'email');
    }
}