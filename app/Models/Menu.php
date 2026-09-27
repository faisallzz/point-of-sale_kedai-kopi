<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'nama_menu',
        'harga_jual',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
    ];

    /**
     * Relasi One-to-Many: 1 menu dapat muncul di banyak baris detail transaksi.
     */
    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id');
    }

    /**
     * Relasi Many-to-Many: 1 menu tersusun atas banyak bahan baku lewat tabel pivot.
     */
    public function bahanBakus(): BelongsToMany
    {
        return $this->belongsToMany(BahanBaku::class, 'komposisi_menus', 'menu_id', 'bahan_baku_id')
                    ->withPivot('jumlah_penggunaan')
                    ->withTimestamps();
    }

    /**
     * Relasi One-to-Many ke tabel perantara komposisi menu.
     */
    public function komposisiMenus(): HasMany
    {
        return $this->hasMany(KomposisiMenu::class, 'menu_id');
    }
}