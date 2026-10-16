<?php

namespace Tests\Feature;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirTransaksiTest extends TestCase
{
    use RefreshDatabase;

    private User $kasir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kasir = User::create([
            'nama_pengguna' => 'kasir_uji',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);
    }

    /**
     * 1. Kasir bisa membuka /transaksi/create (200) dan melihat nama 7 menu dari seeder.
     */
    public function test_kasir_can_open_create_page_and_see_seven_menus(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->actingAs($this->kasir)->get(route('transaksi.create'));

        $response->assertStatus(200);
        $response->assertSee('Catat Transaksi');
        $response->assertSee('Espresso');
        $response->assertSee('Caffe Latte');
        $response->assertSee('Americano');
        $response->assertSee('Cappuccino');
        $response->assertSee('Caramel Macchiato');
        $response->assertSee('Mocha');
        $response->assertSee('Cold Brew');
    }

    /**
     * 2. Pemilik membuka /transaksi/create ditolak (403). Tamu diarahkan ke /login.
     */
    public function test_pemilik_is_forbidden_and_guest_is_redirected_to_login(): void
    {
        $guestResponse = $this->get(route('transaksi.create'));
        $guestResponse->assertRedirect(route('login'));

        $pemilik = User::create([
            'nama_pengguna' => 'pemilik_uji',
            'password' => bcrypt('password123'),
            'peran' => 'pemilik',
        ]);

        $pemilikResponse = $this->actingAs($pemilik)->get(route('transaksi.create'));
        $pemilikResponse->assertStatus(403);
    }

