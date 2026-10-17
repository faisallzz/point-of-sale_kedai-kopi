<?php

namespace Tests\Feature;

use App\Models\BahanBaku;
use App\Models\DetailTransaksi;
use App\Models\KomposisiMenu;
use App\Models\LaporanPenjualan;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanKeuanganTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    private User $pemilik;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nama_pengguna' => 'admin_keuangan',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $this->kasir = User::create([
            'nama_pengguna' => 'kasir_keuangan',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $this->pemilik = User::create([
            'nama_pengguna' => 'pemilik_keuangan',
            'password' => bcrypt('password123'),
            'peran' => 'pemilik',
        ]);
    }

    /**
     * 1. Admin dapat membuat bahan baku baru dengan harga beli satuan.
     */
    public function test_admin_can_create_bahan_baku_with_harga_satuan(): void
    {
        $response = $this->actingAs($this->admin)->post(route('bahan-baku.store'), [
            'nama_bahan_baku' => 'Biji Kopi Arabika Specialty',
            'stok' => 5000,
            'satuan' => 'gram',
            'harga_satuan' => 220,
        ]);

        $response->assertRedirect(route('bahan-baku.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bahan_bakus', [
            'nama_bahan_baku' => 'Biji Kopi Arabika Specialty',
            'stok' => 5000,
            'satuan' => 'gram',
            'harga_satuan' => 220.00,
        ]);
    }

    /**
     * 2. Admin dapat mengubah (edit) harga beli satuan bahan baku kapan saja secara dinamis.
     */
    public function test_admin_can_update_harga_satuan_bahan_baku(): void
    {
        $bahan = BahanBaku::create([
            'nama_bahan_baku' => 'Susu UHT Full Cream',
            'stok' => 10000,
            'satuan' => 'ml',
            'harga_satuan' => 20,
        ]);

        $response = $this->actingAs($this->admin)->put(route('bahan-baku.update', $bahan), [
            'nama_bahan_baku' => 'Susu UHT Full Cream Premium',
            'stok' => 12000,
            'satuan' => 'ml',
            'harga_satuan' => 22.5,
        ]);

        $response->assertRedirect(route('bahan-baku.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bahan_bakus', [
            'id' => $bahan->id,
            'nama_bahan_baku' => 'Susu UHT Full Cream Premium',
            'harga_satuan' => 22.50,
        ]);
    }

    /**
     * 3. Model Menu menghitung HPP (biaya modal bahan per porsi), laba kotor, dan margin laba dengan benar.
     */
    public function test_menu_calculates_hpp_and_profit_margin(): void
    {
        // 18g kopi @ Rp 220 = Rp 3.960
        $kopi = BahanBaku::create([
            'nama_bahan_baku' => 'Biji Arabika',
            'stok' => 1000,
            'satuan' => 'gram',
            'harga_satuan' => 220,
        ]);

        // 40ml air @ Rp 1 = Rp 40
        $air = BahanBaku::create([
            'nama_bahan_baku' => 'Air Seduh',
            'stok' => 5000,
            'satuan' => 'ml',
            'harga_satuan' => 1,
        ]);

        $espresso = Menu::create([
            'nama_menu' => 'Espresso Test',
            'harga_jual' => 18000,
        ]);

        KomposisiMenu::create([
            'menu_id' => $espresso->id,
            'bahan_baku_id' => $kopi->id,
            'jumlah_penggunaan' => 18,
        ]);

        KomposisiMenu::create([
            'menu_id' => $espresso->id,
            'bahan_baku_id' => $air->id,
            'jumlah_penggunaan' => 40,
        ]);

        // Total HPP = 18 * 220 + 40 * 1 = 3960 + 40 = 4000
        $this->assertEquals(4000.0, (float) $espresso->biaya_hpp);

        // Laba Kotor = 18000 - 4000 = 14000
        $this->assertEquals(14000.0, (float) $espresso->laba_kotor);

        // Margin Laba = (14000 / 18000) * 100 = 77.8% (dibulatkan 1 desimal)
        $this->assertEquals(77.8, (float) $espresso->margin_laba);
    }

    /**
     * 4. Pemilik dapat melihat laporan keuangan lengkap (Omset, Pengeluaran HPP, Keuntungan, Margin).
     */
    public function test_pemilik_can_view_laporan_keuangan_with_financial_metrics(): void
    {
        LaporanPenjualan::create([
            'bulan' => 10,
            'total_pendapatan' => 1000000,
            'total_pengeluaran' => 350000,
            'total_keuntungan' => 650000,
            'total_transaksi' => 40,
        ]);

        $response = $this->actingAs($this->pemilik)->get(route('laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan & Laba Rugi', false);
        $response->assertSee('Total Omset');
        $response->assertSee('Biaya Bahan (HPP)');
        $response->assertSee('Total Laba Kotor');
        $response->assertSee('Rata-rata Margin Laba');
        $response->assertSee('Rp 1.000.000');
        $response->assertSee('Rp 350.000');
        $response->assertSee('Rp 650.000');
        $response->assertSee('65%');
    }

    /**
     * 5. Dashboard pemilik menampilkan ringkasan kartu metrik laba dan pengeluaran.
     */
    public function test_pemilik_dashboard_displays_profit_and_expense_cards(): void
    {
        LaporanPenjualan::create([
            'bulan' => 10,
            'total_pendapatan' => 500000,
            'total_pengeluaran' => 150000,
            'total_keuntungan' => 350000,
            'total_transaksi' => 20,
        ]);

        $response = $this->actingAs($this->pemilik)->get(route('pemilik.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pemilik');
        $response->assertSee('Total Omset');
        $response->assertSee('Pengeluaran Bahan');
        $response->assertSee('Total Keuntungan');
        $response->assertSee('Rata-rata Margin');
        $response->assertSee('Rp 500.000');
        $response->assertSee('Rp 150.000');
        $response->assertSee('Rp 350.000');
    }

    /**
     * 6. Perhitungan keuntungan dan biaya bahan pada model TransaksiPenjualan.
     */
    public function test_transaksi_penjualan_calculates_total_biaya_bahan_and_keuntungan(): void
    {
        $kopi = BahanBaku::create([
            'nama_bahan_baku' => 'Biji Arabika Trx',
            'stok' => 500,
            'satuan' => 'gram',
            'harga_satuan' => 200,
        ]);

        $menu = Menu::create([
            'nama_menu' => 'Espresso Single',
            'harga_jual' => 15000,
        ]);

        KomposisiMenu::create([
            'menu_id' => $menu->id,
            'bahan_baku_id' => $kopi->id,
            'jumlah_penggunaan' => 15,
        ]);

        $laporan = LaporanPenjualan::create([
            'bulan' => 10,
            'total_pendapatan' => 0,
            'total_pengeluaran' => 0,
            'total_keuntungan' => 0,
            'total_transaksi' => 0,
        ]);

        $trx = TransaksiPenjualan::create([
            'user_id' => $this->kasir->id,
            'laporan_penjualan_id' => $laporan->id,
            'nomor_transaksi' => 'TRX-TEST-FIN',
            'nama_pelanggan' => 'Pelanggan Profit',
            'tanggal_transaksi' => now(),
            'total_bayar' => 30000,
        ]);

        DetailTransaksi::create([
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'subtotal' => 30000,
        ]);

        // Biaya per cup = 15g * 200 = 3000. Untuk 2 cup = 6000.
        $this->assertEquals(6000.0, (float) $trx->total_biaya_bahan);

        // Keuntungan = 30000 - 6000 = 24000.
        $this->assertEquals(24000.0, (float) $trx->total_keuntungan);
    }
}
