<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu (bisa dilihat oleh Admin, Kasir, dan Pemilik).
     */
    public function index(): View
    {
        $menus = Menu::with('komposisiMenus.bahanBaku')
            ->withCount('detailTransaksis')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('menus.index', compact('menus'));
    }

    /**
     * Form tambah menu (Khusus Admin).
     */
    public function create(): View
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan_baku', 'asc')->get();

        return view('menus.create', compact('bahanBakus'));
    }

    /**
     * Simpan menu baru beserta gambar dan komposisi resep bahan (Khusus Admin).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'komposisi' => ['nullable', 'array'],
            'komposisi.*.bahan_baku_id' => ['required_with:komposisi', 'integer', 'exists:bahan_bakus,id'],
            'komposisi.*.jumlah_penggunaan' => ['required_with:komposisi', 'numeric', 'min:0.01'],
        ], [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'nama_menu.max' => 'Nama menu maksimal 100 karakter.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual minimal Rp 0.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diperbolehkan: jpeg, jpg, png, atau webp.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'komposisi.*.bahan_baku_id.exists' => 'Bahan baku yang dipilih tidak valid.',
            'komposisi.*.jumlah_penggunaan.min' => 'Jumlah takaran bahan minimal 0.01.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $uploadDir = public_path('images/menu/uploads');
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = time().'_'.Str::slug($validated['nama_menu']).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $gambarPath = 'images/menu/uploads/'.$fileName;
        }

        DB::transaction(function () use ($validated, $gambarPath) {
            $menu = Menu::create([
                'nama_menu' => $validated['nama_menu'],
                'harga_jual' => $validated['harga_jual'],
                'gambar' => $gambarPath,
            ]);

            if (! empty($validated['komposisi'])) {
                $mergedKomposisi = [];
                foreach ($validated['komposisi'] as $item) {
                    if (empty($item['bahan_baku_id']) || empty($item['jumlah_penggunaan'])) {
                        continue;
                    }
                    $bId = (int) $item['bahan_baku_id'];
                    $qty = (float) $item['jumlah_penggunaan'];
                    $mergedKomposisi[$bId] = ($mergedKomposisi[$bId] ?? 0) + $qty;
                }

                foreach ($mergedKomposisi as $bId => $qty) {
                    $menu->komposisiMenus()->create([
                        'bahan_baku_id' => $bId,
                        'jumlah_penggunaan' => $qty,
                    ]);
                }
            }
        });

        return redirect()->route('menus.index')->with('success', 'Menu baru "'.$validated['nama_menu'].'" berhasil ditambahkan.');
    }

    /**
     * Form edit menu (Khusus Admin).
     */
    public function edit(Menu $menu): View
    {
        $menu->load('komposisiMenus.bahanBaku');
        $bahanBakus = BahanBaku::orderBy('nama_bahan_baku', 'asc')->get();

        return view('menus.edit', compact('menu', 'bahanBakus'));
    }

    /**
     * Perbarui data menu, gambar, dan resep komposisi (Khusus Admin).
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'komposisi' => ['nullable', 'array'],
            'komposisi.*.bahan_baku_id' => ['required_with:komposisi', 'integer', 'exists:bahan_bakus,id'],
            'komposisi.*.jumlah_penggunaan' => ['required_with:komposisi', 'numeric', 'min:0.01'],
        ], [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'nama_menu.max' => 'Nama menu maksimal 100 karakter.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual minimal Rp 0.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diperbolehkan: jpeg, jpg, png, atau webp.',
            'gambar.max' => 'Ukuran file gambar maksimal 2 MB.',
            'komposisi.*.bahan_baku_id.exists' => 'Bahan baku yang dipilih tidak valid.',
            'komposisi.*.jumlah_penggunaan.min' => 'Jumlah takaran bahan minimal 0.01.',
        ]);

        $gambarPath = $menu->gambar;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $uploadDir = public_path('images/menu/uploads');
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = time().'_'.Str::slug($validated['nama_menu']).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);

            // Hapus gambar lama jika merupakan hasil upload kustom
            if (! empty($menu->gambar) && str_starts_with($menu->gambar, 'images/menu/uploads/') && file_exists(public_path($menu->gambar))) {
                @unlink(public_path($menu->gambar));
            }

            $gambarPath = 'images/menu/uploads/'.$fileName;
        }

        DB::transaction(function () use ($menu, $validated, $gambarPath) {
            $menu->update([
                'nama_menu' => $validated['nama_menu'],
                'harga_jual' => $validated['harga_jual'],
                'gambar' => $gambarPath,
            ]);

            // Hapus komposisi lama, masukkan komposisi baru
            $menu->komposisiMenus()->delete();

            if (! empty($validated['komposisi'])) {
                $mergedKomposisi = [];
                foreach ($validated['komposisi'] as $item) {
                    if (empty($item['bahan_baku_id']) || empty($item['jumlah_penggunaan'])) {
                        continue;
                    }
                    $bId = (int) $item['bahan_baku_id'];
                    $qty = (float) $item['jumlah_penggunaan'];
                    $mergedKomposisi[$bId] = ($mergedKomposisi[$bId] ?? 0) + $qty;
                }

                foreach ($mergedKomposisi as $bId => $qty) {
                    $menu->komposisiMenus()->create([
                        'bahan_baku_id' => $bId,
                        'jumlah_penggunaan' => $qty,
                    ]);
                }
            }
        });

        return redirect()->route('menus.index')->with('success', 'Data menu "'.$menu->nama_menu.'" berhasil diperbarui.');
    }

    /**
     * Hapus menu dengan pengecekan riwayat transaksi penjualan (Khusus Admin).
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        // Cegah penghapusan jika menu sudah pernah terjual dalam transaksi penjualan
        if ($menu->detailTransaksis()->exists()) {
            return redirect()->route('menus.index')
                ->with('error', 'Menu "'.$menu->nama_menu.'" tidak dapat dihapus karena sudah memiliki riwayat transaksi penjualan.');
        }

        $nama = $menu->nama_menu;

        DB::transaction(function () use ($menu) {
            // Hapus komposisi resep terlebih dahulu
            $menu->komposisiMenus()->delete();

            // Hapus file gambar jika hasil upload kustom
            if (! empty($menu->gambar) && str_starts_with($menu->gambar, 'images/menu/uploads/') && file_exists(public_path($menu->gambar))) {
                @unlink(public_path($menu->gambar));
            }

            $menu->delete();
        });

        return redirect()->route('menus.index')->with('success', 'Menu "'.$nama.'" berhasil dihapus dari sistem.');
    }
}
