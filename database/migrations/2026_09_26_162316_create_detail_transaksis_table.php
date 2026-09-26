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
    Schema::create('detail_transaksis', function (Blueprint $table) {
        $table->id(); // Mewakili idDetail: int 
        
        // Mewakili idTransaksi: int (FK ke tabel transaksi_penjualans) 
        // cascadeOnDelete() digunakan karena di Class Diagram relasinya adalah Komposisi (tanda wajik hitam)
        $table->foreignId('transaksi_penjualan_id')
              ->constrained('transaksi_penjualans')
              ->cascadeOnDelete();

        // Mewakili idMenu: int (FK ke tabel menus) 
        // restrictOnDelete() menjaga integritas riwayat penjualan agar menu tidak terhapus jika pernah terjual
        $table->foreignId('menu_id')
              ->constrained('menus')
              ->restrictOnDelete();

        $table->integer('jumlah'); // Mewakili jumlah: int 
        $table->decimal('subtotal', 12, 2); // Mewakili subtotal: double 
        
        $table->timestamps(); // Standar Laravel [USULAN]
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
