<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ResetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Matikan pengecekan Foreign Key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel TRANSAKSI & DETAILNYA
        DB::table('detail_transaksi')->truncate();
        DB::table('transaksi')->truncate();

        // 3. Kosongkan tabel RESTOCK & DETAILNYA
        DB::table('detail_restock_items')->truncate();
        DB::table('restock_items')->truncate();

        // 4. Kosongkan tabel BARANG
        DB::table('items')->truncate();

        // 5. Nyalakan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints();

        $this->command->info('SUKSES! Data Barang, Stok, dan Transaksi sudah bersih. User AMAN.');
    }
}
