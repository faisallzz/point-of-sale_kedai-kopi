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

    <!-- Statistik Cards (4 Metrik Finansial) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-2xl text-amber-600">
                💰
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Total Omset</p>
                <p class="text-xl font-extrabold text-stone-800 mt-0.5">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <a href="{{ route('laporan.index') }}" class="text-[10px] font-bold text-amber-700 hover:underline">Rekap Omset &rarr;</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-2xl text-rose-600">
                📦
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Pengeluaran Bahan</p>
                <p class="text-xl font-extrabold text-rose-700 mt-0.5">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                <span class="text-[10px] text-stone-400">Total modal HPP bahan</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl text-emerald-600">
                📈
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Total Keuntungan</p>
                <p class="text-xl font-extrabold text-emerald-700 mt-0.5">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</p>
                <span class="text-[10px] text-emerald-600 font-bold">Laba bersih kedai</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl text-indigo-600">
                📊
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Rata-rata Margin</p>
                <p class="text-xl font-extrabold text-indigo-700 mt-0.5">{{ $rataRataMargin }}%</p>
                <span class="text-[10px] text-stone-400">{{ $totalTransaksi }} transaksi selesai</span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Laporan Finansial Terbaru -->
    <div class="bg-white rounded-2xl p-6 border border-stone-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-stone-800 flex items-center gap-2">
                <span>📈</span> Ringkasan Laporan Laba Rugi Terkini
            </h2>
            <a href="{{ route('laporan.index') }}" class="text-xs font-bold text-amber-700 hover:underline">Lihat Seluruh Laporan &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-100 text-stone-600 uppercase font-bold border-b border-stone-200">
                    <tr>
                        <th class="px-4 py-3">Periode Bulan</th>
                        <th class="px-4 py-3 text-center">Total Transaksi</th>
                        <th class="px-4 py-3">Total Omset</th>
                        <th class="px-4 py-3">Pengeluaran Bahan (HPP)</th>
                        <th class="px-4 py-3">Laba Bersih</th>
                        <th class="px-4 py-3 text-center">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse($laporans as $lap)
                        <tr class="hover:bg-amber-50/40 transition">
                            <td class="px-4 py-3 font-semibold text-stone-800">Bulan ke-{{ $lap->bulan }} ({{ date('Y') }})</td>
                            <td class="px-4 py-3 font-semibold text-stone-700 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-stone-100 text-stone-700 font-bold text-[11px] border border-stone-200">
                                    {{ $lap->total_transaksi }} Pesanan
                                </span>
                            </td>
                            <td class="px-4 py-3 font-extrabold text-stone-800">Rp {{ number_format($lap->total_pendapatan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-extrabold text-rose-700">Rp {{ number_format($lap->total_pengeluaran, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-extrabold text-emerald-700">Rp {{ number_format($lap->total_keuntungan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] border border-emerald-200">
                                    {{ $lap->margin_persen }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-stone-400">Belum ada rekapitulasi data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
