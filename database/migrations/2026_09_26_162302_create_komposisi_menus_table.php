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
    Schema::create('komposisi_menus', function (Blueprint $table) {
        $table->id(); // idKomposisi: int 

        // idMenu: int (FK ke menus)
        $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();

        // idBahanBaku: int (FK ke bahan_bakus)
        $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();

        $table->decimal('jumlah_penggunaan', 8, 2); // jumlahPenggunaan: double
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komposisi_menus');
    }
};
