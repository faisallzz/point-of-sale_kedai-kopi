# PERAN
Kamu adalah Senior Laravel Developer sekaligus Technical Writer. Kerjakan tugas praktikum "Login Multi Role dan Management Pengguna" (LKP Pertemuan 5) pada projek Laravel yang sudah ada di workspace ini, lalu dokumentasikan hasilnya mengikuti template LKP terlampir.

# FILE ACUAN
Template dokumentasi: `LKP_Pertemuan_5_Login_MultiRole_Management_Pengguna_Revisi.docx.md` (terlampir). Ikuti struktur Bab I–XIII, nama tabel, dan nama file screenshot di dalamnya.
Gaya pengisian mengikuti LKP sebelumnya (LKP04): ringkas, berbentuk tabel, TANPA menempelkan source code. Cukup path file dan status commit.

# KONTEKS PROJEK
- Mata kuliah: Pemrograman Web Lanjut, Universitas Hasyim Asy'ari Tebuireng Jombang, Teknik Informatika
- Nama projek: POS (Point of Sale) Kedai Kopi
- Deskripsi projek: Aplikasi kasir kedai kopi untuk mengelola menu, bahan baku, komposisi menu, transaksi penjualan, dan laporan penjualan bulanan.
- Framework: Laravel 11/12, PHP 8.2+, Laragon, MySQL/MariaDB
- Repository asal (milik kelompok): https://github.com/faisallzz/point-of-sale_kedai-kopi
- Workspace ini adalah SALINAN projek utama yang khusus dipakai untuk pengerjaan LKP 5. Projek utama tidak boleh terpengaruh.

# ISOLASI SALINAN (LAKUKAN PERTAMA)
1. Ubah `.env` salinan ini agar memakai database terpisah, yaitu `DB_DATABASE=db_kedaikopi_lkp5` (user `root`, password kosong). Buat database tersebut (collation `utf8mb4_unicode_ci`). Tujuannya agar `migrate:fresh` pada salinan ini tidak menghapus data projek utama (`db_kedaikopi`).
2. Jika server dev projek utama mungkin sedang berjalan di port 8000, jalankan salinan ini di port lain (`php artisan serve --port=8001`).
3. Jangan push apa pun ke remote `origin` tanpa persetujuan saya.

# LANGKAH 0 — AUDIT DULU (jangan ubah kode sebelum selesai)
Baca dan rangkum kondisi projek saat ini:
- versi Laravel (`composer.json`)
- isi `database/migrations`, `app/Models`, `database/seeders` (khususnya `UserSeeder.php`), `routes/web.php`
- controller, view, dan layout yang sudah ada
- framework CSS yang sudah dipakai (Bootstrap/Tailwind/lainnya). Pakai yang sama, jangan tambah yang baru.
- apakah modul Menu, Bahan Baku, Transaksi, dan Laporan sudah punya controller dan halaman
Tampilkan hasil audit singkat, lalu lanjut ke tugas.

# SKEMA YANG SUDAH ADA (JANGAN DIRUSAK)
Tabel `users` sudah ada dan dipakai tim:
`id`, `nama_pengguna` (unique), `password`, `peran` (enum: admin, kasir, pemilik; default kasir), `remember_token`, `created_at`, `updated_at`. Tidak ada kolom email.
- Login memakai **nama_pengguna + password**, bukan email. Ini keputusan final.
- Jangan edit migration yang sudah ada. Jika butuh perubahan skema, buat migration BARU.
- `transaksi_penjualans.user_id` memakai `restrictOnDelete`. Jadi pengguna yang sudah punya riwayat transaksi TIDAK BOLEH terhapus. Tangkap kasus ini di `UserController@destroy` dan tampilkan pesan error yang ramah, bukan error 500.
- Akun seeder yang sudah ada: `admin_kopi` (admin), `kasir_lutfi` (kasir), `owner_kedai` (pemilik). Baca password aslinya dari `UserSeeder.php` dan pakai akun-akun ini. Jangan buat akun duplikat.

