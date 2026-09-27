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
    Schema::create('bahan_bakus', function (Blueprint $table) {
        $table->id(); // idBahanBaku: int
        $table->string('nama_bahan_baku', 100); // namaBahanBaku: string
        $table->decimal('stok', 10, 2)->default(0); // stok: double
        $table->string('satuan', 20); // satuan: string
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
