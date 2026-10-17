<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'nama_pelanggan',
        'tanggal_transaksi',
        'total_bayar',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_bayar' => 'decimal:2',
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

    /**
     * Hitung total biaya modal bahan baku (HPP) untuk seluruh pesanan dalam transaksi ini.
     */
    protected function totalBiayaBahan(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $details = $this->relationLoaded('detailTransaksis')
                    ? $this->detailTransaksis
                    : $this->detailTransaksis()->with('menu.komposisiMenus.bahanBaku')->get();

                $totalHpp = 0.0;
                foreach ($details as $detail) {
                    if ($detail->menu) {
                        $totalHpp += ((float) $detail->menu->biaya_hpp * (int) $detail->jumlah);
                    }
                }

                return round($totalHpp, 2);
            }
        );
    }

    /**
     * Hitung total keuntungan kotor yang dihasilkan dari transaksi ini.
     */
    protected function totalKeuntungan(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                return (float) $this->total_bayar - (float) $this->total_biaya_bahan;
            }
        );
    }
}
