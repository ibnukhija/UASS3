<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('nama_item');
            $table->string('kategori');
            $table->integer('harga_beli')->default(0);
            $table->integer('harga_jual')->default(0);
            $table->integer('stok')->default(0);
            $table->string('foto');
            $table->timestamps();
            
            #untuk pencarian pada index
            $table->index('nama_item');
            $table->index('kategori');
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
