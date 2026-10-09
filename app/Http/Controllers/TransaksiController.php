<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $menus = Menu::all();

        return view('transaksis.create', compact('menus'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $total = $menu->harga_jual * $validated['jumlah'];

        $trx = TransaksiPenjualan::create([
            'user_id' => auth()->id(),
            'laporan_penjualan_id' => null,
            'nomor_transaksi' => 'TRX-'.date('Ymd').'-'.sprintf('%03d', TransaksiPenjualan::count() + 1),
            'tanggal_transaksi' => now(),
            'total_bayar' => $total,
        ]);

        $trx->detailTransaksis()->create([
            'menu_id' => $menu->id,
            'jumlah' => $validated['jumlah'],
            'subtotal' => $total,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi '.$trx->nomor_transaksi.' berhasil dicatat.');
    }
}
