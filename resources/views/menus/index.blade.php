@extends('layouts.app')

@section('title', 'Data Master Menu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>☕</span> Master Data Menu
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Daftar Menu Minuman & Kopi</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftar menu minuman kedai kopi yang dapat dipesan oleh kasir dan dipantau oleh pemilik.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <div>
                <a href="{{ route('menus.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
                    <span>➕</span> Tambah Menu Baru
                </a>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Nama Menu</th>
                        <th class="px-5 py-3.5">Harga Jual</th>
                        <th class="px-5 py-3.5">Komposisi Resep Bahan</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse($menus as $index => $menu)
                        <tr class="hover:bg-amber-50/30 transition">
                            <td class="px-5 py-4 font-semibold text-stone-500">
                                {{ $menus->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-bold text-stone-800 text-sm">
                                ☕ {{ $menu->nama_menu }}
                            </td>
                            <td class="px-5 py-4 font-extrabold text-emerald-700 text-sm">
                                Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-stone-600">
                                @forelse($menu->komposisiMenus as $komp)
                                    <span class="inline-block px-2 py-0.5 bg-stone-100 rounded text-[11px] font-semibold border border-stone-200 mr-1 mb-1">
                                        {{ $komp->bahanBaku->nama_bahan_baku ?? 'Bahan' }} ({{ $komp->jumlah_penggunaan }} {{ $komp->bahanBaku->satuan ?? '' }})
                                    </span>
                                @empty
                                    <span class="text-stone-400 italic">Belum ditentukan</span>
                                @endforelse
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase">
                                    Aktif Dijual
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-stone-400">Belum ada menu yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($menus->hasPages())
            <div class="p-4 bg-stone-50 border-t border-stone-200">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
