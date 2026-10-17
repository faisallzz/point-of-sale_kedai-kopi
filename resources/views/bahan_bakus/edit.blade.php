@extends('layouts.app')

@section('title', 'Edit Bahan Baku — ' . $bahanBaku->nama_bahan_baku)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>📦</span> Master Bahan Baku
            </div>
            <h1 class="text-xl font-extrabold text-stone-800">Edit Data & Sisa Stok Bahan Baku</h1>
            <p class="text-xs text-stone-500 mt-0.5">Sesuaikan penambahan kiriman baru, stok opname, atau pengurangan susut operasional.</p>
        </div>
        <a href="{{ route('bahan-baku.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                <span>⚠️</span> Terdapat kesalahan input:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('bahan-baku.update', $bahanBaku) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_bahan_baku" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                    Nama Bahan Baku <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nama_bahan_baku" 
                    id="nama_bahan_baku" 
                    required 
                    maxlength="100"
                    value="{{ old('nama_bahan_baku', $bahanBaku->nama_bahan_baku) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stok" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Sisa Stok Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="stok" 
                        id="stok" 
                        required 
                        min="0" 
                        step="0.01" 
                        value="{{ old('stok', (float) $bahanBaku->stok) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Ubah angka untuk menambah / mengurangi stok fisik.</span>
                </div>
                <div>
                    <label for="satuan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Satuan Ukuran <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="satuan" 
                        id="satuan" 
                        required 
                        maxlength="20"
                        value="{{ old('satuan', $bahanBaku->satuan) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Misal: gram, ml, pcs, bungkus.</span>
                </div>
            </div>

            <div>
                <label for="harga_satuan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                    Harga Beli per Satuan (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm font-bold text-stone-400">Rp</span>
                    <input 
                        type="number" 
                        step="any" 
                        name="harga_satuan" 
                        id="harga_satuan" 
                        required 
                        min="0" 
                        value="{{ old('harga_satuan', (float) $bahanBaku->harga_satuan) }}" 
                        class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    >
                </div>
                <span class="text-[11px] text-stone-500 mt-1 block">Harga beli bahan dari supplier per unit satuan (Rp per gram / Rp per ml / Rp per pcs). Digunakan untuk menghitung HPP & laba di laporan owner.</span>
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('bahan-baku.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow-md transition">Simpan Perubahan Stok</button>
            </div>
        </form>
    </div>
</div>
@endsection
