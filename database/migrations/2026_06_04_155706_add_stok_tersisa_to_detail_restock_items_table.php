<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menambah kolom stok tersisa
    public function up(): void
    {
        Schema::table('detail_restock_items', function (Blueprint $table) {
            $table->integer('stok_tersisa')->default(0)->after('jumlah');
        });
    }

    // Menghapus kolom stok tersisa
    public function down(): void
    {
        Schema::table('detail_restock_items', function (Blueprint $table) {
            $table->dropColumn('stok_tersisa');
        });
    }
};