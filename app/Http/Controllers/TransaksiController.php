<?php

namespace App\Http\Controllers;

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
        $menus = Menu::orderBy('id')->get();

        return view('transaksis.create', compact('menus'));
    }

    /**
     * Simpan transaksi baru.
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

        // Ambil harga dari database (server-side calculation)
        $menus = Menu::whereIn('id', array_keys($mergedItems))->get()->keyBy('id');

        // Normalisasi nama pelanggan: kosong atau hanya spasi disimpan sebagai 'Pelanggan Umum'
        $namaPelanggan = trim((string) $request->input('nama_pelanggan'));
        if ($namaPelanggan === '') {
            $namaPelanggan = 'Pelanggan Umum';
        }

        $trx = DB::transaction(function () use ($mergedItems, $menus, $namaPelanggan) {
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

        return redirect()->route('transaksi.index')->with('success', 'Transaksi '.$trx->nomor_transaksi.' berhasil dicatat.');
    }

    /**
     * Hapus data transaksi penjualan (Kasir dan Admin).
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
            $transaksi->detailTransaksis()->delete();
            $transaksi->delete();
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi '.$nomor.' berhasil dihapus.');
    }
}
