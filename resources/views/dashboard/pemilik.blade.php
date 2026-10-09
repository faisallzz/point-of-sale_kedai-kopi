@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-amber-950 to-stone-900 rounded-2xl p-6 sm:p-8 text-white shadow-lg border border-amber-800/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-900/90 text-amber-200 border border-amber-700 text-xs font-bold uppercase tracking-wider mb-2">
                <span>👑</span> Role 3: Pemilik Kedai (Owner)
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-50">Dashboard Pemilik</h1>
            <p class="text-xs sm:text-sm text-amber-200/80 mt-1">
                Selamat datang, <span class="font-bold text-white">{{ auth()->user()->nama_pengguna }}</span>. Anda memiliki akses pengawasan omset, laporan penjualan, dan daftar menu kedai kopi.
            </p>
        </div>
        <div>
            <a href="{{ route('laporan.index') }}" class="px-5 py-3 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                <span>📊</span> Buka Laporan Penjualan
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-3xl text-amber-600">
                💰
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Pendapatan Kedai</p>
                <p class="text-3xl font-extrabold text-stone-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <a href="{{ route('laporan.index') }}" class="text-xs font-bold text-amber-700 hover:underline">Detail Rekap Bulanan &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-3xl text-indigo-600">
                🧾
            </div>
            <div>
                <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Transaksi Selesai</p>
                <p class="text-3xl font-extrabold text-stone-800 mt-1">{{ $totalTransaksi }}</p>
                <span class="text-xs text-stone-500">Akumulasi seluruh kasir</span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Laporan Terbaru -->
    <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-stone-800 flex items-center gap-2">
                <span>📈</span> Ringkasan Laporan Pendapatan Terkini
            </h2>
            <a href="{{ route('laporan.index') }}" class="text-xs font-bold text-amber-700 hover:underline">Lihat Seluruh Laporan &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-100 text-stone-600 uppercase font-bold border-b border-stone-200">
                    <tr>
                        <th class="px-4 py-3">Periode Bulan</th>
                        <th class="px-4 py-3">Total Transaksi</th>
                        <th class="px-4 py-3">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($laporans as $lap)
                        <tr class="hover:bg-amber-50/40 transition">
                            <td class="px-4 py-3 font-semibold text-stone-800">Bulan ke-{{ $lap->bulan }} ({{ date('Y') }})</td>
                            <td class="px-4 py-3 font-semibold text-stone-700">{{ $lap->total_transaksi }} Pesanan</td>
                            <td class="px-4 py-3 font-extrabold text-emerald-700">Rp {{ number_format($lap->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-stone-400">Belum ada rekapitulasi data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
