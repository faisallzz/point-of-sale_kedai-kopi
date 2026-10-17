@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-950 to-stone-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg border border-emerald-800/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/90 text-emerald-200 border border-emerald-700 text-xs font-bold uppercase tracking-wider mb-2">
                <span>☕</span> Role 2: Kasir Pelayanan
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-50">Dashboard Kasir</h1>
            <p class="text-xs sm:text-sm text-emerald-200/80 mt-1">
                Selamat bertugas, <span class="font-bold text-white">{{ auth()->user()->nama_pengguna }}</span>. Layani pelanggan dan catat pesanan penjualan minuman kopi.
            </p>
        </div>
        <div>
            <a href="{{ route('transaksi.create') }}" class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                <span>➕</span> Catat Transaksi Baru
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-3xl text-emerald-600">
                🧾
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Transaksi Saya</p>
                <p class="text-3xl font-extrabold text-stone-800 mt-1">{{ $transaksiKasir }}</p>
                <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Riwayat &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-3xl text-amber-600">
                ☕
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Menu Siap Jual</p>
                <p class="text-3xl font-extrabold text-stone-800 mt-1">{{ $totalMenu }}</p>
                <a href="{{ route('menus.index') }}" class="text-xs font-bold text-amber-700 hover:underline">Daftar Menu &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Menu Cepat Siap Saji -->
    <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-stone-800 flex items-center gap-2">
                <span>📋</span> Menu Terdaftar Kedai Kopi
            </h2>
            <a href="{{ route('menus.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua Menu &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @forelse($menus as $menu)
                <div class="p-4 rounded-xl border {{ $menu->is_tersedia ? 'border-stone-200 bg-stone-50 hover:bg-emerald-50/50 hover:border-emerald-300' : 'border-rose-200 bg-rose-50/30 opacity-75' }} transition flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-sm text-stone-800">{{ $menu->nama_menu }}</p>
                            @if(! $menu->is_tersedia)
                                <span class="px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 text-[9px] font-extrabold uppercase">Habis</span>
                            @endif
                        </div>
                        <p class="text-xs font-semibold text-emerald-700 mt-0.5">Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}</p>
                    </div>
                    @if($menu->is_tersedia)
                        <a href="{{ route('transaksi.create') }}" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold transition">
                            Pesan
                        </a>
                    @else
                        <span class="px-2.5 py-1 rounded-lg bg-stone-200 text-stone-400 text-[11px] font-bold cursor-not-allowed">
                            Kosong
                        </span>
                    @endif
                </div>
            @empty
                <p class="text-xs text-stone-500 col-span-3 text-center py-4">Belum ada menu yang terdaftar.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
