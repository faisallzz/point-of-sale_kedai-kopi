<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomposisiMenu extends Model
{
    use HasFactory;

    protected $table = 'komposisi_menus';

    protected $fillable = [
        'menu_id',
        'bahan_baku_id',
        'jumlah_penggunaan',
    ];

    protected $casts = [
        'jumlah_penggunaan' => 'decimal:2',
    ];

    /**
     * Relasi BelongsTo: Merujuk ke menu terkait.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Relasi BelongsTo: Merujuk ke bahan baku yang dipakai.
     */
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}