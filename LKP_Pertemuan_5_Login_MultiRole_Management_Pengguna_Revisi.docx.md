

| LEMBAR KERJA PRAKTIKUM (LKP) — PERTEMUAN 5 Dokumentasi Implementasi Login Multi Roledan Management Pengguna Mata Kuliah: Pemrograman Web Lanjut · Framework: Laravel · Database: MySQL / MariaDB |
| :---: |

**I. IDENTITAS PROYEK**

| Komponen | Isian |
| :---- | :---- |
| Nama Mahasiswa | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| NIM | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Kelas / Semester | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Nama Kelompok | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Nama Projek | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Deskripsi Projek | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Framework | Laravel |
| Database | MySQL / MariaDB |
| Repository Git | https\://github.com/\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Branch Pengerjaan | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**II. TUJUAN PRAKTIKUM**

Dokumentasi ini dibuat untuk mencatat implementasi fitur autentikasi dan pengelolaan pengguna pada projek kelompok.

**Tujuan:**

**1\.** Mendokumentasikan proses login berdasarkan role pengguna.

**2\.** Menjelaskan hak akses setiap role pada sistem.

**3\.** Mendokumentasikan proses CRUD pengguna.

**4\.** Membuktikan hasil implementasi melalui pengujian sistem.

**III. DESKRIPSI SISTEM LOGIN**

**3.1 Alur Login**

Pengguna melakukan login menggunakan akun yang telah terdaftar pada sistem. Sistem melakukan validasi email dan password, kemudian menentukan halaman tujuan berdasarkan role pengguna.

**Alur:**

**1\.** Pengguna membuka halaman login.

**2\.** Pengguna memasukkan email dan password.

**3\.** Sistem melakukan autentikasi.

**4\.** Sistem membaca role pengguna.

**5\.** Sistem mengarahkan pengguna sesuai hak akses.

**IV. ROLE PENGGUNA SISTEM**

| 📌 Petunjuk: *Sesuaikan dengan kebutuhan projek kelompok.* |
| :---- |

| No | Role | Deskripsi Hak Akses |
| :---: | :---- | :---- |
| 1 | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| 2 | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| 3 | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| 4 | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**V. DOKUMENTASI LOGIN SETIAP ROLE**

**5.1 Login Role 1: \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_**

| Komponen | Data |
| :---- | :---- |
| Email | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Password | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Role | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**Hak akses:**

| Fitur | Akses |
| :---- | :---: |
| Dashboard | Ya / Tidak |
| Data Utama Projek | Ya / Tidak |
| Tambah Data | Ya / Tidak |
| Edit Data | Ya / Tidak |
| Hapus Data | Ya / Tidak |

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/login\_role1.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/login\_role1.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

| 📷 Bukti Tangkapan Layar: screenshots/dashboard\_role1.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/dashboard\_role1.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**5.2 Login Role 2: \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_**

| Komponen | Data |
| :---- | :---- |
| Email | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Password | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Role | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**Hak akses:**

| Fitur | Akses |
| :---- | :---: |
| Dashboard | Ya / Tidak |
| Data Utama Projek | Ya / Tidak |
| Tambah Data | Ya / Tidak |
| Edit Data | Ya / Tidak |
| Hapus Data | Ya / Tidak |

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/login\_role2.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/login\_role2.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

| 📷 Bukti Tangkapan Layar: screenshots/dashboard\_role2.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/dashboard\_role2.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**5.3 Login Role 3: \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_**

| Komponen | Data |
| :---- | :---- |
| Email | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Password | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Role | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**Hak akses:**

| Fitur | Akses |
| :---- | :---: |
| Dashboard | Ya / Tidak |
| Melihat Data | Ya / Tidak |
| Mengubah Data | Ya / Tidak |
| Management User | Ya / Tidak |

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/login\_role3.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/login\_role3.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

| 📷 Bukti Tangkapan Layar: screenshots/dashboard\_role3.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/dashboard\_role3.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**VI. MANAGEMENT PENGGUNA**

**6.1 Deskripsi**

Management pengguna merupakan fitur yang digunakan oleh role yang memiliki hak akses untuk mengelola akun pengguna sistem.

**Fitur meliputi:**

**•** Melihat daftar pengguna.

**•** Menambahkan pengguna baru.

**•** Mengubah data pengguna.

**•** Menghapus pengguna.

**•** Mengatur role pengguna.

**VII. STRUKTUR DATA PENGGUNA**

