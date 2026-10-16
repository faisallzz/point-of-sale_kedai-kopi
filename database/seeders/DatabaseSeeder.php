<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\DetailTransaksi;
use App\Models\KomposisiMenu;
use App\Models\LaporanPenjualan;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Akun Pengguna (3 Aktor)
        $this->call([UserSeeder::class]);

        // 2. Data Master Bahan Baku (2 item)
        $kopi = BahanBaku::create(['nama_bahan_baku' => 'Biji Kopi Arabika', 'stok' => 5000, 'satuan' => 'gram']);
        $susu = BahanBaku::create(['nama_bahan_baku' => 'Susu Segar UHT', 'stok' => 10000, 'satuan' => 'ml']);

        // 3. Data Master Menu (7 menu kopi)
        $espresso = Menu::create(['nama_menu' => 'Espresso', 'harga_jual' => 18000, 'gambar' => 'images/menu/espresso.jpg']);
        $latte = Menu::create(['nama_menu' => 'Caffe Latte', 'harga_jual' => 24000, 'gambar' => 'images/menu/caffe-latte.jpg']);
        $americano = Menu::create(['nama_menu' => 'Americano', 'harga_jual' => 20000, 'gambar' => 'images/menu/americano.jpg']);
        $cappuccino = Menu::create(['nama_menu' => 'Cappuccino', 'harga_jual' => 25000, 'gambar' => 'images/menu/cappuccino.jpg']);
        $macchiato = Menu::create(['nama_menu' => 'Caramel Macchiato', 'harga_jual' => 28000, 'gambar' => 'images/menu/caramel-macchiato.jpg']);
        $mocha = Menu::create(['nama_menu' => 'Mocha', 'harga_jual' => 27000, 'gambar' => 'images/menu/mocha.jpg']);
        $coldBrew = Menu::create(['nama_menu' => 'Cold Brew', 'harga_jual' => 26000, 'gambar' => 'images/menu/cold-brew.jpg']);

        // 4. Data Pivot Komposisi Resep
        KomposisiMenu::create(['menu_id' => $espresso->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);
        KomposisiMenu::create(['menu_id' => $americano->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 120]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);
        KomposisiMenu::create(['menu_id' => $coldBrew->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 25]);

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
            'nama_pelanggan' => 'Pelanggan Umum',
            'tanggal_transaksi' => now(),
            'total_bayar' => 42000,
        ]);

        // 7. Data Rincian Item Pesanan (2 baris detail)
        DetailTransaksi::create(['transaksi_penjualan_id' => $trx->id, 'menu_id' => $espresso->id, 'jumlah' => 1, 'subtotal' => 18000]);
        DetailTransaksi::create(['transaksi_penjualan_id' => $trx->id, 'menu_id' => $latte->id, 'jumlah' => 1, 'subtotal' => 24000]);
    }
}
