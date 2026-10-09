<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | POS Kedai Kopi</title>
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
<body class="bg-gradient-to-br from-stone-900 via-rose-950 to-stone-950 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <!-- Badge -->
        <div class="inline-flex w-20 h-20 rounded-3xl bg-rose-900/60 border border-rose-700/80 items-center justify-center text-4xl shadow-2xl shadow-rose-950 mb-6">
            🚫
        </div>

        <div class="bg-stone-900/90 backdrop-blur-md rounded-2xl border border-rose-900/40 p-8 shadow-2xl">
            <span class="inline-block px-3 py-1 rounded-full bg-rose-950 text-rose-300 border border-rose-800 text-xs font-extrabold uppercase tracking-widest mb-3">
                Error 403 — Terlarang
            </span>

            <h1 class="text-2xl font-extrabold text-white mb-2">Akses Ditolak!</h1>
            <p class="text-xs text-stone-300 leading-relaxed mb-6">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau otoritas untuk membuka modul/halaman ini sesuai matriks hak akses sistem POS Kedai Kopi.' }}
            </p>

            @auth
                <div class="p-3.5 rounded-xl bg-stone-800/80 border border-stone-700 mb-6 text-left text-xs">
                    <p class="text-stone-400">Pengguna Aktif: <strong class="text-white">{{ auth()->user()->nama_pengguna }}</strong></p>
                    <p class="text-stone-400 mt-1">Peran Terdaftar: <strong class="uppercase text-amber-300 font-bold">{{ auth()->user()->peran }}</strong></p>
                </div>
            @endauth

            <div class="flex flex-col sm:flex-row gap-3">
                <a
                    href="{{ url('/') }}"
                    class="flex-1 py-3 px-4 rounded-xl font-bold text-xs bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 text-white shadow-lg transition"
                >
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>

        <p class="text-xs text-stone-500 mt-6">
            Keamanan Sistem Multi-Role POS Kedai Kopi &middot; LKP 5
        </p>
    </div>
</body>
</html>
