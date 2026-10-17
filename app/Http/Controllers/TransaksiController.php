<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Menampilkan riwayat transaksi (Admin dan Kasir).
     */
    public function index(): View
    {
        $transaksis = TransaksiPenjualan::with(['user', 'detailTransaksis.menu'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('transaksis.index', compact('transaksis'));
    }

    /**
     * Form pencatatan transaksi baru (Kasir dan Admin).
     */
    public function create(): View
    {
        $menus = Menu::with('komposisiMenus.bahanBaku')->orderBy('id')->get();

        return view('transaksis.create', compact('menus'));
    }

    /**
     * Simpan transaksi baru dan otomatis potong stok bahan baku sesuai resep.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['nullable', 'string', 'max:100'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'exists:menus,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1', 'max:99'],
        ], [
            'nama_pelanggan.max' => 'Nama pelanggan tidak boleh lebih dari 100 karakter.',
            'items.required' => 'Pesanan masih kosong. Pilih minimal satu menu.',
            'items.array' => 'Format daftar pesanan tidak valid.',
            'items.min' => 'Pesanan masih kosong. Pilih minimal satu menu.',
            'items.*.menu_id.required' => 'Menu pesanan wajib dipilih.',
            'items.*.menu_id.integer' => 'ID menu harus berupa angka integer.',
            'items.*.menu_id.exists' => 'Menu yang dipilih tidak ditemukan dalam daftar menu.',
            'items.*.jumlah.required' => 'Jumlah pesanan wajib diisi.',
            'items.*.jumlah.integer' => 'Jumlah pesanan harus berupa angka integer.',
            'items.*.jumlah.min' => 'Jumlah pesanan minimal 1 porsi.',
            'items.*.jumlah.max' => 'Jumlah pesanan maksimal 99 porsi per item.',
        ]);

        // Gabungkan jumlah jika menu_id yang sama muncul lebih dari sekali
        $mergedItems = [];
        foreach ($validated['items'] as $item) {
            $menuId = (int) $item['menu_id'];
            $jumlah = (int) $item['jumlah'];
            if (isset($mergedItems[$menuId])) {
                $mergedItems[$menuId] += $jumlah;
            } else {
                $mergedItems[$menuId] = $jumlah;
            }
        }

        // Ambil data menu beserta komposisi resep bahan baku
        $menus = Menu::with('komposisiMenus.bahanBaku')->whereIn('id', array_keys($mergedItems))->get()->keyBy('id');

        // Hitung total konsumsi setiap bahan baku untuk seluruh pesanan
        $kebutuhanBahan = [];
        foreach ($mergedItems as $menuId => $jumlah) {
            $menu = $menus->get($menuId);
            if ($menu) {
                foreach ($menu->komposisiMenus as $komposisi) {
                    $bahanId = $komposisi->bahan_baku_id;
                    $takaran = (float) $komposisi->jumlah_penggunaan * $jumlah;
                    $kebutuhanBahan[$bahanId] = ($kebutuhanBahan[$bahanId] ?? 0) + $takaran;
                }
            }
        }

        // Normalisasi nama pelanggan: kosong atau hanya spasi disimpan sebagai 'Pelanggan Umum'
        $namaPelanggan = trim((string) $request->input('nama_pelanggan'));
        if ($namaPelanggan === '') {
            $namaPelanggan = 'Pelanggan Umum';
        }

        try {
            $trx = DB::transaction(function () use ($mergedItems, $menus, $namaPelanggan, $kebutuhanBahan) {
                // Periksa dan kunci baris stok bahan baku untuk mencegah race condition
                if (! empty($kebutuhanBahan)) {
                    $bahanBakus = BahanBaku::whereIn('id', array_keys($kebutuhanBahan))
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($kebutuhanBahan as $bahanId => $totalDibutuhkan) {
                        $bahan = $bahanBakus->get($bahanId);
                        $stokTersedia = $bahan ? (float) $bahan->stok : 0;

                        if (! $bahan || $stokTersedia < $totalDibutuhkan) {
                            $namaBahan = $bahan ? $bahan->nama_bahan_baku : 'Bahan Baku';
                            $satuan = $bahan ? $bahan->satuan : '';

                            throw new \RuntimeException(
                                "Stok bahan baku '{$namaBahan}' tidak mencukupi untuk memproses pesanan ini. (Tersedia: {$stokTersedia} {$satuan}, Dibutuhkan: {$totalDibutuhkan} {$satuan})"
                            );
                        }

                        // Kurangi stok bahan baku secara otomatis
                        $bahan->decrement('stok', $totalDibutuhkan);
                    }
                }

                $totalBayar = 0;
                $details = [];

                foreach ($mergedItems as $menuId => $jumlah) {
                    $menu = $menus->get($menuId);
                    $subtotal = $menu->harga_jual * $jumlah;
                    $totalBayar += $subtotal;

                    $details[] = [
                        'menu_id' => $menuId,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal,
                    ];
                }

                $nomorTransaksi = 'TRX-'.date('Ymd').'-'.sprintf('%03d', TransaksiPenjualan::count() + 1);

                $trx = TransaksiPenjualan::create([
                    'user_id' => auth()->id(),
                    'laporan_penjualan_id' => null,
                    'nomor_transaksi' => $nomorTransaksi,
                    'nama_pelanggan' => $namaPelanggan,
                    'tanggal_transaksi' => now(),
                    'total_bayar' => $totalBayar,
                ]);

                foreach ($details as $detail) {
                    $trx->detailTransaksis()->create($detail);
                }

                return $trx;
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi '.$trx->nomor_transaksi.' berhasil dicatat.');
    }

    /**
     * Hapus data transaksi penjualan (Kasir dan Admin) dan kembalikan stok bahan baku.
     */
    public function destroy(TransaksiPenjualan $transaksi): RedirectResponse
    {
        // Cegah penghapusan jika transaksi sudah masuk rekap laporan bulanan
        if ($transaksi->laporan_penjualan_id !== null) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi '.$transaksi->nomor_transaksi.' tidak dapat dihapus karena sudah masuk dalam rekap laporan bulanan.');
        }

        $nomor = $transaksi->nomor_transaksi;

        DB::transaction(function () use ($transaksi) {
            // Muat rincian pesanan beserta komposisi resep bahan baku
            $transaksi->load('detailTransaksis.menu.komposisiMenus');

            // Kembalikan stok bahan baku yang pernah terpakai oleh menu yang dibatalkan
            foreach ($transaksi->detailTransaksis as $detail) {
                if ($detail->menu) {
                    foreach ($detail->menu->komposisiMenus as $komposisi) {
                        $jumlahDikembalikan = (float) $komposisi->jumlah_penggunaan * (int) $detail->jumlah;
                        BahanBaku::where('id', $komposisi->bahan_baku_id)->increment('stok', $jumlahDikembalikan);
                    }
                }
            }

            $transaksi->detailTransaksis()->delete();
            $transaksi->delete();
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi '.$nomor.' berhasil dihapus dan stok bahan baku telah dikembalikan.');
    }
}
