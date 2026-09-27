<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'nama_bahan_baku',
        'stok',
        'satuan',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
    ];

    /**
     * Relasi Many-to-Many: 1 bahan baku dapat digunakan di banyak menu.
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'komposisi_menus', 'bahan_baku_id', 'menu_id')
                    ->withPivot('jumlah_penggunaan')
                    ->withTimestamps();
    }

    /**
     * Relasi One-to-Many ke tabel perantara komposisi menu.
     */
    public function komposisiMenus(): HasMany
    {
        return $this->hasMany(KomposisiMenu::class, 'bahan_baku_id');
    }
}