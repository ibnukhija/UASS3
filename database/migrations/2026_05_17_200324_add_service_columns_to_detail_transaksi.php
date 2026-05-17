<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Mengubah item_id agar boleh kosong (nullable)
            $table->unsignedBigInteger('item_id')->nullable()->change();

            // Menambahkan kolom baru
            $table->string('nama_service')->nullable()->after('item_id');
            $table->enum('tipe', ['barang', 'service'])->default('barang')->after('nama_service');
        });
    }

    public function down()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable(false)->change();
            $table->dropColumn(['nama_service', 'tipe']);
        });
    }
};
