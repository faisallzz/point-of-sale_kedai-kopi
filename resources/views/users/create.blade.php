@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <h1 class="text-xl font-extrabold text-stone-800">Tambah Pengguna Baru</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftarkan akun staf kedai kopi dengan peran dan hak akses yang sesuai.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Nama Pengguna -->
            <div>
                <label for="nama_pengguna" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                    Nama Pengguna (Username) <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="nama_pengguna"
                    id="nama_pengguna"
                    value="{{ old('nama_pengguna') }}"
                    required
                    placeholder="Contoh: kasir_budi"
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('nama_pengguna') ? 'border-rose-500 bg-rose-50/30' : 'border-stone-300' }} text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                >
                <p class="text-[11px] text-stone-400 mt-1">Nama pengguna harus unik dan tidak boleh sama dengan pengguna lain.</p>
                @error('nama_pengguna')
                    <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-500 bg-rose-50/30' : 'border-stone-300' }} text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                >
                <p class="text-[11px] text-stone-400 mt-1">Gunakan kata sandi yang aman untuk login operasional.</p>
                @error('password')
                    <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Peran / Role -->
            <div>
                <label for="peran" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                    Peran / Hak Akses Pengguna <span class="text-rose-500">*</span>
                </label>
                <select
                    name="peran"
                    id="peran"
                    required
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('peran') ? 'border-rose-500' : 'border-stone-300' }} text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition bg-white"
                >
                    <option value="" disabled {{ old('peran') ? '' : 'selected' }}>-- Pilih Hak Akses --</option>
                    <option value="admin" {{ old('peran') === 'admin' ? 'selected' : '' }}>Admin (Pengelolaan Pengguna, Menu, & Bahan Baku)</option>
                    <option value="kasir" {{ old('peran') === 'kasir' ? 'selected' : '' }}>Kasir (Layanan Penjualan & Pencatatan Transaksi)</option>
                    <option value="pemilik" {{ old('peran') === 'pemilik' ? 'selected' : '' }}>Pemilik (Pengawasan Omset & Laporan Penjualan)</option>
                </select>
                @error('peran')
                    <p class="mt-1 text-xs text-rose-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-md transition"
                >
                    💾 Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
