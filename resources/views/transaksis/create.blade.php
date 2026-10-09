@extends('layouts.app')

@section('title', 'Catat Transaksi Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-stone-800">Catat Transaksi Kasir</h1>
            <p class="text-xs text-stone-500 mt-0.5">Input pesanan pelanggan kedai kopi dan proses pembayaran.</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Riwayat
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('transaksi.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="menu_id" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Pilih Menu Kopi / Minuman <span class="text-rose-500">*</span></label>
                <select name="menu_id" id="menu_id" required class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-white">
                    <option value="" disabled selected>-- Pilih Menu --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">
                            {{ $menu->nama_menu }} — Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('menu_id')
                    <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jumlah" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">Jumlah Porsi (Cup) <span class="text-rose-500">*</span></label>
                <input type="number" name="jumlah" id="jumlah" required min="1" value="1" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                @error('jumlah')
                    <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('transaksi.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold shadow-md transition">💳 Simpan & Selesaikan Transaksi</button>
            </div>
        </form>
    </div>
</div>
@endsection