| 📌 Petunjuk: *Sesuaikan dengan tabel users pada database projek.* |
| :---- |

| Field | Tipe Data | Keterangan |
| :---- | :---- | :---- |
| id | bigint | Primary Key |
| nama | varchar | Nama pengguna |
| email | varchar | Email pengguna |
| password | varchar | Password terenkripsi |
| role | varchar | Hak akses pengguna |
| created\_at | timestamp | Waktu dibuat |
| updated\_at | timestamp | Waktu perubahan |

**VIII. CRUD MANAGEMENT PENGGUNA**

**8.1 Create User**

**Tujuan:**

Membuat akun pengguna baru.

**Data input:**

| Field | Nilai |
| :---- | :---- |
| Nama | \_\_\_\_\_\_\_\_\_\_ |
| Email | \_\_\_\_\_\_\_\_\_\_ |
| Password | \_\_\_\_\_\_\_\_\_\_ |
| Role | \_\_\_\_\_\_\_\_\_\_ |

**Langkah:**

**1\.** Login menggunakan role yang memiliki akses management user.

**2\.** Membuka menu pengguna.

**3\.** Klik tambah pengguna.

**4\.** Mengisi data pengguna.

**5\.** Menyimpan data.

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/user\_create.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/user\_create.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**8.2 Read User**

**Tujuan:**

Menampilkan seluruh data pengguna yang tersimpan.

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/user\_list.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/user\_list.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**8.3 Update User**

**Tujuan:**

Mengubah informasi pengguna.

**Data yang dapat diubah:**

| Field | Status |
| :---- | :---: |
| Nama | Bisa / Tidak |
| Email | Bisa / Tidak |
| Role | Bisa / Tidak |

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/user\_edit.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/user\_edit.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**8.4 Delete User**

**Tujuan:**

Menghapus akun pengguna.

**Langkah:**

**1\.** Memilih pengguna.

**2\.** Klik tombol hapus.

**3\.** Konfirmasi penghapusan.

**Screenshot:**

| 📷 Bukti Tangkapan Layar: screenshots/user\_delete.png |
| ----- |
| ***\[ Tempelkan Tangkapan Layar: screenshots/user\_delete.png \]*** *Pastikan gambar terbaca jelas, memperlihatkan URL/halaman serta akun role aktif.* |

**IX. MATRIKS HAK AKSES ROLE**

| Fitur | Role 1 | Role 2 | Role 3 |
| :---- | :---: | :---: | :---: |
| Login | **✓** | **✓** | **✓** |
| Dashboard |  |  |  |
| Melihat Data |  |  |  |
| Tambah Data |  |  |  |
| Edit Data |  |  |  |
| Hapus Data |  |  |  |
| Management User |  |  |  |

**X. HASIL PENGUJIAN**

| No | Pengujian | Hasil |
| :---: | :---- | :---: |
| 1 | Login setiap role berhasil | Berhasil / Gagal |
| 2 | Redirect halaman sesuai role | Berhasil / Gagal |
| 3 | Pembatasan akses berjalan | Berhasil / Gagal |
| 4 | Tambah pengguna berhasil | Berhasil / Gagal |
| 5 | Edit pengguna berhasil | Berhasil / Gagal |
| 6 | Hapus pengguna berhasil | Berhasil / Gagal |

**XI. INFORMASI REPOSITORY**

| Komponen | Data |
| :---- | :---- |
| URL Repository | https\://github.com/\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Nama Repository | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Owner | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |
| Branch | \_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_\_ |

**XII. STRUKTUR FILE IMPLEMENTASI**

| File | Fungsi |
| :---- | :---- |
| app/Models/User.php | Model pengguna |
| app/Http/Controllers/AuthController.php | Proses login |
| app/Http/Controllers/UserController.php | CRUD pengguna |
| routes/web.php | Routing sistem |
| database/migrations | Struktur database |
| database/seeders | Data pengguna dummy |
| resources/views | Tampilan aplikasi |

**XIII. CHECKLIST PENGUMPULAN**

| No | Komponen | Status |
| :---: | :---- | :---: |
| 1 | Source code sudah di repository | ☐ |
| 2 | Link repository tersedia | ☐ |
| 3 | Dokumentasi login selesai | ☐ |
| 4 | Dokumentasi role selesai | ☐ |
| 5 | Dokumentasi CRUD pengguna selesai | ☐ |
| 6 | Screenshot pengujian tersedia | ☐ |

