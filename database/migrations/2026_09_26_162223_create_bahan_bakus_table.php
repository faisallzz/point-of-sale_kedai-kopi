<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan_baku', 100);
            $table->decimal('stok', 10, 2);
            $table->string('satuan', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};