<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'total_pengeluaran',
        'total_keuntungan',
        'total_transaksi',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'total_keuntungan' => 'decimal:2',
        'total_transaksi' => 'integer',
    ];

    /**
     * Accessor persentase margin keuntungan terhadap omset penjualan.
     */
    protected function marginPersen(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $pendapatan = (float) $this->total_pendapatan;
                $keuntungan = (float) $this->total_keuntungan;

                if ($pendapatan <= 0) {
                    return 0.0;
                }

                return round(($keuntungan / $pendapatan) * 100, 1);
            }
        );
    }

    /**
     * Relasi One-to-Many: 1 laporan penjualan bulanan merangkum banyak transaksi.
     */
    public function transaksiPenjualans(): HasMany
    {
        return $this->hasMany(TransaksiPenjualan::class, 'laporan_penjualan_id');
    }
}
