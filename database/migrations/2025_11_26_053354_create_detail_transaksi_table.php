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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('detail_id');

            $table->unsignedBigInteger('transaksi_id');
            $table->foreign('transaksi_id')->references('transaksi_id')->on('transaksi')->onDelete('cascade');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');

            $table->integer('jumlah')->default(0);
            $table->double('harga_jual_saat_itu')->default(0);
            $table->timestamps();

            $table->index(['transaksi_id']);
            $table->index(['item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
