@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>👥</span> Otoritas Admin
            </div>
            <h1 class="text-2xl font-extrabold text-stone-800">Manajemen Pengguna Sistem</h1>
            <p class="text-xs text-stone-500 mt-0.5">Kelola data pengguna, hak akses role (Admin, Kasir, Pemilik), dan kata sandi operasional kedai.</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-2">
                <span>➕</span> Tambah Pengguna Baru
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs divide-y divide-stone-200">
                <thead class="bg-stone-100 text-stone-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Nama Pengguna</th>
                        <th class="px-5 py-3.5">Peran / Role</th>
                        <th class="px-5 py-3.5">Riwayat Transaksi</th>
                        <th class="px-5 py-3.5">Terdaftar Sejak</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-amber-50/30 transition">
                            <td class="px-5 py-4 font-semibold text-stone-500">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-bold text-stone-800 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center font-bold text-xs text-stone-700">
                                    {{ strtoupper(substr($user->nama_pengguna, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-stone-900 font-bold text-sm">{{ $user->nama_pengguna }}</span>
                                    @if(auth()->id() === $user->id)
                                        <span class="ml-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-stone-200 text-stone-700">Akun Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                    {{ $user->peran === 'admin' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                                    {{ $user->peran === 'kasir' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : '' }}
                                    {{ $user->peran === 'pemilik' ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}">
                                    @if($user->peran === 'admin') 🛡️ Admin @elseif($user->peran === 'kasir') ☕ Kasir @else 👑 Pemilik @endif
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-600">
                                @if($user->transaksi_penjualans_count > 0)
                                    <span class="inline-flex items-center gap-1 text-emerald-700 font-bold">
                                        <span>🧾</span> {{ $user->transaksi_penjualans_count }} Transaksi (Terkunci)
                                    </span>
                                @else
                                    <span class="text-stone-400">0 Transaksi</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-stone-500">
                                {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="px-2.5 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs border border-stone-300 transition"
                                    >
                                        ✏️ Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->nama_pengguna }}? Data yang memiliki riwayat transaksi tidak dapat dihapus.');"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition"
                                        >
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-stone-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 bg-stone-50 border-t border-stone-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
