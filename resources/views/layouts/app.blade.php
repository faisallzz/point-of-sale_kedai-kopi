<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — POS Kedai Kopi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 antialiased min-h-screen flex flex-col">
    <!-- Navbar Atas -->
    <header class="bg-amber-950 text-amber-50 shadow-md sticky top-0 z-50 border-b border-amber-900/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center text-white font-bold text-xl shadow-inner group-hover:bg-amber-500 transition">
                            ☕
                        </div>
                        <div>
                            <span class="text-lg font-extrabold tracking-tight text-amber-100 group-hover:text-white transition">POS Kedai Kopi</span>
                            <span class="block text-[11px] font-medium text-amber-300 tracking-wider uppercase">Sistem Multi-Role & Layanan</span>
                        </div>
                    </a>
                </div>

                <!-- Navigasi Utama Sesuai Peran -->
                <nav class="hidden md:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Pengguna
                            </a>
                            <a href="{{ route('menus.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('menus.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Menu
                            </a>
                            <a href="{{ route('bahan-baku.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('bahan-baku.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Bahan Baku
                            </a>
                            <a href="{{ route('transaksi.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('transaksi.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Transaksi
                            </a>
                            <a href="{{ route('laporan.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('laporan.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Laporan
                            </a>
                        @elseif(auth()->user()->isKasir())
                            <a href="{{ route('kasir.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('kasir.dashboard') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Dashboard
                            </a>
                            <a href="{{ route('menus.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('menus.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Daftar Menu
                            </a>
                            <a href="{{ route('transaksi.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('transaksi.index') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Riwayat Transaksi
                            </a>
                            <a href="{{ route('transaksi.create') }}" class="px-3 py-2 rounded-lg text-sm font-semibold bg-emerald-700 hover:bg-emerald-600 text-white transition">
                                + Catat Transaksi
                            </a>
                        @elseif(auth()->user()->isPemilik())
                            <a href="{{ route('pemilik.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('pemilik.dashboard') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Dashboard
                            </a>
                            <a href="{{ route('laporan.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('laporan.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Laporan Penjualan
                            </a>
                            <a href="{{ route('menus.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('menus.*') ? 'bg-amber-900 text-white' : 'text-amber-200 hover:bg-amber-900/60 hover:text-white' }} transition">
                                Data Menu
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Info User & Logout -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="flex items-center gap-2 text-right">
                            <div class="hidden sm:block">
                                <div class="text-sm font-bold text-amber-100">{{ auth()->user()->nama_pengguna }}</div>
                                <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider
                                    {{ auth()->user()->peran === 'admin' ? 'bg-indigo-900/80 text-indigo-200 border border-indigo-700' : '' }}
                                    {{ auth()->user()->peran === 'kasir' ? 'bg-emerald-900/80 text-emerald-200 border border-emerald-700' : '' }}
                                    {{ auth()->user()->peran === 'pemilik' ? 'bg-amber-800/80 text-amber-200 border border-amber-600' : '' }}">
                                    Role: {{ ucfirst(auth()->user()->peran) }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-950/80 hover:bg-rose-900 text-rose-200 border border-rose-800 transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-amber-600 hover:bg-amber-500 text-white transition">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Bar Navigasi Mobile -->
    @auth
    <div class="md:hidden bg-amber-900 border-b border-amber-800 px-4 py-2 flex flex-wrap gap-2 text-xs">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="px-2 py-1 rounded bg-amber-950 text-white">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="px-2 py-1 rounded text-amber-200">Pengguna</a>
            <a href="{{ route('menus.index') }}" class="px-2 py-1 rounded text-amber-200">Menu</a>
            <a href="{{ route('bahan-baku.index') }}" class="px-2 py-1 rounded text-amber-200">Bahan Baku</a>
            <a href="{{ route('transaksi.index') }}" class="px-2 py-1 rounded text-amber-200">Transaksi</a>
            <a href="{{ route('laporan.index') }}" class="px-2 py-1 rounded text-amber-200">Laporan</a>
        @elseif(auth()->user()->isKasir())
            <a href="{{ route('kasir.dashboard') }}" class="px-2 py-1 rounded bg-amber-950 text-white">Dashboard</a>
            <a href="{{ route('menus.index') }}" class="px-2 py-1 rounded text-amber-200">Menu</a>
            <a href="{{ route('transaksi.index') }}" class="px-2 py-1 rounded text-amber-200">Transaksi</a>
            <a href="{{ route('transaksi.create') }}" class="px-2 py-1 rounded bg-emerald-700 text-white">+ Catat</a>
        @elseif(auth()->user()->isPemilik())
            <a href="{{ route('pemilik.dashboard') }}" class="px-2 py-1 rounded bg-amber-950 text-white">Dashboard</a>
            <a href="{{ route('laporan.index') }}" class="px-2 py-1 rounded text-amber-200">Laporan</a>
            <a href="{{ route('menus.index') }}" class="px-2 py-1 rounded text-amber-200">Menu</a>
        @endif
    </div>
    @endauth

    <!-- Flash Messages -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 text-lg font-bold">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-stone-100 border-t border-stone-200 py-6 text-center text-xs text-stone-500">
        <p>&copy; {{ date('Y') }} POS Kedai Kopi — Pemrograman Web Lanjut Kelompok 8 (LKP 5 Multi-Role & User Management)</p>
    </footer>
</body>
</html>
