<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BahanBakuController extends Controller
{
    /**
     * Menampilkan daftar bahan baku (Khusus Admin).
     */
    public function index(): View
    {
        $bahanBakus = BahanBaku::orderBy('id', 'asc')->paginate(10);

        return view('bahan_bakus.index', compact('bahanBakus'));
    }

    /**
     * Form tambah bahan baku (Khusus Admin).
     */
    public function create(): View
    {
        return view('bahan_bakus.create');
    }

    /**
     * Simpan bahan baku (Khusus Admin).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan_baku' => ['required', 'string', 'max:100'],
            'stok' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
        ]);

        BahanBaku::create($validated);

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }
}
