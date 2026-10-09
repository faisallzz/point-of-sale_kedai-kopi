@extends('layouts.app')

@section('title', 'Laporan Penjualan Kedai Kopi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>📊</span> Otoritas Pemilik & Admin
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Laporan Penjualan & Pendapatan</h1>
            <p class="text-xs text-stone-500 mt-0.5">Rekapitulasi omset dan performa penjualan bulanan kedai kopi.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Periode Rekap</th>
                        <th class="px-5 py-3.5">Total Transaksi</th>
                        <th class="px-5 py-3.5">Total Pendapatan (Omset)</th>
                        <th class="px-5 py-3.5">Rata-rata per Transaksi</th>
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
                            <td class="px-5 py-4 font-semibold text-stone-700">
                                {{ $lap->total_transaksi }} Transaksi
                            </td>
                            <td class="px-5 py-4 font-extrabold text-emerald-700 text-sm">
                                Rp {{ number_format($lap->total_pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-600">
                                Rp {{ number_format($lap->total_transaksi > 0 ? $lap->total_pendapatan / $lap->total_transaksi : 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-stone-400">Belum ada rekapitulasi data penjualan.</td>
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
