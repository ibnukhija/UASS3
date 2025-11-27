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
        Schema::create('incoming_item_details', function (Blueprint $table) {
            $table->id('detail_id');

            #untuk menyambungkan table transaksi ke table user
            $table->unsignedBigInteger('restock_id');
            $table->foreign('restock_id')->references('restock_id')->on('restock_items')->onDelete('cascade');

            #untuk menyambungkan table transaksi ke table user
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');

            $table->integer('jumlah')->default(0);
            $table->double('harga_beli_saat_itu')->default(0);
            $table->timestamps();

            $table->index(['restock_id']);
            $table->index(['item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_restock_items');
    }
};
