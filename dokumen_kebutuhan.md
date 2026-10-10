# Dokumen Kebutuhan Perangkat Lunak — Versi Awal 
**Nama Proyek:** Point of Sale (POS) Kedai Kopi
**Nama Kelompok:** Kelompok 8
| No | Nama Anggota | NIM | Peran |
|---:|---|---|---|
| 1 | Ahmad Faizal Bahri | 2495114009 | Project Manager, Reviewer / QA |
| 2 | Muhammad Lutfi Maulana | 2495114039 | Requirement Analyst, Reviewer / QA |
---
## 1. Daftar Aktor & Peran 
1. **Pemilik:** Melihat laporan pendapatan, melihat kondisi stok.
2. **Admin:** Mengelola data menu, mengelola harga, mengelola bahan baku, melakukan penyesuaian stok.
3. **Kasir:** Mencatat transaksi, memilih menu yang dipesan, memasukkan jumlah pesanan.
## 2. Kebutuhan Fungsional
- **F-01:** Admin dapat menambah, mengubah, dan menghapus data menu beserta harga jualnya.
- **F-02:** Admin dapat menambah, mengubah, melihat, dan menghapus data bahan baku.
- **F-03:** Admin dapat mengatur komposisi bahan baku pada setiap menu.
- **F-04:** Kasir dapat mencatat transaksi penjualan.
- **F-05:** Kasir dapat melihat daftar menu dan harga yang tersedia saat mencatat transaksi.
- **F-06:** Pemilik dapat melihat laporan penjualan bulanan.
- **F-07:** Pemilik dapat melihat informasi stok bahan baku yang tersedia.
## 3. Kebutuhan Nonfungsional
1. **Performa:** Sistem bisa memberikan respons terhadap proses transaksi dalam waktu maksimal 3 detik pada kondisi penggunaan normal.
2. **Keandalan:** Sistem harus dapat digunakan selama jam operasional kedai dengan tingkat ketersediaan minimal 99% per bulan, tidak termasuk waktu pemeliharaan terjadwal.
3. **Keakuratan:** Sistem harus menjaga keakuratan data stok bahan baku setelah transaksi.
## 4. Batasan Sistem
- Sistem hanya digunakan untuk mengelola transaksi dan stok bahan baku pada satu kedai kopi dan tidak mencakup pengelolaan cabang lain.
- Sistem hanya mencatat metode pembayaran yang digunakan pada transaksi dan tidak terintegrasi secara langsung dengan payment gateway atau mesin pembayaran.
- Sistem tidak mencakup pengadaan atau pemesanan bahan baku kepada supplier secara otomatis.
## 5. User Story & Acceptance Criteria
### US-01
> Sebagai kasir, saya ingin mencatat transaksi pesanan pelanggan, sehingga transaksi penjualan dapat tercatat secara digital dan total pembayaran dapat dihitung oleh sistem.
- [ ] AC-1: Kasir dapat memilih menu dan jumlah pesanan.
- [ ] AC-2: Sistem menghitung total harga berdasarkan menu dan jumlah yang dipilih.
- [ ] AC-3: Setelah transaksi disimpan, data transaksi tercatat dalam sistem.
### US-02
> Sebagai admin, saya ingin mengelola data bahan baku dan komposisi bahan setiap menu, sehingga sistem dapat menghitung dan mengurangi stok secara otomatis ketika terjadi penjualan.
- [ ] AC-1: Admin dapat menambahkan dan mengubah data bahan baku.
- [ ] AC-2: Admin dapat menentukan bahan dan jumlah yang digunakan untuk setiap menu.
- [ ] AC-3: Ketika transaksi berhasil, sistem mengurangi stok berdasarkan komposisi bahan menu yang terjual.
### US-03
> Sebagai pemilik, saya ingin melihat laporan pendapatan bulanan, sehingga saya dapat mengetahui hasil penjualan kedai pada setiap bulannya.
- [ ] AC-1: Sistem menampilkan total transaksi pada bulan yang dipilih.
- [ ] AC-2: Sistem menampilkan total pendapatan berdasarkan transaksi yang tercatat.
- [ ] AC-3: Laporan hanya dapat diakses oleh pengguna dengan hak akses pemilik.
## 6. Use Case Naratif
- **Nama Use Case:** Mencatat penjualan dan memperbarui stok bahan baku secara otomatis.
- **Aktor utama:** Kasir
- **Prekondisi:**
    * Kasir sudah login.
    * Data menu tersedia.
    * Data komposisi bahan setiap menu sudah tersedia.
    * Stok bahan baku tercatat dalam sistem.
- **Alur utama:**
    1. Kasir membuka halaman transaksi.
    2. Sistem menampilkan daftar menu.
    3. Kasir memilih menu yang dipesan pelanggan.
    4. Kasir memasukkan jumlah masing-masing menu.
    5. Sistem menghitung total transaksi.
    6. Kasir mengonfirmasi transaksi.
    7. Sistem menyimpan transaksi.
    8. Sistem menghitung kebutuhan bahan berdasarkan menu dan jumlah yang terjual.
    9. Sistem mengurangi stok bahan baku secara otomatis.
    10. Sistem menampilkan bahwa transaksi berhasil disimpan.
- **Pascakondisi:** Data transaksi tersimpan dan stok bahan baku telah diperbarui sesuai bahan yang digunakan.
