@extends('layouts.app')

@section('title', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>🧾</span> Layanan Penjualan
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Riwayat Transaksi Penjualan</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftar transaksi pesanan minuman yang dicatat oleh kasir kedai kopi.</p>
        </div>
        <div>
            <a href="{{ route('transaksi.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
                <span>➕</span> Catat Transaksi Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">No. Transaksi</th>
                        <th class="px-5 py-3.5">Kasir Petugas</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Rincian Item</th>
                        <th class="px-5 py-3.5">Total Bayar</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse($transaksis as $index => $trx)
                        <tr class="hover:bg-emerald-50/20 transition">
                            <td class="px-5 py-4 font-semibold text-stone-500">
                                {{ $transaksis->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-stone-900 text-xs">
                                {{ $trx->nomor_transaksi }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-700">
                                ☕ {{ $trx->user->nama_pengguna ?? 'Kasir #' . $trx->user_id }}
                            </td>
                            <td class="px-5 py-4 font-medium text-stone-700">
                                {{ $trx->nama_pelanggan ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-stone-500">
                                {{ $trx->tanggal_transaksi ? \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4 text-stone-600">
                                @forelse($trx->detailTransaksis as $dt)
                                    <span class="inline-block px-2 py-0.5 bg-stone-100 rounded text-[11px] font-semibold border border-stone-200 mr-1 mb-1">
                                        {{ $dt->menu->nama_menu ?? 'Item' }} ({{ $dt->jumlah }}x)
                                    </span>
                                @empty
                                    <span class="text-stone-400 italic">-</span>
                                @endforelse
                            </td>
                            <td class="px-5 py-4 font-extrabold text-emerald-700 text-sm">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($trx->laporan_penjualan_id === null)
                                    <form
                                        id="delete-form-{{ $trx->id }}"
                                        method="POST"
                                        action="{{ route('transaksi.destroy', $trx) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi {{ $trx->nomor_transaksi }}? Tindakan ini tidak dapat dibatalkan.');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition inline-flex items-center gap-1 shadow-xs"
                                            title="Hapus transaksi jika ada kesalahan input"
                                        >
                                            <span>🗑️</span> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[11px] font-semibold text-stone-400 bg-stone-100 border border-stone-200" title="Transaksi telah direkap dalam laporan penjualan dan tidak dapat dihapus">
                                        <span>🔒</span> Terkunci
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-stone-400">Belum ada transaksi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksis->hasPages())
            <div class="p-4 bg-stone-50 border-t border-stone-200">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
