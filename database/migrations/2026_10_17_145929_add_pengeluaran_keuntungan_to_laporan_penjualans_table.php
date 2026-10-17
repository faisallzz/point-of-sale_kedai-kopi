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
        Schema::table('laporan_penjualans', function (Blueprint $table) {
            $table->decimal('total_pengeluaran', 14, 2)->default(0)->after('total_pendapatan');
            $table->decimal('total_keuntungan', 14, 2)->default(0)->after('total_pengeluaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_penjualans', function (Blueprint $table) {
            $table->dropColumn(['total_pengeluaran', 'total_keuntungan']);
        });
    }
};
