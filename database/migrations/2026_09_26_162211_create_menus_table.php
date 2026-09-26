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
    Schema::create('menus', function (Blueprint $table) {
        $table->id(); // PK otomatis representasi idMenu di Class Diagram
        $table->string('nama_menu', 100); // representasi namaMenu: string
        $table->decimal('harga_jual', 10, 2); // representasi hargaJual: double
        $table->timestamps(); // Default Laravel
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
