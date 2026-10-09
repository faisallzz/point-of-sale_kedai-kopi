@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-amber-900 to-stone-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg border border-amber-800/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-900/90 text-indigo-200 border border-indigo-700 text-xs font-bold uppercase tracking-wider mb-2">
                <span>🛡️</span> Role 1: Administrator
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-50">Dashboard Administrator</h1>
            <p class="text-xs sm:text-sm text-amber-200/80 mt-1">
                Selamat datang, <span class="font-bold text-white">{{ auth()->user()->nama_pengguna }}</span>. Anda memiliki akses penuh ke manajemen pengguna, master menu, dan bahan baku.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <span>➕</span> Tambah Pengguna
            </a>
            <a href="{{ route('menus.create') }}" class="px-4 py-2.5 rounded-xl bg-stone-800 hover:bg-stone-700 text-amber-100 font-bold text-xs border border-stone-700 shadow-md transition flex items-center gap-2">
                <span>☕</span> Tambah Menu
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl text-indigo-600">
                👥
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Pengguna</p>
                <p class="text-2xl font-extrabold text-stone-800 mt-0.5">{{ $totalUsers }}</p>
                <a href="{{ route('admin.users.index') }}" class="text-[11px] font-bold text-indigo-600 hover:underline">Kelola Pengguna &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-2xl text-amber-600">
                ☕
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Menu Kedai</p>
                <p class="text-2xl font-extrabold text-stone-800 mt-0.5">{{ $totalMenu }}</p>
                <a href="{{ route('menus.index') }}" class="text-[11px] font-bold text-amber-700 hover:underline">Lihat Menu &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl text-emerald-600">
                📦
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Bahan Baku</p>
                <p class="text-2xl font-extrabold text-stone-800 mt-0.5">{{ $totalBahanBaku }}</p>
                <a href="{{ route('bahan-baku.index') }}" class="text-[11px] font-bold text-emerald-700 hover:underline">Kelola Stok &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-2xl text-rose-600">
                🧾
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Transaksi</p>
                <p class="text-2xl font-extrabold text-stone-800 mt-0.5">{{ $totalTransaksi }}</p>
                <a href="{{ route('transaksi.index') }}" class="text-[11px] font-bold text-rose-700 hover:underline">Riwayat Kasir &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Modul Hak Akses Administrator -->
    <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-stone-800 flex items-center gap-2">
            <span>🔑</span> Ringkasan Otoritas & Hak Akses Administrator
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80">
                <span class="font-bold text-stone-700 block mb-1">Management Pengguna:</span>
                <p class="text-stone-500">Membuat akun baru, mengubah nama/peran/sandi, dan menghapus pengguna (dengan proteksi akun sendiri & transaksi).</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80">
                <span class="font-bold text-stone-700 block mb-1">Data Master & Resep:</span>
                <p class="text-stone-500">Menambahkan menu kopi, memantau bahan baku (biji kopi & susu), serta mengonfigurasi komposisi resep.</p>
            </div>
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80">
                <span class="font-bold text-stone-700 block mb-1">Monitoring & Laporan:</span>
                <p class="text-stone-500">Melihat rekap transaksi penjualan kasir dan laporan berkala seluruh operasional kedai kopi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
