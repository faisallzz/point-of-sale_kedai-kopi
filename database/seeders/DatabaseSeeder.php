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

        // 2. Data Master Bahan Baku Realistis Standar Kedai Kopi (11 item dengan harga satuan pasar)
        $kopi = BahanBaku::create(['nama_bahan_baku' => 'Biji Kopi Arabika', 'stok' => 5000, 'satuan' => 'gram', 'harga_satuan' => 220]);
        $susu = BahanBaku::create(['nama_bahan_baku' => 'Susu Segar UHT', 'stok' => 10000, 'satuan' => 'ml', 'harga_satuan' => 21]);
        $air = BahanBaku::create(['nama_bahan_baku' => 'Air Mineral Seduh', 'stok' => 30000, 'satuan' => 'ml', 'harga_satuan' => 1]);
        $gulaAren = BahanBaku::create(['nama_bahan_baku' => 'Gula Aren Cair', 'stok' => 3000, 'satuan' => 'ml', 'harga_satuan' => 55]);
        $simpleSyrup = BahanBaku::create(['nama_bahan_baku' => 'Gula Pasir Cair', 'stok' => 3000, 'satuan' => 'ml', 'harga_satuan' => 25]);
        $karamel = BahanBaku::create(['nama_bahan_baku' => 'Sirup Karamel', 'stok' => 2000, 'satuan' => 'ml', 'harga_satuan' => 140]);
        $vanila = BahanBaku::create(['nama_bahan_baku' => 'Sirup Vanila', 'stok' => 2000, 'satuan' => 'ml', 'harga_satuan' => 130]);
        $hazelnut = BahanBaku::create(['nama_bahan_baku' => 'Sirup Hazelnut', 'stok' => 2000, 'satuan' => 'ml', 'harga_satuan' => 130]);
        $sausCokelat = BahanBaku::create(['nama_bahan_baku' => 'Saus Cokelat', 'stok' => 2500, 'satuan' => 'ml', 'harga_satuan' => 150]);
        $bubukKakao = BahanBaku::create(['nama_bahan_baku' => 'Bubuk Kakao', 'stok' => 1000, 'satuan' => 'gram', 'harga_satuan' => 110]);
        $esBatu = BahanBaku::create(['nama_bahan_baku' => 'Es Batu Kristal', 'stok' => 15000, 'satuan' => 'gram', 'harga_satuan' => 2]);

        // 3. Data Master Menu (7 menu kopi)
        $espresso = Menu::create(['nama_menu' => 'Espresso', 'harga_jual' => 18000, 'gambar' => 'images/menu/espresso.jpg']);
        $latte = Menu::create(['nama_menu' => 'Caffe Latte', 'harga_jual' => 24000, 'gambar' => 'images/menu/caffe-latte.jpg']);
        $americano = Menu::create(['nama_menu' => 'Americano', 'harga_jual' => 20000, 'gambar' => 'images/menu/americano.jpg']);
        $cappuccino = Menu::create(['nama_menu' => 'Cappuccino', 'harga_jual' => 25000, 'gambar' => 'images/menu/cappuccino.jpg']);
        $macchiato = Menu::create(['nama_menu' => 'Caramel Macchiato', 'harga_jual' => 28000, 'gambar' => 'images/menu/caramel-macchiato.jpg']);
        $mocha = Menu::create(['nama_menu' => 'Mocha', 'harga_jual' => 27000, 'gambar' => 'images/menu/mocha.jpg']);
        $coldBrew = Menu::create(['nama_menu' => 'Cold Brew', 'harga_jual' => 26000, 'gambar' => 'images/menu/cold-brew.jpg']);

        // 4. Data Pivot Komposisi Resep Realistis Standar Kedai Kopi
        // 1. Espresso (Ekstraksi 18g biji kopi + 40ml air seduh)
        KomposisiMenu::create(['menu_id' => $espresso->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $espresso->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 40]);

        // 2. Caffe Latte (18g biji kopi + 40ml air seduh + 180ml susu segar UHT)
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 40]);
        KomposisiMenu::create(['menu_id' => $latte->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 180]);

        // 3. Americano (18g biji kopi + 200ml air seduh)
        KomposisiMenu::create(['menu_id' => $americano->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $americano->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 200]);

        // 4. Cappuccino (18g biji kopi + 40ml air seduh + 120ml susu segar UHT + 2g bubuk kakao dusting)
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 40]);
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 120]);
        KomposisiMenu::create(['menu_id' => $cappuccino->id, 'bahan_baku_id' => $bubukKakao->id, 'jumlah_penggunaan' => 2]);

        // 5. Caramel Macchiato (18g biji kopi + 40ml air seduh + 150ml susu + 15ml sirup vanila + 20ml sirup karamel)
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 40]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $vanila->id, 'jumlah_penggunaan' => 15]);
        KomposisiMenu::create(['menu_id' => $macchiato->id, 'bahan_baku_id' => $karamel->id, 'jumlah_penggunaan' => 20]);

        // 6. Mocha (18g biji kopi + 40ml air seduh + 150ml susu + 30ml saus cokelat)
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 18]);
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 40]);
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 150]);
        KomposisiMenu::create(['menu_id' => $mocha->id, 'bahan_baku_id' => $sausCokelat->id, 'jumlah_penggunaan' => 30]);

        // 7. Cold Brew (25g biji kopi + 200ml air seduh + 80g es batu kristal)
        KomposisiMenu::create(['menu_id' => $coldBrew->id, 'bahan_baku_id' => $kopi->id, 'jumlah_penggunaan' => 25]);
        KomposisiMenu::create(['menu_id' => $coldBrew->id, 'bahan_baku_id' => $air->id, 'jumlah_penggunaan' => 200]);
        KomposisiMenu::create(['menu_id' => $coldBrew->id, 'bahan_baku_id' => $esBatu->id, 'jumlah_penggunaan' => 80]);

        // 5. Data Rekap Laporan Bulanan (1 baris: Omset Rp 42.000, Pengeluaran HPP Rp 11.780, Laba Rp 30.220)
        $laporan = LaporanPenjualan::create([
            'bulan' => 9,
            'total_pendapatan' => 42000,
            'total_pengeluaran' => 11780,
            'total_keuntungan' => 30220,
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
