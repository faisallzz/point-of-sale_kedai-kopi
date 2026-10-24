<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'gambar',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
    ];

    /**
     * Accessor URL gambar menu dengan fallback ke placeholder.
     */
    protected function gambarUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! empty($this->gambar) && file_exists(public_path($this->gambar))) {
                    return asset($this->gambar);
                }

                return asset('images/menu/placeholder.svg');
            }
        );
    }

    /**
     * Hitung perkiraan sisa porsi menu yang dapat dibuat berdasarkan stok bahan baku terendah.
     */
    protected function stokPorsi(): Attribute
    {
        return Attribute::make(
            get: function (): ?int {
                $komposisis = $this->relationLoaded('komposisiMenus')
                    ? $this->komposisiMenus
                    : $this->komposisiMenus()->with('bahanBaku')->get();

                if ($komposisis->isEmpty()) {
                    return null;
                }

                $minPorsi = null;
                foreach ($komposisis as $komposisi) {
                    $bahan = $komposisi->bahanBaku;
                    $takaran = (float) $komposisi->jumlah_penggunaan;

                    if ($takaran <= 0) {
                        continue;
                    }

                    $stok = $bahan ? (float) $bahan->stok : 0;
                    $porsi = (int) floor($stok / $takaran);

                    $minPorsi = ($minPorsi === null) ? $porsi : min($minPorsi, $porsi);
                }

                return $minPorsi ?? 0;
            }
        );
    }

    /**
     * Cek apakah menu aktif dan dapat dijual (seluruh bahan penyusun mencukupi minimal 1 porsi).
     */
    protected function isTersedia(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $porsi = $this->stok_porsi;

                return $porsi === null || $porsi > 0;
            }
        );
    }

    /**
     * Daftar nama bahan baku yang stoknya tidak cukup untuk menu ini.
     */
    protected function bahanHabis(): Attribute
    {
        return Attribute::make(
            get: function (): array {
                $komposisis = $this->relationLoaded('komposisiMenus')
                    ? $this->komposisiMenus
                    : $this->komposisiMenus()->with('bahanBaku')->get();

                $habis = [];
                foreach ($komposisis as $komposisi) {
                    $bahan = $komposisi->bahanBaku;
                    $takaran = (float) $komposisi->jumlah_penggunaan;
                    $stok = $bahan ? (float) $bahan->stok : 0;

                    if ($stok < $takaran) {
                        $habis[] = $bahan?->nama_bahan_baku ?? 'Bahan Baku';
                    }
                }

                return $habis;
            }
        );
    }

    /**
     * Hitung Harga Pokok Penjualan (HPP) modal bahan baku per porsi minuman.
     */
    protected function biayaHpp(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $komposisis = $this->relationLoaded('komposisiMenus')
                    ? $this->komposisiMenus
                    : $this->komposisiMenus()->with('bahanBaku')->get();

                $totalHpp = 0.0;
                foreach ($komposisis as $komposisi) {
                    $bahan = $komposisi->bahanBaku;
                    $takaran = (float) $komposisi->jumlah_penggunaan;
                    $hargaSatuan = $bahan ? (float) $bahan->harga_satuan : 0.0;

                    $totalHpp += ($takaran * $hargaSatuan);
                }

                return round($totalHpp, 2);
            }
        );
    }

    /**
     * Hitung potensi laba kotor per porsi (Harga Jual - Biaya HPP).
     */
    protected function labaKotor(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                return (float) $this->harga_jual - (float) $this->biaya_hpp;
            }
        );
    }

    /**
     * Hitung persentase margin keuntungan per porsi menu.
     */
    protected function marginLaba(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $harga = (float) $this->harga_jual;
                if ($harga <= 0) {
                    return 0.0;
                }

                return round(($this->laba_kotor / $harga) * 100, 1);
            }
        );
    }

    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id');
    }

    public function bahanBakus(): BelongsToMany
    {
        return $this->belongsToMany(BahanBaku::class, 'komposisi_menus', 'menu_id', 'bahan_baku_id')
            ->withPivot('jumlah_penggunaan')
            ->withTimestamps();
    }

    public function komposisiMenus(): HasMany
    {
        return $this->hasMany(KomposisiMenu::class, 'menu_id');
    }
}