    /**
     * 3. Kasir menyimpan pesanan 2 menu berbeda dengan jumlah berbeda:
     * header 1 baris, detail 2 baris, total_bayar dan subtotal benar berdasarkan harga database.
     */
    public function test_kasir_stores_two_different_menus_with_correct_subtotal_and_total(): void
    {
        $menuA = Menu::create([
            'nama_menu' => 'Espresso Test',
            'harga_jual' => 18000,
        ]);
        $menuB = Menu::create([
            'nama_menu' => 'Latte Test',
            'harga_jual' => 24000,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Budi Santoso',
            'items' => [
                ['menu_id' => $menuA->id, 'jumlah' => 2],
                ['menu_id' => $menuB->id, 'jumlah' => 3],
            ],
        ]);

        $response->assertRedirect(route('transaksi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transaksi_penjualans', [
            'user_id' => $this->kasir->id,
            'nama_pelanggan' => 'Budi Santoso',
            'total_bayar' => 108000.00, // (18000 * 2) + (24000 * 3) = 36000 + 72000 = 108000
        ]);

        $trx = TransaksiPenjualan::where('nama_pelanggan', 'Budi Santoso')->first();
        $this->assertNotNull($trx);
        $this->assertCount(2, $trx->detailTransaksis);

        $this->assertDatabaseHas('detail_transaksis', [
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menuA->id,
            'jumlah' => 2,
            'subtotal' => 36000.00,
        ]);

        $this->assertDatabaseHas('detail_transaksis', [
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menuB->id,
            'jumlah' => 3,
            'subtotal' => 72000.00,
        ]);
    }

    /**
     * 4. Server mengabaikan harga dari client (kirim field harga palsu; total tetap dari database).
     */
    public function test_server_ignores_client_provided_prices(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Kopi Premium',
            'harga_jual' => 30000,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Client Manipulation Test',
            'total_bayar' => 500, // manipulasi harga total client
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'jumlah' => 2,
                    'harga' => 250, // manipulasi harga per item client
                    'subtotal' => 500,
                ],
            ],
        ]);

        $response->assertRedirect(route('transaksi.index'));

        // Server tetap menghitung dari database: 30000 * 2 = 60000
        $this->assertDatabaseHas('transaksi_penjualans', [
            'nama_pelanggan' => 'Client Manipulation Test',
            'total_bayar' => 60000.00,
        ]);

        $this->assertDatabaseMissing('transaksi_penjualans', [
            'total_bayar' => 500.00,
        ]);

        $this->assertDatabaseHas('detail_transaksis', [
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'subtotal' => 60000.00,
        ]);
    }

    /**
     * 5. nama_pelanggan terisi tersimpan, dan kosong tersimpan sebagai Pelanggan Umum.
     */
    public function test_nama_pelanggan_saved_or_defaults_to_pelanggan_umum(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Kopi Test',
            'harga_jual' => 15000,
        ]);

        // Kasus 1: nama diisi spesifik
        $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Dosen Penguji',
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 1],
            ],
        ]);
        $this->assertDatabaseHas('transaksi_penjualans', [
            'nama_pelanggan' => 'Dosen Penguji',
        ]);

        // Kasus 2: nama kosong string ''
        $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => '',
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 1],
            ],
        ]);

        // Kasus 3: nama hanya whitespace '   '
        $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => '   ',
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 1],
            ],
        ]);

        $this->assertEquals(
            2,
            TransaksiPenjualan::where('nama_pelanggan', 'Pelanggan Umum')->count()
        );
    }

    /**
     * 6. menu_id yang sama dikirim 2 kali digabung menjadi satu baris detail.
     */
    public function test_duplicate_menu_id_items_are_merged_into_one_detail_row(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Espresso Merge',
            'harga_jual' => 20000,
        ]);

        $response = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Pelanggan Double Order',
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 2],
                ['menu_id' => $menu->id, 'jumlah' => 3],
            ],
        ]);

        $response->assertRedirect(route('transaksi.index'));

        $trx = TransaksiPenjualan::where('nama_pelanggan', 'Pelanggan Double Order')->first();
        $this->assertNotNull($trx);

        // Hanya ada 1 baris detail dengan jumlah 2 + 3 = 5, subtotal 100.000
        $this->assertCount(1, $trx->detailTransaksis);
        $this->assertDatabaseHas('detail_transaksis', [
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menu->id,
            'jumlah' => 5,
            'subtotal' => 100000.00,
        ]);
        $this->assertEquals(100000.00, (float) $trx->total_bayar);
    }

    /**
     * 7. Validasi gagal untuk: items kosong, menu_id tidak ada, jumlah 0, jumlah 100, nama_pelanggan > 100 karakter.
     */
    public function test_validation_fails_for_invalid_inputs(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Kopi Validasi',
            'harga_jual' => 20000,
        ]);

        // items kosong
        $r1 = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => 'Pelanggan',
            'items' => [],
        ]);
        $r1->assertSessionHasErrors('items');

        // menu_id tidak ada
        $r2 = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'items' => [
                ['menu_id' => 999999, 'jumlah' => 1],
            ],
        ]);
        $r2->assertSessionHasErrors('items.0.menu_id');

        // jumlah 0 (min 1)
        $r3 = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 0],
            ],
        ]);
        $r3->assertSessionHasErrors('items.0.jumlah');

        // jumlah 100 (max 99)
        $r4 = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 100],
            ],
        ]);
        $r4->assertSessionHasErrors('items.0.jumlah');

        // nama_pelanggan > 100 karakter
        $r5 = $this->actingAs($this->kasir)->post(route('transaksi.store'), [
            'nama_pelanggan' => str_repeat('A', 101),
            'items' => [
                ['menu_id' => $menu->id, 'jumlah' => 1],
            ],
        ]);
        $r5->assertSessionHasErrors('nama_pelanggan');
    }

    /**
     * 8. Riwayat transaksi menampilkan nama pelanggan.
     */
    public function test_transaction_history_displays_customer_name(): void
    {
        $menu = Menu::create(['nama_menu' => 'Kopi Riwayat', 'harga_jual' => 18000]);

        $trx = TransaksiPenjualan::create([
            'user_id' => $this->kasir->id,
            'nomor_transaksi' => 'TRX-TEST-HISTORI-01',
            'nama_pelanggan' => 'Bapak Pembina POS',
            'tanggal_transaksi' => now(),
            'total_bayar' => 18000,
        ]);

        DetailTransaksi::create([
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'subtotal' => 18000,
        ]);

        $response = $this->actingAs($this->kasir)->get(route('transaksi.index'));

        $response->assertStatus(200);
        $response->assertSee('Bapak Pembina POS');
        $response->assertSee('Pelanggan');
    }

    /**
     * 9. Accessor gambar_url mengembalikan placeholder jika gambar null/file tidak ada.
     */
    public function test_menu_gambar_url_accessor_returns_placeholder_when_missing(): void
    {
        // 1. gambar null
        $menuNull = Menu::create(['nama_menu' => 'Menu Null', 'harga_jual' => 10000, 'gambar' => null]);
        $this->assertStringContainsString('images/menu/placeholder.svg', $menuNull->gambar_url);

        // 2. gambar file tidak ada di public_path
        $menuMissing = Menu::create(['nama_menu' => 'Menu Hilang', 'harga_jual' => 10000, 'gambar' => 'images/menu/file_palsu_tidak_ada.jpg']);
        $this->assertStringContainsString('images/menu/placeholder.svg', $menuMissing->gambar_url);

        // 3. gambar file ada
        $menuAda = Menu::create(['nama_menu' => 'Espresso Ada', 'harga_jual' => 18000, 'gambar' => 'images/menu/espresso.jpg']);
        $this->assertStringContainsString('images/menu/espresso.jpg', $menuAda->gambar_url);
    }
}
