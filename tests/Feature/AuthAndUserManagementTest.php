<?php

namespace Tests\Feature;

use App\Models\LaporanPenjualan;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Halaman login dapat dibuka oleh tamu (guest).
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('POS KEDAI KOPI');
    }

    /**
     * Pengguna tamu diarahkan ke halaman login jika membuka halaman yang diproteksi.
     */
    public function test_guest_is_redirected_to_login_from_protected_pages(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Login admin berhasil dan dialihkan ke /admin/dashboard.
     */
    public function test_login_successful_for_admin_and_redirects_to_admin_dashboard(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_test',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $response = $this->post('/login', [
            'nama_pengguna' => 'admin_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Login kasir berhasil dan dialihkan ke /kasir/dashboard.
     */
    public function test_login_successful_for_kasir_and_redirects_to_kasir_dashboard(): void
    {
        $kasir = User::create([
            'nama_pengguna' => 'kasir_test',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $response = $this->post('/login', [
            'nama_pengguna' => 'kasir_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($kasir);
        $response->assertRedirect(route('kasir.dashboard'));
    }

    /**
     * Login pemilik berhasil dan dialihkan ke /pemilik/dashboard.
     */
    public function test_login_successful_for_pemilik_and_redirects_to_pemilik_dashboard(): void
    {
        $pemilik = User::create([
            'nama_pengguna' => 'owner_test',
            'password' => bcrypt('password123'),
            'peran' => 'pemilik',
        ]);

        $response = $this->post('/login', [
            'nama_pengguna' => 'owner_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($pemilik);
        $response->assertRedirect(route('pemilik.dashboard'));
    }

    /**
     * Login gagal jika password salah.
     */
    public function test_login_fails_with_invalid_password(): void
    {
        User::create([
            'nama_pengguna' => 'admin_test',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $response = $this->post('/login', [
            'nama_pengguna' => 'admin_test',
            'password' => 'salah_password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('nama_pengguna');
    }

    /**
     * Pengguna terautentikasi dapat melakukan logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'nama_pengguna' => 'kasir_logout',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    /**
     * Role Kasir ditolak (403) saat mencoba mengakses manajemen pengguna.
     */
    public function test_kasir_is_forbidden_from_admin_user_management(): void
    {
        $kasir = User::create([
            'nama_pengguna' => 'kasir_akses',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $response = $this->actingAs($kasir)->get('/admin/users');

        $response->assertStatus(403);
    }

    /**
     * Role Pemilik ditolak (403) saat mencoba mengakses manajemen pengguna.
     */
    public function test_pemilik_is_forbidden_from_admin_user_management(): void
    {
        $pemilik = User::create([
            'nama_pengguna' => 'owner_akses',
            'password' => bcrypt('password123'),
            'peran' => 'pemilik',
        ]);

        $response = $this->actingAs($pemilik)->get('/admin/users');

        $response->assertStatus(403);
    }

    /**
     * Admin berhasil melihat daftar pengguna.
     */
    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_view',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Sistem');
        $response->assertSee('admin_view');
    }

    /**
     * Admin berhasil menambah pengguna baru.
     */
    public function test_admin_can_create_new_user(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_creator',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'nama_pengguna' => 'kasir_baru',
            'password' => 'secret123',
            'peran' => 'kasir',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'nama_pengguna' => 'kasir_baru',
            'peran' => 'kasir',
        ]);
    }

    /**
     * Admin berhasil memperbarui data pengguna.
     */
    public function test_admin_can_update_user(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_editor',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $targetUser = User::create([
            'nama_pengguna' => 'kasir_lama',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'nama_pengguna' => 'kasir_diedit',
            'peran' => 'admin',
            'password' => '', // password kosong tidak mengubah password
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'nama_pengguna' => 'kasir_diedit',
            'peran' => 'admin',
        ]);
    }

    /**
     * Admin berhasil menghapus pengguna yang tidak memiliki riwayat transaksi.
     */
    public function test_admin_can_delete_user_without_transactions(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_deleter',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $userToDelete = User::create([
            'nama_pengguna' => 'kasir_kosong',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$userToDelete->id}");

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    /**
     * Admin tidak boleh menghapus akun miliknya sendiri.
     */
    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_diri',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    /**
     * Hapus pengguna yang memiliki riwayat transaksi ditolak dengan pesan yang ramah.
     */
    public function test_admin_cannot_delete_user_with_existing_transactions(): void
    {
        $admin = User::create([
            'nama_pengguna' => 'admin_fk',
            'password' => bcrypt('password123'),
            'peran' => 'admin',
        ]);

        $kasir = User::create([
            'nama_pengguna' => 'kasir_aktif',
            'password' => bcrypt('password123'),
            'peran' => 'kasir',
        ]);

        $laporan = LaporanPenjualan::create([
            'bulan' => 9,
            'total_pendapatan' => 18000,
            'total_transaksi' => 1,
        ]);

        TransaksiPenjualan::create([
            'user_id' => $kasir->id,
            'laporan_penjualan_id' => $laporan->id,
            'nomor_transaksi' => 'TRX-TEST-001',
            'tanggal_transaksi' => now(),
            'total_bayar' => 18000,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$kasir->id}");

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('riwayat data transaksi penjualan', session('error'));
        $this->assertDatabaseHas('users', [
            'id' => $kasir->id,
        ]);
    }
}
