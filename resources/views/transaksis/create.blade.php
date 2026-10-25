@extends('layouts.app')

@section('title', 'Catat Transaksi Kasir')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex items-center justify-between bg-white p-5 sm:p-6 rounded-2xl border border-stone-200 shadow-sm">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">
                <span>☕</span> Layanan Kasir
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-stone-800">Catat Transaksi</h1>
            <p class="text-xs text-stone-500 mt-0.5">Pilih menu kopi untuk menambahkan ke pesanan dan atur transaksi di panel kasir.</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="px-3.5 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold border border-stone-300 transition flex items-center gap-1.5">
            <span>&larr;</span> Riwayat
        </a>
    </div>

    <!-- Alert Error Validasi Server -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                <span>⚠️</span> Pesanan belum dapat disimpan:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tata Letak 2 Kolom (Layar Lebar) & 1 Kolom (Mobile) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Kolom Kiri: Katalog Kartu Menu (Layar Lebar: 7-8 kolom) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xs sm:text-sm font-extrabold text-stone-700 uppercase tracking-wider">
                    Pilihan Menu Kopi ({{ $menus->count() }})
                </h2>
                <span class="text-[11px] text-stone-400">Klik kartu menu untuk menambah ke pesanan</span>
            </div>

            <!-- Grid Menu: 2 kolom di HP, 3 kolom di layar besar -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-4">
                @foreach ($menus as $menu)
                    @php
                        $tersedia = $menu->is_tersedia;
                        $stokPorsi = $menu->stok_porsi;
                    @endphp
                    <div 
                        @if($tersedia)
                            onclick="tambahItem({{ $menu->id }})"
                            role="button"
                            tabindex="0"
                            onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); tambahItem({{ $menu->id }}); }"
                            class="group bg-white rounded-2xl border border-stone-200 hover:border-amber-500/60 shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col cursor-pointer select-none"
                        @else
                            class="bg-stone-50 rounded-2xl border border-stone-200/80 shadow-xs opacity-60 overflow-hidden flex flex-col cursor-not-allowed select-none"
                            title="Menu tidak dapat dipesan karena bahan baku habis: {{ implode(', ', $menu->bahan_habis) }}"
                        @endif
                    >
                        <!-- Gambar Menu (Rasio 4:3) -->
                        <div class="relative w-full aspect-[4/3] bg-stone-100 overflow-hidden" style="aspect-ratio: 4/3;">
                            <img 
                                src="{{ $menu->gambar_url }}" 
                                alt="{{ $menu->nama_menu }}"
                                loading="lazy"
                                class="w-full h-full object-cover {{ $tersedia ? 'group-hover:scale-105 transition-transform duration-300' : 'grayscale' }}"
                            >
                            @if($tersedia)
                                <span class="absolute top-2 right-2 bg-amber-950/80 text-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm shadow">
                                    Kopi
                                </span>
                                @if($stokPorsi !== null && $stokPorsi <= 10)
                                    <span class="absolute bottom-2 left-2 bg-amber-500/90 text-stone-900 text-[10px] font-extrabold px-2 py-0.5 rounded-md backdrop-blur-sm shadow">
                                        Sisa {{ $stokPorsi }} porsi
                                    </span>
                                @endif
                            @else
                                <span class="absolute top-2 right-2 bg-rose-600 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow">
                                    ✕ Habis
                                </span>
                                <div class="absolute inset-0 bg-stone-900/40 flex items-center justify-center">
                                    <span class="px-2.5 py-1 rounded-lg bg-rose-600/90 text-white text-xs font-extrabold shadow">
                                        Stok Habis
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Info Menu & Tombol Tambah -->
                        <div class="p-3 sm:p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-stone-800 text-xs sm:text-sm md:text-base leading-snug {{ $tersedia ? 'group-hover:text-amber-900' : 'text-stone-400' }} transition">
                                    {{ $menu->nama_menu }}
                                </h3>
                                <p class="text-xs sm:text-sm font-extrabold {{ $tersedia ? 'text-emerald-700' : 'text-stone-400' }} mt-1">
                                    Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2.5 border-t border-stone-100 flex items-center justify-between">
                                @if($tersedia)
                                    <span class="text-[11px] text-stone-400 group-hover:text-stone-600 font-medium">
                                        Tambah
                                    </span>
                                    <button 
                                        type="button" 
                                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-amber-50 group-hover:bg-emerald-600 text-amber-900 group-hover:text-white flex items-center justify-center font-bold text-sm sm:text-base transition shadow-sm"
                                        aria-label="Tambah {{ $menu->nama_menu }} ke pesanan"
                                        onclick="event.stopPropagation(); tambahItem({{ $menu->id }});"
                                    >
                                        +
                                    </button>
                                @else
                                    <span class="text-[11px] text-rose-500 font-bold">
                                        Bahan Kosong
                                    </span>
                                    <button 
                                        type="button" 
                                        disabled
                                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-stone-200 text-stone-400 flex items-center justify-center font-bold text-sm cursor-not-allowed"
                                        aria-label="{{ $menu->nama_menu }} habis"
                                    >
                                        ✕
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Kolom Kanan: Panel Pesanan Kasir (Sticky di Layar Lebar) -->
        <div class="lg:col-span-5 xl:col-span-4">
            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-4 sm:p-5 lg:sticky lg:top-20 space-y-4">
                
                <!-- Header Panel Pesanan -->
                <div class="flex items-center justify-between pb-3 border-b border-stone-200">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🧾</span>
                        <h2 class="font-extrabold text-stone-800 text-sm sm:text-base">Pesanan Pelanggan</h2>
                    </div>
                    <span id="badge-total-item" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-stone-100 text-stone-600 border border-stone-200">
                        0 Item
                    </span>
                </div>

                <form id="pos-form" method="POST" action="{{ route('transaksi.store') }}" class="space-y-4">
                    @csrf

                    <!-- Input Nama Pelanggan -->
                    <div>
                        <label for="nama_pelanggan" class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1.5">
                            Nama Pelanggan
                        </label>
                        <input 
                            type="text" 
                            name="nama_pelanggan" 
                            id="nama_pelanggan" 
                            maxlength="100" 
                            value="{{ old('nama_pelanggan') }}"
                            placeholder="Pelanggan Umum"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white placeholder-stone-400 font-medium transition"
                        >
                        <span class="text-[11px] text-stone-400 mt-1 block">Boleh dikosongkan (default: Pelanggan Umum).</span>
                    </div>

                    <!-- Wadah Elemen Hidden Input Items -->
                    <div id="hidden-inputs-container"></div>

                    <!-- Daftar Item Pesanan -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">
                                Item Terpilih
                            </span>
                            <button 
                                type="button" 
                                id="btn-reset-cart"
                                onclick="kosongkanPesanan()"
                                class="text-[11px] font-semibold text-rose-600 hover:text-rose-700 transition hidden"
                            >
                                Kosongkan
                            </button>
                        </div>

                        <!-- Tampilan Saat Pesanan Kosong -->
                        <div id="empty-state" class="py-8 px-4 text-center border-2 border-dashed border-stone-200 rounded-xl bg-stone-50">
                            <span class="text-3xl block mb-2 opacity-60">🛒</span>
                            <p class="text-xs font-bold text-stone-600">Belum ada pesanan.</p>
                            <p class="text-[11px] text-stone-400 mt-0.5">Pilih menu di sebelah kiri.</p>
                        </div>

                        <!-- Tampilan Daftar Item (Scroll jika banyak) -->
                        <div id="items-list" class="space-y-2 max-h-[300px] overflow-y-auto pr-1 hidden"></div>
                    </div>

                    <!-- Rincian Pembayaran & Submit -->
                    <div class="border-t border-stone-200 pt-3.5 space-y-3.5">
                        
                        <!-- Total Pembelian -->
                        <div class="flex items-center justify-between bg-stone-50 p-3 rounded-xl border border-stone-200">
                            <div>
                                <span class="text-[11px] font-bold text-stone-500 uppercase tracking-wider block">Total Pembelian</span>
                                <span id="label-subtotal-info" class="text-[11px] text-stone-400">0 porsi dipesan</span>
                            </div>
                            <div id="display-total" class="text-lg sm:text-xl font-extrabold text-emerald-700">
                                Rp 0
                            </div>
                        </div>

                        <!-- Pilihan Metode Pembayaran (HANYA TAMPILAN) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Metode Pembayaran</span>
                                <span class="text-[10px] text-stone-400 font-medium bg-stone-100 px-1.5 py-0.5 rounded border border-stone-200" title="Pilihan ini hanya simulasi tampilan di kasir">
                                    Simulasi Tampilan
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button 
                                    type="button" 
                                    id="btn-metode-tunai" 
                                    onclick="pilihMetodePembayaran('tunai')"
                                    class="py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-emerald-600 bg-emerald-50 text-emerald-800"
                                >
                                    <span>💵</span> Tunai
                                </button>
                                <button 
                                    type="button" 
                                    id="btn-metode-qris" 
                                    onclick="pilihMetodePembayaran('qris')"
                                    class="py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-stone-200 bg-white text-stone-600 hover:bg-stone-50"
                                >
                                    <span>📱</span> QRIS
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Submit Simpan Transaksi -->
                        <button 
                            type="submit" 
                            id="btn-submit"
                            class="w-full py-3 px-4 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-600 text-xs sm:text-sm font-bold shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <span>💳</span> Simpan Transaksi
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@php
    $menusPayload = $menus->map(function ($item) {
        return [
            'id' => (int) $item->id,
            'nama' => $item->nama_menu,
            'harga' => (int) round($item->harga_jual),
            'gambar_url' => $item->gambar_url,
            'is_tersedia' => (bool) $item->is_tersedia,
            'stok_porsi' => $item->stok_porsi,
            'bahan_habis' => $item->bahan_habis,
        ];
    })->values();

    $oldItems = old('items', []);
