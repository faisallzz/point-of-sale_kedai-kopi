<?php

namespace Tests\Feature;

use App\Models\BahanBaku;
use App\Models\DetailTransaksi;
use App\Models\KomposisiMenu;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminMenuDanBahanBakuTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kasir;

    private User $pemilik;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nama_pengguna' => 'admin_test',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $this->kasir = User::create([
            'nama_pengguna' => 'kasir_test',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $this->pemilik = User::create([
            'nama_pengguna' => 'pemilik_test',
            'password' => bcrypt('password123'),
            'peran' => 'pemilik',
        ]);
    }

    /**
     * 1. Admin dapat membuat menu baru lengkap dengan komposisi resep dan file gambar.
     */
    public function test_admin_can_create_menu_with_recipes_and_image(): void
    {
        $bijiKopi = BahanBaku::create(['nama_bahan_baku' => 'Biji Kopi Gayo', 'stok' => 2000, 'satuan' => 'gram']);
        $susu = BahanBaku::create(['nama_bahan_baku' => 'Susu Oat', 'stok' => 5000, 'satuan' => 'ml']);

        $fakeImage = UploadedFile::fake()->image('oat_latte.jpg', 600, 450);

        $response = $this->actingAs($this->admin)->post(route('menus.store'), [
            'nama_menu' => 'Oat Milk Latte',
            'harga_jual' => 32000,
            'gambar' => $fakeImage,
            'komposisi' => [
                ['bahan_baku_id' => $bijiKopi->id, 'jumlah_penggunaan' => 18],
                ['bahan_baku_id' => $susu->id, 'jumlah_penggunaan' => 180],
            ],
        ]);

        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('menus', [
            'nama_menu' => 'Oat Milk Latte',
            'harga_jual' => 32000,
        ]);

        $menu = Menu::where('nama_menu', 'Oat Milk Latte')->first();
        $this->assertNotNull($menu);
        $this->assertNotNull($menu->gambar);
        $this->assertTrue(file_exists(public_path($menu->gambar)));

        // Periksa resep tersimpan
        $this->assertCount(2, $menu->komposisiMenus);
        $this->assertDatabaseHas('komposisi_menus', [
            'menu_id' => $menu->id,
            'bahan_baku_id' => $bijiKopi->id,
            'jumlah_penggunaan' => 18,
        ]);

        // Bersihkan berkas uji
        if (file_exists(public_path($menu->gambar))) {
            @unlink(public_path($menu->gambar));
        }
    }

    /**
     * 2. Admin dapat mengedit nama menu, harga, gambar, dan komposisi resep.
     */
    public function test_admin_can_update_menu_name_price_image_and_recipes(): void
    {
        $bijiKopi = BahanBaku::create(['nama_bahan_baku' => 'Arabika Java', 'stok' => 3000, 'satuan' => 'gram']);
        $sirup = BahanBaku::create(['nama_bahan_baku' => 'Sirup Vanila', 'stok' => 1000, 'satuan' => 'ml']);

        $menu = Menu::create([
            'nama_menu' => 'Menu Awal',
            'harga_jual' => 22000,
            'gambar' => null,
        ]);

        KomposisiMenu::create([
            'menu_id' => $menu->id,
            'bahan_baku_id' => $bijiKopi->id,
            'jumlah_penggunaan' => 15,
        ]);

        $newImage = UploadedFile::fake()->image('vanilla_latte.png', 400, 300);

        $response = $this->actingAs($this->admin)->put(route('menus.update', $menu), [
            'nama_menu' => 'Vanilla Sweet Latte',
            'harga_jual' => 27000,
            'gambar' => $newImage,
            'komposisi' => [
                ['bahan_baku_id' => $bijiKopi->id, 'jumlah_penggunaan' => 20],
                ['bahan_baku_id' => $sirup->id, 'jumlah_penggunaan' => 30],
            ],
        ]);

        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('success');

        $menu->refresh();
        $this->assertEquals('Vanilla Sweet Latte', $menu->nama_menu);
        $this->assertEquals(27000, (float) $menu->harga_jual);
        $this->assertNotNull($menu->gambar);
        $this->assertTrue(file_exists(public_path($menu->gambar)));

        // Resep terupdate
        $this->assertCount(2, $menu->komposisiMenus);
        $this->assertDatabaseHas('komposisi_menus', [
            'menu_id' => $menu->id,
            'bahan_baku_id' => $sirup->id,
            'jumlah_penggunaan' => 30,
        ]);

        // Bersihkan berkas uji
        if (file_exists(public_path($menu->gambar))) {
            @unlink(public_path($menu->gambar));
        }
    }

    /**
     * 3. Admin dapat menghapus menu yang belum memiliki riwayat transaksi.
     */
    public function test_admin_can_delete_menu_without_transactions(): void
    {
        $biji = BahanBaku::create(['nama_bahan_baku' => 'Robusta', 'stok' => 1000, 'satuan' => 'gram']);

        $menu = Menu::create([
            'nama_menu' => 'Kopi Salah Input',
            'harga_jual' => 15000,
        ]);

        KomposisiMenu::create([
            'menu_id' => $menu->id,
            'bahan_baku_id' => $biji->id,
            'jumlah_penggunaan' => 10,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('menus.destroy', $menu));

        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
        $this->assertDatabaseMissing('komposisi_menus', ['menu_id' => $menu->id]);
    }

    /**
     * 4. Admin tidak dapat menghapus menu yang sudah pernah terjual dalam transaksi.
     */
    public function test_admin_cannot_delete_menu_with_existing_transactions(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Espresso Terjual',
            'harga_jual' => 18000,
        ]);

        $trx = TransaksiPenjualan::create([
            'user_id' => $this->kasir->id,
            'nomor_transaksi' => 'TRX-TEST-LOCK-01',
            'nama_pelanggan' => 'Pembeli',
            'tanggal_transaksi' => now(),
            'total_bayar' => 18000,
        ]);

        DetailTransaksi::create([
            'transaksi_penjualan_id' => $trx->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'subtotal' => 18000,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('menus.destroy', $menu));

        $response->assertRedirect(route('menus.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    /**
     * 5. Admin dapat mengedit sisa stok bahan baku.
     */
    public function test_admin_can_update_stock_of_bahan_baku(): void
    {
        $bahan = BahanBaku::create([
            'nama_bahan_baku' => 'Gula Aren Cair',
            'stok' => 500,
            'satuan' => 'ml',
            'harga_satuan' => 50,
        ]);

        $response = $this->actingAs($this->admin)->put(route('bahan-baku.update', $bahan), [
            'nama_bahan_baku' => 'Gula Aren Organik',
            'stok' => 1250.5,
            'satuan' => 'ml',
            'harga_satuan' => 60,
        ]);

        $response->assertRedirect(route('bahan-baku.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bahan_bakus', [
            'id' => $bahan->id,
            'nama_bahan_baku' => 'Gula Aren Organik',
            'stok' => 1250.50,
            'satuan' => 'ml',
            'harga_satuan' => 60.00,
        ]);
    }

    /**
     * 6. Admin dapat menghapus bahan baku yang tidak terikat pada resep menu manapun.
     */
    public function test_admin_can_delete_unused_bahan_baku(): void
    {
        $bahan = BahanBaku::create([
            'nama_bahan_baku' => 'Bubuk Kayu Manis Lama',
            'stok' => 200,
            'satuan' => 'gram',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('bahan-baku.destroy', $bahan));

        $response->assertRedirect(route('bahan-baku.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('bahan_bakus', ['id' => $bahan->id]);
    }

    /**
     * 7. Admin tidak dapat menghapus bahan baku yang masih digunakan dalam komposisi resep menu.
     */
    public function test_admin_cannot_delete_bahan_baku_used_in_menu_recipes(): void
    {
        $bahan = BahanBaku::create([
            'nama_bahan_baku' => 'Biji Kopi Aktif',
            'stok' => 5000,
            'satuan' => 'gram',
        ]);

        $menu = Menu::create([
            'nama_menu' => 'Kopi Tubruk',
            'harga_jual' => 12000,
        ]);

        KomposisiMenu::create([
            'menu_id' => $menu->id,
            'bahan_baku_id' => $bahan->id,
            'jumlah_penggunaan' => 20,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('bahan-baku.destroy', $bahan));

        $response->assertRedirect(route('bahan-baku.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('bahan_bakus', ['id' => $bahan->id]);
    }

    /**
     * 8. Pengguna non-admin (kasir, pemilik, tamu) ditolak saat mengakses aksi CRUD menu & bahan baku.
     */
    public function test_non_admin_cannot_access_menu_or_bahan_baku_management(): void
    {
        $menu = Menu::create(['nama_menu' => 'Menu Test', 'harga_jual' => 10000]);
        $bahan = BahanBaku::create(['nama_bahan_baku' => 'Bahan Test', 'stok' => 100, 'satuan' => 'pcs']);

        // Kasir
        $this->actingAs($this->kasir)->get(route('menus.create'))->assertStatus(403);
        $this->actingAs($this->kasir)->get(route('menus.edit', $menu))->assertStatus(403);
        $this->actingAs($this->kasir)->delete(route('menus.destroy', $menu))->assertStatus(403);
        $this->actingAs($this->kasir)->get(route('bahan-baku.edit', $bahan))->assertStatus(403);
        $this->actingAs($this->kasir)->delete(route('bahan-baku.destroy', $bahan))->assertStatus(403);

        // Pemilik
        $this->actingAs($this->pemilik)->get(route('menus.create'))->assertStatus(403);
        $this->actingAs($this->pemilik)->get(route('menus.edit', $menu))->assertStatus(403);
        $this->actingAs($this->pemilik)->delete(route('menus.destroy', $menu))->assertStatus(403);
        $this->actingAs($this->pemilik)->get(route('bahan-baku.edit', $bahan))->assertStatus(403);
        $this->actingAs($this->pemilik)->delete(route('bahan-baku.destroy', $bahan))->assertStatus(403);
    }

    /**
     * 9. Tamu diarahkan ke halaman login saat mencoba mengakses aksi admin.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $menu = Menu::create(['nama_menu' => 'Menu Guest', 'harga_jual' => 10000]);
        $bahan = BahanBaku::create(['nama_bahan_baku' => 'Bahan Guest', 'stok' => 100, 'satuan' => 'pcs']);

        $this->get(route('menus.create'))->assertRedirect(route('login'));
        $this->delete(route('menus.destroy', $menu))->assertRedirect(route('login'));
        $this->delete(route('bahan-baku.destroy', $bahan))->assertRedirect(route('login'));
    }

    /**
     * 10. Kondisi stok bahan baku dievaluasi secara cerdas berdasarkan satuan ukuran (unit-aware).
     */
    public function test_kondisi_stok_bahan_baku_cerdas_berdasarkan_satuan(): void
    {
        // Satuan pcs: 50 pcs harus 'Aman', bukan 'Perlu Ditambah'
        $cup = BahanBaku::create(['nama_bahan_baku' => 'Paper Cup 8oz', 'stok' => 50, 'satuan' => 'pcs']);
        $this->assertEquals('Aman', $cup->status_kondisi_stok);

        // Satuan pcs: 10 pcs harus 'Perlu Ditambah'
        $cupTipis = BahanBaku::create(['nama_bahan_baku' => 'Cup Menipis', 'stok' => 10, 'satuan' => 'pcs']);
        $this->assertEquals('Perlu Ditambah', $cupTipis->status_kondisi_stok);

        // Satuan gram: 2000g 'Aman', 300g 'Perlu Ditambah'
        $kopiAman = BahanBaku::create(['nama_bahan_baku' => 'Kopi Aman', 'stok' => 2000, 'satuan' => 'gram']);
        $this->assertEquals('Aman', $kopiAman->status_kondisi_stok);

        $kopiTipis = BahanBaku::create(['nama_bahan_baku' => 'Kopi Tipis', 'stok' => 300, 'satuan' => 'gram']);
        $this->assertEquals('Perlu Ditambah', $kopiTipis->status_kondisi_stok);

        // Stok 0: 'Habis'
        $habis = BahanBaku::create(['nama_bahan_baku' => 'Bahan Nol', 'stok' => 0, 'satuan' => 'pcs']);
        $this->assertEquals('Habis', $habis->status_kondisi_stok);

        // Tampilan di /bahan-baku
        $response = $this->actingAs($this->admin)->get(route('bahan-baku.index'));
        $response->assertStatus(200);
        $response->assertSee('Aman');
        $response->assertSee('Perlu Ditambah');
        $response->assertSee('Habis');
    }
}