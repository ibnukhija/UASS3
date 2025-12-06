<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Memanggil seeder User (Owner)
            UserSeeder::class,
            
            // Memanggil seeder Kategori
            KategoriSeeder::class,
        ]);
    }
}