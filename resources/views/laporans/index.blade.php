@extends('layouts.app')

@section('title', 'Laporan Keuangan & Penjualan Kedai Kopi')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>📊</span> Otoritas Pemilik & Admin
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Laporan Keuangan & Laba Rugi</h1>
            <p class="text-xs text-stone-500 mt-0.5">Rekapitulasi omset penjualan, biaya modal bahan baku (HPP), dan laba bersih operasional kedai kopi.</p>
        </div>
    </div>

    <!-- Ringkasan Finansial Eksekutif (4 Kartu Metrik) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-2xl text-amber-600">
                💰
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Total Omset</p>
                <p class="text-xl font-extrabold text-stone-800 mt-0.5">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                <span class="text-[10px] text-stone-400">Akumulasi pendapatan kotor</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-2xl text-rose-600">
                📦
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Biaya Bahan (HPP)</p>
                <p class="text-xl font-extrabold text-rose-700 mt-0.5">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                <span class="text-[10px] text-stone-400">Total belanja modal bahan</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl text-emerald-600">
                📈
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Total Laba Kotor</p>
                <p class="text-xl font-extrabold text-emerald-700 mt-0.5">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</p>
                <span class="text-[10px] text-emerald-600 font-bold">Laba bersih operasional</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl text-indigo-600">
                📊
            </div>
            <div>
                <p class="text-[11px] font-bold text-stone-500 uppercase tracking-wider">Rata-rata Margin Laba</p>
                <p class="text-xl font-extrabold text-indigo-700 mt-0.5">{{ $rataRataMargin }}%</p>
                <span class="text-[10px] text-stone-400">Efisiensi keuntungan menu</span>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Finansial Bulanan -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-sm font-extrabold text-stone-800 flex items-center gap-2">
                <span>📋</span> Rincian Rekapitulasi Laporan Finansial Bulanan
            </h2>
            <span class="text-xs text-stone-400">Diperbarui otomatis berdasarkan transaksi kasir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Periode Rekap</th>
                        <th class="px-5 py-3.5 text-center">Total Transaksi</th>
                        <th class="px-5 py-3.5">Total Omset</th>
                        <th class="px-5 py-3.5">Biaya Bahan (HPP)</th>
                        <th class="px-5 py-3.5">Laba Bersih</th>
                        <th class="px-5 py-3.5 text-center">Margin</th>
                        <th class="px-5 py-3.5">Rata-rata / Trx</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse($laporans as $index => $lap)
                        <tr class="hover:bg-amber-50/20 transition">
                            <td class="px-5 py-4 font-semibold text-stone-500">
                                {{ $laporans->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-bold text-stone-800 text-sm">
                                📅 Bulan ke-{{ $lap->bulan }} ({{ date('Y') }})
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-700 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-stone-100 text-stone-700 font-bold text-xs border border-stone-200">
                                    {{ $lap->total_transaksi }} Trx
                                </span>
                            </td>
                            <td class="px-5 py-4 font-extrabold text-stone-800 text-sm">
                                Rp {{ number_format($lap->total_pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-rose-700 text-sm">
                                Rp {{ number_format($lap->total_pengeluaran, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-emerald-700 text-sm">
                                Rp {{ number_format($lap->total_keuntungan, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px] border border-emerald-200">
                                    {{ $lap->margin_persen }}%
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-600">
                                Rp {{ number_format($lap->total_transaksi > 0 ? $lap->total_pendapatan / $lap->total_transaksi : 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-stone-400">Belum ada rekapitulasi data keuangan penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laporans->hasPages())
            <div class="p-4 bg-stone-50 border-t border-stone-200">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
