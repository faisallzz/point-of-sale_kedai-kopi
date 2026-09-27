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
    Schema::create('laporan_penjualans', function (Blueprint $table) {
        $table->id(); // idLaporan: int
        $table->integer('bulan'); // bulan: int
        $table->decimal('total_pendapatan', 14, 2)->default(0); // totalPendapatan: double
        $table->integer('total_transaksi')->default(0); // totalTransaksi: int
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualans');
    }
};
