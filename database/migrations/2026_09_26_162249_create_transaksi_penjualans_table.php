<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('transaksi_penjualans', function (Blueprint $table) {
        $table->id(); // idTransaksi: int

        // idPengguna: int (FK ke users)
        $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

        // idLaporan: int (FK ke laporan_penjualans)
        // Wajib nullable() karena saat kasir mencatat transaksi, laporan bulanan belum digenerate
        $table->foreignId('laporan_penjualan_id')->nullable()->constrained('laporan_penjualans')->nullOnDelete();

        $table->string('nomor_transaksi', 50)->unique(); // nomorTransaksi: string
        $table->dateTime('tanggal_transaksi'); // tanggalTransaksi: datetime
        $table->decimal('total_bayar', 12, 2)->default(0); // totalBayar: double
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};
