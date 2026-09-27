<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';

    protected $fillable = [
        'transaksi_penjualan_id',
        'menu_id',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'jumlah'   => 'integer',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi BelongsTo: Rincian item terikat pada header transaksi penjualan.
     */
    public function transaksiPenjualan(): BelongsTo
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_penjualan_id');
    }

    /**
     * Relasi BelongsTo: Rincian item merujuk ke produk menu yang dipesan.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}