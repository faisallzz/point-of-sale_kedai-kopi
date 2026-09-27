<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\KomposisiMenu;
use App\Models\LaporanPenjualan;
use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksi;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Akun Pengguna (3 Aktor)
        $this->call([UserSeeder::class]);

        // 2. Data Master Bahan Baku (2 item)
        $kopi = BahanBaku::create(['nama_bahan_baku' => 'Biji Kopi Arabika', 'stok' => 5000, 'satuan' => 'gram']);
        $susu = BahanBaku::create(['nama_bahan_baku' => 'Susu Segar UHT', 'stok' => 10000, 'satuan' => 'ml']);

        // 3. Data Master Menu (2 menu)
        $espresso = Menu::create(['nama_menu' => 'Espresso', 'harga_jual' => 18000]);
        $latte = Menu::create(['nama_menu' => 'Caffe Latte', 'harga_jual' => 24000]);

        // 4. Data Pivot Komposisi Resep (3 baris pemakaian)
        KomposisiMenu::create(['menu_id' => $espresso->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);

        // 5. Data Rekap Laporan Bulanan (1 baris)
        $laporan = LaporanPenjualan::create([
            'bulan' => 9,
            'total_pendapatan' => 42000,
            'total_transaksi' => 1,
        ]);

        // 6. Data Header Transaksi Kasir (1 transaksi)
        $kasir = User::where('peran', 'kasir')->first();
        $trx = TransaksiPenjualan::create([
            'user_id' => $kasir->id,
            'laporan_penjualan_id' => $laporan->id,
            'nomor_transaksi' => 'TRX-20260927-001',
            'tanggal_transaksi' => now(),
            'total_bayar' => 42000,
        ]);

        // 7. Data Rincian Item Pesanan (2 baris detail)
        DetailTransaksi::create(['transaksi_penjualan_id' => $trx->id, 'menu_id' => $espresso->id, 'jumlah' => 1, 'subtotal' => 18000]);
        DetailTransaksi::create(['transaksi_penjualan_id' => $trx->id, 'menu_id' => $latte->id, 'jumlah' => 1, 'subtotal' => 24000]);
    }
}