@endphp

<script>
    // Data menu dari database (harga berupa angka int)
    const menusData = @json($menusPayload);
    const menusMap = {};
    menusData.forEach(function (m) {
        menusMap[m.id] = m;
    });

    // Keranjang pesanan kasir: { [menuId]: jumlah }
    let cart = {};

    // Pulihkan pesanan dari old('items') jika validasi server sebelumnya gagal
    const oldItemsData = @json($oldItems);
    if (Array.isArray(oldItemsData) && oldItemsData.length > 0) {
        oldItemsData.forEach(function (row) {
            if (row && row.menu_id && row.jumlah) {
                const id = parseInt(row.menu_id, 10);
                const qty = parseInt(row.jumlah, 10);
                if (menusMap[id] && qty > 0) {
                    cart[id] = (cart[id] || 0) + Math.min(qty, 99);
                }
            }
        });
    }

    /**
     * Tambah 1 porsi menu ke pesanan (maksimal porsi tersedia / 99)
     */
    function tambahItem(menuId) {
        const menu = menusMap[menuId];
        if (!menu) return;

        if (!menu.is_tersedia) {
            alert('Maaf, menu "' + menu.nama + '" saat ini tidak dapat dipesan karena bahan baku habis (' + (menu.bahan_habis || []).join(', ') + ').');
            return;
        }

        const currentQty = cart[menuId] || 0;
        const maxPorsi = menu.stok_porsi !== null ? menu.stok_porsi : 99;

        if (currentQty >= maxPorsi) {
            alert('Stok bahan baku saat ini hanya mencukupi untuk ' + maxPorsi + ' porsi ' + menu.nama + '.');
            return;
        }

        if (currentQty < 99) {
            cart[menuId] = currentQty + 1;
        }
        renderCart();
    }

    /**
     * Kurangi 1 porsi menu dari pesanan. Jika mencapai 0, item dihapus.
     */
    function kurangiItem(menuId) {
        if (!cart[menuId]) return;
        if (cart[menuId] > 1) {
            cart[menuId] -= 1;
        } else {
            delete cart[menuId];
        }
        renderCart();
    }

    /**
     * Hapus item dari pesanan
     */
    function hapusItem(menuId) {
        delete cart[menuId];
        renderCart();
    }

    /**
     * Kosongkan seluruh item pesanan
     */
    function kosongkanPesanan() {
        cart = {};
        renderCart();
    }

    /**
     * Render antarmuka keranjang belanja kasir
     */
    function renderCart() {
        const itemsListEl = document.getElementById('items-list');
        const emptyStateEl = document.getElementById('empty-state');
        const badgeTotalItemEl = document.getElementById('badge-total-item');
        const displayTotalEl = document.getElementById('display-total');
        const labelSubtotalInfoEl = document.getElementById('label-subtotal-info');
        const btnSubmitEl = document.getElementById('btn-submit');
        const btnResetCartEl = document.getElementById('btn-reset-cart');
        const hiddenInputsEl = document.getElementById('hidden-inputs-container');

        const menuIds = Object.keys(cart);
        let totalBayar = 0;
        let totalPorsi = 0;

        // Kosongkan kontainer DOM
        itemsListEl.innerHTML = '';
        hiddenInputsEl.innerHTML = '';

        if (menuIds.length === 0) {
            emptyStateEl.classList.remove('hidden');
            itemsListEl.classList.add('hidden');
            btnResetCartEl.classList.add('hidden');
            badgeTotalItemEl.textContent = '0 Item';
            displayTotalEl.textContent = 'Rp 0';
            labelSubtotalInfoEl.textContent = '0 porsi dipesan';
            btnSubmitEl.disabled = false;
            btnSubmitEl.className = 'w-full py-3 px-4 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-600 text-xs sm:text-sm font-bold shadow-sm transition flex items-center justify-center gap-2 cursor-pointer';
            return;
        }

        emptyStateEl.classList.add('hidden');
        itemsListEl.classList.remove('hidden');
        btnResetCartEl.classList.remove('hidden');
        btnSubmitEl.disabled = false;
        btnSubmitEl.className = 'w-full py-3 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold shadow-md transition flex items-center justify-center gap-2 cursor-pointer';

        let inputIndex = 0;

        menuIds.forEach(function (idStr) {
            const id = parseInt(idStr, 10);
            const qty = cart[id];
            const menu = menusMap[id];
            if (!menu) return;

            const subtotal = menu.harga * qty;
            totalBayar += subtotal;
            totalPorsi += qty;

            // Generate Baris Item di Panel Pesanan
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 p-2.5 rounded-xl bg-stone-50 border border-stone-200';

            row.innerHTML = `
                <img src="${menu.gambar_url}" alt="${menu.nama}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-stone-200">
                <div class="flex-1 min-w-0">
                    <h4 class="text-xs font-bold text-stone-800 truncate">${menu.nama}</h4>
                    <p class="text-[11px] text-stone-500">Rp ${menu.harga.toLocaleString('id-ID')} &times; ${qty}</p>
                    <p class="text-xs font-extrabold text-emerald-700">Rp ${subtotal.toLocaleString('id-ID')}</p>
                </div>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button 
                        type="button" 
                        onclick="kurangiItem(${id})" 
                        aria-label="Kurangi ${menu.nama}"
                        class="w-6 h-6 rounded-md bg-white hover:bg-stone-200 text-stone-700 border border-stone-300 flex items-center justify-center font-bold text-xs transition"
                    >
                        &minus;
                    </button>
                    <span class="w-6 text-center text-xs font-extrabold text-stone-800">${qty}</span>
                    <button 
                        type="button" 
                        onclick="tambahItem(${id})" 
                        aria-label="Tambah ${menu.nama}"
                        class="w-6 h-6 rounded-md bg-white hover:bg-stone-200 text-stone-700 border border-stone-300 flex items-center justify-center font-bold text-xs transition"
                    >
                        &#43;
                    </button>
                    <button 
                        type="button" 
                        onclick="hapusItem(${id})" 
                        aria-label="Hapus ${menu.nama}"
                        class="w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center text-xs ml-1 transition"
                    >
                        &times;
                    </button>
                </div>
            `;
            itemsListEl.appendChild(row);

            // Generate Input Tersembunyi untuk dikirim ke TransaksiController@store
            const menuInput = document.createElement('input');
            menuInput.type = 'hidden';
            menuInput.name = `items[${inputIndex}][menu_id]`;
            menuInput.value = id;

            const jumlahInput = document.createElement('input');
            jumlahInput.type = 'hidden';
            jumlahInput.name = `items[${inputIndex}][jumlah]`;
            jumlahInput.value = qty;

            hiddenInputsEl.appendChild(menuInput);
            hiddenInputsEl.appendChild(jumlahInput);

            inputIndex++;
        });

        badgeTotalItemEl.textContent = `${menuIds.length} Menu (${totalPorsi} Porsi)`;
        displayTotalEl.textContent = 'Rp ' + totalBayar.toLocaleString('id-ID');
        labelSubtotalInfoEl.textContent = `${totalPorsi} porsi dipesan`;
    }

    /**
     * Simulasi pilihan metode pembayaran (Hanya Tampilan)
     */
    function pilihMetodePembayaran(metode) {
        const btnTunai = document.getElementById('btn-metode-tunai');
        const btnQris = document.getElementById('btn-metode-qris');

        if (metode === 'tunai') {
            btnTunai.className = 'py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-emerald-600 bg-emerald-50 text-emerald-800';
            btnQris.className = 'py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-stone-200 bg-white text-stone-600 hover:bg-stone-50';
        } else {
            btnQris.className = 'py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-emerald-600 bg-emerald-50 text-emerald-800';
            btnTunai.className = 'py-2 px-3 rounded-xl border-2 text-xs font-bold transition flex items-center justify-center gap-1.5 border-stone-200 bg-white text-stone-600 hover:bg-stone-50';
        }
    }

    // Inisialisasi awal keranjang saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        renderCart();
    });
</script>
@endsection
