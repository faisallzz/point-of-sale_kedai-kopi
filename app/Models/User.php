<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel di basis data.
     */
    protected $table = 'users';

    /**
     * Atribut yang dapat diisi secara massal (mass-assignable).
     * Kolom email ditiadakan sesuai atribut Class Diagram.
     */
    protected $fillable = [
        'nama_pengguna',
        'password',
        'peran',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi model (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data bawaan.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi One-to-Many: 1 pengguna/kasir dapat mencatat banyak transaksi penjualan.
     */
    public function transaksiPenjualans(): HasMany
    {
        return $this->hasMany(TransaksiPenjualan::class, 'user_id');
    }
}