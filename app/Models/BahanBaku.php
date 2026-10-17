<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'harga_satuan',
    ];

    protected $casts = [
        'stok' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    /**
     * Accessor label kondisi stok cerdas berdasarkan satuan ukuran.
     */
    protected function statusKondisiStok(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->stok <= 0) {
                    return 'Habis';
                }

                $satuan = strtolower(trim((string) $this->satuan));

                // Satuan cacahan / unit bijian
                if (in_array($satuan, ['pcs', 'buah', 'botol', 'pack', 'box', 'kaleng', 'cup', 'porsi', 'lembar'], true)) {
                    return $this->stok < 20 ? 'Perlu Ditambah' : 'Aman';
                }

                // Satuan besar (kg / liter)
                if (in_array($satuan, ['kg', 'kilogram', 'liter', 'l'], true)) {
                    return $this->stok < 1 ? 'Perlu Ditambah' : 'Aman';
                }

                // Satuan volume / berat kecil (gram / ml)
                if (in_array($satuan, ['gram', 'g', 'ml', 'mililiter'], true)) {
                    return $this->stok < 500 ? 'Perlu Ditambah' : 'Aman';
                }

                // Satuan default lainnya
                return $this->stok < 50 ? 'Perlu Ditambah' : 'Aman';
            }
        );
    }

    /**
     * Accessor class warna badge status kondisi stok.
     */
    protected function badgeKondisiClass(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->status_kondisi_stok) {
                    'Habis' => 'bg-rose-100 text-rose-800 border-rose-200',
                    'Perlu Ditambah' => 'bg-amber-100 text-amber-800 border-amber-200',
                    default => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                };
            }
        );
    }

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