# ROLE & HAK AKSES
Urutan role di LKP: Role 1 = Admin, Role 2 = Kasir, Role 3 = Pemilik.
"Data Utama Projek" = data master Menu dan Bahan Baku, beserta Transaksi dan Laporan Penjualan.

| Fitur | Admin | Kasir | Pemilik |
|---|---|---|---|
| Login | Ya | Ya | Ya |
| Dashboard | Ya | Ya | Ya |
| Melihat Data (Menu, Bahan Baku, Laporan) | Ya | Ya (Menu & Transaksi) | Ya (Laporan & Menu) |
| Tambah Data | Ya | Ya (Transaksi) | Tidak |
| Edit Data | Ya | Tidak | Tidak |
| Hapus Data | Ya | Tidak | Tidak |
| Management User | Ya | Tidak | Tidak |

Hak akses di atas harus benar-benar ditegakkan di kode (middleware dan route), bukan hanya disembunyikan di tampilan.

# TUGAS A — IMPLEMENTASI KODE
1. **Model `User`**: sesuaikan agar autentikasi memakai `nama_pengguna` (`fillable` = nama_pengguna, password, peran), dengan password di-hash.
2. **AuthController** (`app/Http/Controllers/AuthController.php`): form login (nama_pengguna dan password), validasi dengan pesan Bahasa Indonesia, `Auth::attempt` dengan `nama_pengguna`, regenerate session, redirect sesuai `peran`, dan logout dengan invalidate session. Halaman login hanya untuk tamu (`guest`).
3. **Middleware role** (`CheckRole` atau sejenisnya): daftarkan alias sesuai cara Laravel 11/12 (`bootstrap/app.php`). Akses di luar hak akses menghasilkan 403 atau redirect dengan pesan.
4. **UserController** (`app/Http/Controllers/UserController.php`), CRUD pengguna khusus Admin:
   - Read: daftar pengguna
   - Create: nama_pengguna, password, peran
   - Update: nama_pengguna, peran, dan password opsional
   - Delete: dengan konfirmasi. Tidak boleh menghapus akun sendiri, dan tidak boleh menghapus pengguna yang punya transaksi.
5. **Routing** (`routes/web.php`): kelompokkan dengan middleware `auth` dan `role`. Dashboard terpisah per peran.
6. **Views** (`resources/views`): login, dashboard per peran, dan halaman pengguna (index, create, edit). Menu navigasi dan tombol hanya tampil sesuai peran.
7. **Modul data utama**: jika Menu, Bahan Baku, Transaksi, dan Laporan sudah punya halaman, pasang middleware peran sesuai tabel di atas. Jika belum ada, buat halaman daftar minimal (index) untuk masing-masing, cukup untuk membuktikan pembatasan akses. Jangan membuat fitur POS lengkap.
8. **Seeder**: pastikan `DatabaseSeeder` tetap berurutan induk → anak dan `php artisan migrate:fresh --seed` berjalan tanpa error.
9. **Pengujian**: tulis Feature Test (`php artisan test`) untuk:
   - login tiap peran berhasil
   - login gagal dengan password salah
   - redirect sesuai peran
   - Kasir dan Pemilik ditolak (403) di halaman management user
   - Admin berhasil tambah, edit, dan hapus pengguna
   - hapus pengguna yang punya transaksi ditolak dengan pesan
   Jalankan semuanya dan simpan outputnya.

# TUGAS B — DOKUMENTASI LKP
Buat file `LKP05_2495114039_MuhammadLutfiMaulana.md` (penamaan mengikuti LKP04: `LKP05_[NIM]_[NamaLengkap].md`). Isi semua bab I–XIII dari template:

- **I. Identitas Proyek**:
  Nama Mahasiswa: Muhammad Lutfi Maulana | NIM: 2495114039 | Kelas/Semester: Teknik Informatika A / V (Lima) | Nama Kelompok: Kelompok 8 | Nama Projek: POS - Point Of Sale Kedai Kopi | Deskripsi Projek: isi dari konteks di atas | Repository Git: https://github.com/faisallzz/point-of-sale_kedai-kopi | Branch Pengerjaan: isi dari `git branch --show-current`
