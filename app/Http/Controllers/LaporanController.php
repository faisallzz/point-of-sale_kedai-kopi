<?php

namespace App\Http\Controllers;

use App\Models\LaporanPenjualan;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan penjualan (Admin dan Pemilik).
     */
    public function index(): View
    {
        $laporans = LaporanPenjualan::orderBy('bulan', 'desc')->paginate(10);

        $totalOmset = (float) LaporanPenjualan::sum('total_pendapatan');
        $totalPengeluaran = (float) LaporanPenjualan::sum('total_pengeluaran');
        $totalKeuntungan = (float) LaporanPenjualan::sum('total_keuntungan');
        $rataRataMargin = $totalOmset > 0 ? round(($totalKeuntungan / $totalOmset) * 100, 1) : 0;

        return view('laporans.index', compact('laporans', 'totalOmset', 'totalPengeluaran', 'totalKeuntungan', 'rataRataMargin'));
    }
}
