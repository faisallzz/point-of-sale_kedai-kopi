<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu (bisa dilihat oleh Admin, Kasir, dan Pemilik).
     */
    public function index(): View
    {
        $menus = Menu::with('komposisiMenus.bahanBaku')->orderBy('id', 'asc')->paginate(10);

        return view('menus.index', compact('menus'));
    }

    /**
     * Form tambah menu (Khusus Admin).
     */
    public function create(): View
    {
        return view('menus.create');
    }

    /**
     * Simpan menu baru (Khusus Admin).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ], [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
        ]);

        Menu::create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }
}
