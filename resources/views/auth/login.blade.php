<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — POS Kedai Kopi</title>
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
<body class="bg-gradient-to-br from-stone-900 via-amber-950 to-stone-950 text-stone-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-amber-600 items-center justify-center text-3xl shadow-lg shadow-amber-900/50 mb-3">
                ☕
            </div>
            <h1 class="text-2xl font-extrabold text-amber-100 tracking-tight">POS KEDAI KOPI</h1>
            <p class="text-xs text-amber-300 font-medium tracking-wide uppercase mt-1">Sistem Layanan Kasir & Manajemen Multi-Role</p>
        </div>

        <!-- Form Card -->
        <div class="bg-stone-900/90 backdrop-blur-md rounded-2xl border border-amber-900/50 p-6 sm:p-8 shadow-2xl">
            <h2 class="text-lg font-bold text-white mb-2">Masuk ke Akun Anda</h2>
            <p class="text-xs text-stone-400 mb-6">Masukkan nama pengguna dan kata sandi operasional kedai.</p>

            @if(session('error'))
                <div class="mb-5 p-3 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs font-semibold flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-950/80 border border-emerald-800 text-emerald-200 text-xs font-semibold flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <!-- Nama Pengguna -->
                <div>
                    <label for="nama_pengguna" class="block text-xs font-semibold text-stone-300 mb-1.5 uppercase tracking-wider">
                        Nama Pengguna
                    </label>
                    <input
                        type="text"
                        name="nama_pengguna"
                        id="nama_pengguna"
                        value="{{ old('nama_pengguna') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Contoh: admin_kopi"
                        class="w-full px-4 py-2.5 rounded-xl bg-stone-800/90 border {{ $errors->has('nama_pengguna') ? 'border-rose-500' : 'border-stone-700' }} text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition placeholder:text-stone-500"
                    >
                    @error('nama_pengguna')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-300 mb-1.5 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl bg-stone-800/90 border {{ $errors->has('password') ? 'border-rose-500' : 'border-stone-700' }} text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 transition placeholder:text-stone-500"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-stone-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-stone-700 text-amber-600 focus:ring-amber-500 bg-stone-800">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-xl font-bold text-sm bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 text-white shadow-lg shadow-amber-900/50 transition transform active:scale-[0.99]"
                >
                    Masuk ke Sistem POS
                </button>
            </form>

            <!-- Quick Demo Accounts Pills -->
            <div class="mt-8 pt-6 border-t border-stone-800">
                <p class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider mb-2 text-center">Akun Demo (Klik untuk Isi Otomatis):</p>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <button
                        type="button"
                        onclick="fillDemo('admin_kopi', 'admin123')"
                        class="p-2 rounded-lg bg-indigo-950/60 hover:bg-indigo-900/80 border border-indigo-800 text-indigo-200 transition text-[11px] font-semibold"
                    >
                        <span class="block text-indigo-400 font-bold">Admin</span>
                        admin_kopi
                    </button>
                    <button
                        type="button"
                        onclick="fillDemo('kasir_lutfi', 'kasir123')"
                        class="p-2 rounded-lg bg-emerald-950/60 hover:bg-emerald-900/80 border border-emerald-800 text-emerald-200 transition text-[11px] font-semibold"
                    >
                        <span class="block text-emerald-400 font-bold">Kasir</span>
                        kasir_lutfi
                    </button>
                    <button
                        type="button"
                        onclick="fillDemo('owner_kedai', 'owner123')"
                        class="p-2 rounded-lg bg-amber-950/60 hover:bg-amber-900/80 border border-amber-800 text-amber-200 transition text-[11px] font-semibold"
                    >
                        <span class="block text-amber-400 font-bold">Pemilik</span>
                        owner_kedai
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-stone-500 mt-6">
            LKP Pertemuan 5 &middot; Pemrograman Web Lanjut Kelompok 8
        </p>
    </div>

    <script>
        function fillDemo(username, password) {
            document.getElementById('nama_pengguna').value = username;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
