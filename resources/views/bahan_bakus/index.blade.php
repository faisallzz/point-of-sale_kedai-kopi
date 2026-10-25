@extends('layouts.app')

@section('title', 'Master Bahan Baku')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>📦</span> Master Data Bahan Baku
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Daftar Stok Bahan Baku</h1>
            <p class="text-xs text-stone-500 mt-0.5">Kelola stok persediaan bahan baku kedai (Biji Kopi, Susu Segar, dsb.) khusus otorisasi Admin.</p>
        </div>
        <div>
            <a href="{{ route('bahan-baku.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
                <span>➕</span> Tambah Bahan Baku
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Nama Bahan Baku</th>
                        <th class="px-5 py-3.5">Sisa Stok</th>
                        <th class="px-5 py-3.5">Satuan Ukuran</th>
                        <th class="px-5 py-3.5">Harga Beli Satuan</th>
                        <th class="px-5 py-3.5">Kondisi Stok</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse($bahanBakus as $index => $b)
                        <tr class="hover:bg-emerald-50/20 transition">
                            <td class="px-5 py-4 font-semibold text-stone-500">
                                {{ $bahanBakus->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-bold text-stone-800 text-sm">
                                📦 {{ $b->nama_bahan_baku }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-stone-900 text-sm">
                                {{ number_format($b->stok, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-600 uppercase">
                                {{ $b->satuan }}
                            </td>
                            <td class="px-5 py-4 font-bold text-stone-700">
                                Rp {{ number_format($b->harga_satuan, 0, ',', '.') }} <span class="text-[10px] text-stone-400 font-normal">/ {{ $b->satuan }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full border {{ $b->badge_kondisi_class }} font-bold text-[10px] uppercase">
                                    {{ $b->status_kondisi_stok }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a
                                        href="{{ route('bahan-baku.edit', $b) }}"
                                        class="px-2.5 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs border border-stone-300 transition"
                                        title="Edit sisa stok atau data bahan baku"
                                    >
                                        ✏️ Edit Stok
                                    </a>

                                    @if(($b->komposisi_menus_count ?? 0) === 0)
                                        <form
                                            action="{{ route('bahan-baku.destroy', $b) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan baku {{ $b->nama_bahan_baku }}? Tindakan ini tidak dapat dibatalkan.');"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition"
                                                title="Hapus bahan baku yang tidak digunakan resep menu"
                                            >
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded text-[11px] font-semibold text-stone-400 bg-stone-100 border border-stone-200 cursor-not-allowed"
                                            title="Bahan baku masih digunakan dalam komposisi resep menu"
                                        >
                                            🔒 Dipakai Resep
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-stone-400">Belum ada bahan baku tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bahanBakus->hasPages())
            <div class="p-4 bg-stone-50 border-t border-stone-200">
                {{ $bahanBakus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection