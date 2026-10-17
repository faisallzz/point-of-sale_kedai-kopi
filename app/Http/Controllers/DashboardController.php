<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\LaporanPenjualan;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk role Admin.
     */
    public function admin(): View
    {
        $totalUsers = User::count();
        $totalMenu = Menu::count();
        $totalBahanBaku = BahanBaku::count();
        $totalTransaksi = TransaksiPenjualan::count();

        return view('dashboard.admin', compact('totalUsers', 'totalMenu', 'totalBahanBaku', 'totalTransaksi'));
    }

    /**
     * Dashboard untuk role Kasir.
     */
    public function kasir(): View
    {
        $totalMenu = Menu::count();
        $transaksiKasir = TransaksiPenjualan::where('user_id', auth()->id())->count();
        $menus = Menu::with('komposisiMenus.bahanBaku')->take(6)->get();

        return view('dashboard.kasir', compact('totalMenu', 'transaksiKasir', 'menus'));
    }

    /**
     * Dashboard untuk role Pemilik.
     */
    public function pemilik(): View
    {
        $totalPendapatan = (float) LaporanPenjualan::sum('total_pendapatan');
        $totalPengeluaran = (float) LaporanPenjualan::sum('total_pengeluaran');
        $totalKeuntungan = (float) LaporanPenjualan::sum('total_keuntungan');
        $rataRataMargin = $totalPendapatan > 0 ? round(($totalKeuntungan / $totalPendapatan) * 100, 1) : 0;
        $totalTransaksi = TransaksiPenjualan::count();
        $laporans = LaporanPenjualan::latest()->take(5)->get();

        return view('dashboard.pemilik', compact('totalPendapatan', 'totalPengeluaran', 'totalKeuntungan', 'rataRataMargin', 'totalTransaksi', 'laporans'));
    }
}