- **IV–V**: Role 1 Admin, Role 2 Kasir, Role 3 Pemilik. Pada tabel "Komponen/Data", baris "Email" diganti "Nama Pengguna" karena sistem login memakai username. Isi password dari seeder. Seragamkan tabel hak akses ketiga role dengan 6 baris (Dashboard, Melihat Data, Tambah Data, Edit Data, Hapus Data, Management User) sesuai kode yang diimplementasikan. Hapus petunjuk 📌 dan baris role ke-4 yang tidak dipakai.
- **VII**: struktur tabel `users` sesuai migration asli (nama_pengguna, peran, remember_token, dan seterusnya), bukan `nama`/`email`/`role`.
- **VIII (CRUD)**: isi data input dengan contoh nyata dari pengujian. Pada 8.3 Update, status "Bisa/Tidak" mengikuti kode: Nama Pengguna, Peran, dan Password (opsional). Baris "Email" diganti atau dihapus.
- **IX**: matriks hak akses dengan ✓ atau ✗ sesuai implementasi.
- **X**: "Berhasil/Gagal" berdasarkan hasil test dan uji manual yang BENAR-BENAR dijalankan. Jika gagal, tulis Gagal dan sebabnya.
- **XI**: informasi repo (Owner: faisallzz).
- **XII**: tabel file implementasi sesuai file yang benar-benar dibuat atau diubah (path dan fungsi), termasuk middleware, test, dan view baru.
- **XIII**: centang ☑ hanya yang benar-benar selesai. Item "Source code sudah di repository" baru dicentang setelah ada commit.
- Jangan menempelkan source code ke dokumen, hanya path file dan keterangan fungsi.

# SCREENSHOT
Jalankan aplikasi, lalu ambil screenshot lewat browser. Simpan di `screenshots/` dengan nama persis seperti di template:
`login_role1.png`, `dashboard_role1.png`, `login_role2.png`, `dashboard_role2.png`, `login_role3.png`, `dashboard_role3.png`, `user_create.png`, `user_list.png`, `user_edit.png`, `user_delete.png`.
Setiap screenshot harus memperlihatkan URL atau halaman dan peran yang aktif. Untuk bukti pembatasan akses, ambil juga satu screenshot halaman 403 dengan nama `akses_ditolak.png` dan sisipkan di Bab X. Sisipkan semua gambar ke LKP dengan `![keterangan](screenshots/nama.png)`.

# GIT
- Buat branch lokal `feature/lkp05-login-multirole`. Commit bertahap dengan pesan jelas (misalnya `feat: login multi role`, `feat: crud pengguna`, `docs: LKP05`).
- Jangan commit `.env`. Perbarui `.env.example` jika perlu (tanpa kredensial).
- Jangan push dan jangan merge ke `main` tanpa persetujuan saya.

# ATURAN
- Jangan mengarang data. Isian dokumentasi harus berasal dari kode dan hasil pengujian nyata.
- Jangan merusak fitur atau migration milik anggota kelompok lain.
- Ikuti konvensi Laravel (Eloquent, `@csrf`, `@method`, validasi, hashing password). Jangan simpan password plaintext di database.
- Teks UI, pesan validasi, dan dokumentasi dalam Bahasa Indonesia.

# URUTAN KERJA
1. Isolasi salinan, lalu audit (Langkah 0). Tampilkan ringkasan dan rencana kerja, lalu tunggu persetujuan saya.
2. Kerjakan Tugas A, jalankan test, dan tampilkan hasilnya.
3. Ambil screenshot, lalu kerjakan Tugas B.
4. Akhiri dengan ringkasan: file yang dibuat atau diubah, hasil test, dan hal yang belum selesai atau perlu keputusan saya.
