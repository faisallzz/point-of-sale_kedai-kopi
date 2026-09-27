<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualans';

    protected $fillable = [
        'user_id',
        'laporan_penjualan_id',
        'nomor_transaksi',
        'tanggal_transaksi',
        'total_bayar',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_bayar'       => 'decimal:2',
    ];

    /**
     * Relasi BelongsTo: Transaksi dicatat oleh satu user (kasir).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi BelongsTo: Transaksi direkap dalam satu laporan bulanan (opsional/nullable).
     */
    public function laporanPenjualan(): BelongsTo
    {
        return $this->belongsTo(LaporanPenjualan::class, 'laporan_penjualan_id');
    }

    /**
     * Relasi One-to-Many (Komposisi): 1 transaksi memiliki banyak item rincian pesanan.
     */
    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_penjualan_id');
    }
}