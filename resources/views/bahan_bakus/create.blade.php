@extends('layouts.app')

@section('title', 'Tambah Bahan Baku Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-stone-800">Tambah Bahan Baku Kedai</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftarkan stok bahan baku mentah operasional.</p>
        </div>
        <a href="{{ route('bahan-baku.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('bahan-baku.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="nama_bahan_baku" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Nama Bahan Baku <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_bahan_baku" id="nama_bahan_baku" required placeholder="Contoh: Sirup Karamel" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stok" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Jumlah Stok Awal <span class="text-rose-500">*</span></label>
                    <input type="number" step="any" name="stok" id="stok" required min="0" value="{{ old('stok') }}" placeholder="Contoh: 1000" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div>
                    <label for="satuan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Satuan (gram, ml, pcs) <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" id="satuan" required value="{{ old('satuan') }}" placeholder="Contoh: ml" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <div>
                <label for="harga_satuan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Harga Beli per Satuan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm font-bold text-stone-400">Rp</span>
                    <input type="number" step="any" name="harga_satuan" id="harga_satuan" min="0" value="{{ old('harga_satuan', 0) }}" placeholder="Contoh: 220" class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <p class="text-[11px] text-stone-500 mt-1">Biaya modal per unit satuan (contoh: Rp 220 per gram biji kopi, Rp 21 per ml susu, Rp 1 per ml air seduh).</p>
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('bahan-baku.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow-md transition">Simpan Bahan</button>
            </div>
        </form>
    </div>
</div>
@endsection