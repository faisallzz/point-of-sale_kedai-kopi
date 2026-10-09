@extends('layouts.app')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-stone-800">Tambah Menu Kedai Kopi</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftarkan menu minuman baru ke dalam sistem POS.</p>
        </div>
        <a href="{{ route('menus.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('menus.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="nama_menu" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Menu <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_menu" id="nama_menu" required placeholder="Contoh: Caramel Macchiato" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
            </div>

            <div>
                <label for="harga_jual" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="harga_jual" id="harga_jual" required min="0" step="500" placeholder="Contoh: 25000" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('menus.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-md transition">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>
@endsection
