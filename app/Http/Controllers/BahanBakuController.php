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
        $bahanBakus = BahanBaku::withCount('komposisiMenus')->orderBy('id', 'asc')->paginate(10);

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
            'harga_satuan' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nama_bahan_baku.required' => 'Nama bahan baku wajib diisi.',
            'nama_bahan_baku.max' => 'Nama bahan baku maksimal 100 karakter.',
            'stok.required' => 'Jumlah stok awal wajib diisi.',
            'stok.numeric' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
            'satuan.required' => 'Satuan ukuran wajib diisi.',
            'satuan.max' => 'Satuan maksimal 20 karakter.',
            'harga_satuan.numeric' => 'Harga beli satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga beli satuan tidak boleh bernilai negatif.',
        ]);

        $validated['harga_satuan'] = $validated['harga_satuan'] ?? 0;

        BahanBaku::create($validated);

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku "'.$validated['nama_bahan_baku'].'" berhasil ditambahkan.');
    }

    /**
     * Form edit sisa stok dan data bahan baku (Khusus Admin).
     */
    public function edit(BahanBaku $bahanBaku): View
    {
        return view('bahan_bakus.edit', compact('bahanBaku'));
    }

    /**
     * Perbarui data bahan baku, harga modal, dan sisa stok (Khusus Admin).
     */
    public function update(Request $request, BahanBaku $bahanBaku): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan_baku' => ['required', 'string', 'max:100'],
            'stok' => ['required', 'numeric', 'min:0'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
        ], [
            'nama_bahan_baku.required' => 'Nama bahan baku wajib diisi.',
            'nama_bahan_baku.max' => 'Nama bahan baku maksimal 100 karakter.',
            'stok.required' => 'Sisa stok bahan baku wajib diisi.',
            'stok.numeric' => 'Sisa stok harus berupa angka.',
            'stok.min' => 'Sisa stok tidak boleh bernilai negatif.',
            'satuan.required' => 'Satuan ukuran wajib diisi.',
            'satuan.max' => 'Satuan maksimal 20 karakter.',
            'harga_satuan.required' => 'Harga beli satuan wajib diisi.',
            'harga_satuan.numeric' => 'Harga beli satuan harus berupa angka.',
            'harga_satuan.min' => 'Harga beli satuan tidak boleh bernilai negatif.',
        ]);

        $bahanBaku->update($validated);

        return redirect()->route('bahan-baku.index')->with('success', 'Data bahan baku "'.$bahanBaku->nama_bahan_baku.'" berhasil diperbarui.');
    }

    /**
     * Hapus bahan baku jika sudah tidak digunakan dalam komposisi menu (Khusus Admin).
     */
    public function destroy(BahanBaku $bahanBaku): RedirectResponse
    {
        // Cegah penghapusan jika bahan baku masih digunakan dalam resep menu
        if ($bahanBaku->komposisiMenus()->exists()) {
            $menuNames = $bahanBaku->menus->pluck('nama_menu')->unique()->join(', ');

            return redirect()->route('bahan-baku.index')
                ->with('error', 'Bahan baku "'.$bahanBaku->nama_bahan_baku.'" tidak dapat dihapus karena masih digunakan dalam resep menu: '.$menuNames.'. Hapus atau ubah komposisi resep menu tersebut terlebih dahulu.');
        }

        $nama = $bahanBaku->nama_bahan_baku;
        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku "'.$nama.'" berhasil dihapus.');
    }
}
