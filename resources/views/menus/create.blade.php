@extends('layouts.app')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>☕</span> Master Menu
            </div>
            <h1 class="text-xl font-extrabold text-stone-800">Tambah Menu Kedai Kopi</h1>
            <p class="text-xs text-stone-500 mt-0.5">Daftarkan menu minuman baru lengkap dengan foto dan komposisi takaran bahan baku.</p>
        </div>
        <a href="{{ route('menus.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                <span>⚠️</span> Terdapat kesalahan input:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Informasi Dasar Menu -->
            <div class="space-y-4">
                <h2 class="text-xs font-extrabold text-stone-700 uppercase tracking-wider pb-2 border-b border-stone-200">
                    1. Informasi Dasar Produk
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_menu" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Nama Menu <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="nama_menu" 
                            id="nama_menu" 
                            required 
                            maxlength="100"
                            value="{{ old('nama_menu') }}"
                            placeholder="Contoh: Hazelnut Latte" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                        >
                    </div>

                    <div>
                        <label for="harga_jual" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                            Harga Jual (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="harga_jual" 
                            id="harga_jual" 
                            required 
                            min="0" 
                            step="500" 
                            value="{{ old('harga_jual') }}"
                            placeholder="Contoh: 28000" 
                            class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                        >
                    </div>
                </div>

                <div>
                    <label for="gambar" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">
                        Foto Produk Minuman (Opsional)
                    </label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl border border-stone-200 bg-stone-50">
                        <div class="w-24 h-24 rounded-xl bg-stone-200 overflow-hidden flex-shrink-0 border border-stone-300 flex items-center justify-center">
                            <img id="image-preview" src="{{ asset('images/menu/placeholder.svg') }}" alt="Preview Foto Menu" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 space-y-1">
                            <input 
                                type="file" 
                                name="gambar" 
                                id="gambar" 
                                accept="image/jpeg,image/png,image/webp"
                                onchange="previewImage(this)"
                                class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-500 file:cursor-pointer cursor-pointer"
                            >
                            <p class="text-[11px] text-stone-400">Format: JPG, PNG, atau WebP. Maksimal ukuran berkas 2 MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Komposisi Resep Bahan Baku -->
            <div class="space-y-4 pt-4 border-t border-stone-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-extrabold text-stone-700 uppercase tracking-wider">
                            2. Komposisi Resep Bahan Baku
                        </h2>
                        <p class="text-[11px] text-stone-500">Tentukan bahan baku mentah yang diperlukan untuk meracik 1 porsi menu ini.</p>
                    </div>
                    <button 
                        type="button" 
                        onclick="tambahBarisResep()"
                        class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5"
                    >
                        <span>➕</span> Tambah Bahan
                    </button>
                </div>

                <div id="container-resep" class="space-y-3">
                    <!-- Baris resep dinamis ditambahkan di sini oleh JavaScript -->
                </div>

                <div id="empty-resep-note" class="py-6 px-4 text-center border-2 border-dashed border-stone-200 rounded-xl bg-stone-50">
                    <span class="text-2xl block mb-1 opacity-60">⚖️</span>
                    <p class="text-xs font-semibold text-stone-600">Belum ada bahan baku dalam resep ini.</p>
                    <p class="text-[11px] text-stone-400 mt-0.5">Klik tombol "Tambah Bahan" jika menu ini memerlukan resep takaran bahan baku.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-stone-200 flex items-center justify-end gap-3">
                <a href="{{ route('menus.index') }}" class="px-4 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-md transition">Simpan Menu Baru</button>
            </div>
        </form>
    </div>
</div>

@php
    $bahanBakusJson = $bahanBakus->map(function ($b) {
        return [
            'id' => (int) $b->id,
            'nama' => $b->nama_bahan_baku,
            'satuan' => $b->satuan,
        ];
    })->values();

    $oldKomposisi = old('komposisi', []);
@endphp

<script>
    const listBahanBaku = @json($bahanBakusJson);
    let rowIndex = 0;

    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateSatuanLabel(selectElement, index) {
        const selectedId = selectElement.value;
        const satuanSpan = document.getElementById('satuan-label-' + index);
        const item = listBahanBaku.find(b => b.id == selectedId);
        if (item && satuanSpan) {
            satuanSpan.textContent = item.satuan;
        } else if (satuanSpan) {
            satuanSpan.textContent = '-';
        }
    }

    function tambahBarisResep(selectedBahanId = '', jumlah = '') {
        const container = document.getElementById('container-resep');
        const emptyNote = document.getElementById('empty-resep-note');
        emptyNote.classList.add('hidden');

        const currentIdx = rowIndex++;

        let optionsHtml = '<option value="" disabled ' + (selectedBahanId ? '' : 'selected') + '>-- Pilih Bahan Baku --</option>';
        listBahanBaku.forEach(b => {
            const isSelected = (b.id == selectedBahanId) ? 'selected' : '';
            optionsHtml += `<option value="${b.id}" data-satuan="${b.satuan}" ${isSelected}>${b.nama} (satuan: ${b.satuan})</option>`;
        });

        const selectedItem = listBahanBaku.find(b => b.id == selectedBahanId);
        const initialSatuan = selectedItem ? selectedItem.satuan : '-';

        const row = document.createElement('div');
        row.id = 'resep-row-' + currentIdx;
        row.className = 'flex flex-col sm:flex-row items-stretch sm:items-center gap-3 p-3 rounded-xl bg-stone-50 border border-stone-200';

        row.innerHTML = `
            <div class="flex-1">
                <label class="block text-[11px] font-bold text-stone-600 mb-1">Bahan Baku</label>
                <select 
                    name="komposisi[${currentIdx}][bahan_baku_id]" 
                    required 
                    onchange="updateSatuanLabel(this, ${currentIdx})"
                    class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white"
                >
                    ${optionsHtml}
                </select>
            </div>

            <div class="w-full sm:w-44">
                <label class="block text-[11px] font-bold text-stone-600 mb-1">Takaran Pakai</label>
                <div class="relative flex items-center">
                    <input 
                        type="number" 
                        name="komposisi[${currentIdx}][jumlah_penggunaan]" 
                        required 
                        min="0.01" 
                        step="0.01" 
                        value="${jumlah}"
                        placeholder="Contoh: 18" 
                        class="w-full px-3 py-2 pr-12 rounded-lg border border-stone-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white"
                    >
                    <span id="satuan-label-${currentIdx}" class="absolute right-3 text-[11px] font-bold text-stone-500 uppercase">
                        ${initialSatuan}
                    </span>
                </div>
            </div>

            <div class="self-end sm:self-auto sm:pt-5">
                <button 
                    type="button" 
                    onclick="hapusBarisResep(${currentIdx})"
                    class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center text-xs transition"
                    title="Hapus baris bahan"
                >
                    🗑️
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    function hapusBarisResep(index) {
        const row = document.getElementById('resep-row-' + index);
        if (row) {
            row.remove();
        }
        const container = document.getElementById('container-resep');
        const emptyNote = document.getElementById('empty-resep-note');
        if (container.children.length === 0) {
            emptyNote.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const oldData = @json($oldKomposisi);
        if (Array.isArray(oldData) && oldData.length > 0) {
            oldData.forEach(item => {
                if (item && item.bahan_baku_id) {
                    tambahBarisResep(item.bahan_baku_id, item.jumlah_penggunaan || '');
                }
            });
        }
    });
</script>
@endsection