<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualans';

    protected $fillable = [
        'bulan',
        'total_pendapatan',
        'total_transaksi',
    ];

    protected $casts = [
        'bulan'            => 'integer',
        'total_pendapatan' => 'decimal:2',
        'total_transaksi'  => 'integer',
    ];

    /**
     * Relasi One-to-Many: 1 laporan penjualan bulanan merangkum banyak transaksi.
     */
    public function transaksiPenjualans(): HasMany
    {
        return $this->hasMany(TransaksiPenjualan::class, 'laporan_penjualan_id');
    }
}