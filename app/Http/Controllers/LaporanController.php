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

        return view('laporans.index', compact('laporans'));
    }
}
