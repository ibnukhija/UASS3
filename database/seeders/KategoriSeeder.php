<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategoriList = [
            'Oli & Pelumas',
            'Sistem Pengereman',  // Kampas rem, handle, minyak rem
            'Kelistrikan & Lampu', // Aki, busi, bolham
            'Ban & Velg',
            'Mesin & Transmisi',   // Vanbelt, roller, gear set
            'Filter & Karburator',
            'Body & Aksesoris',    // Spion, cover body
            'Suspensi & Kaki-kaki', // Shockbreaker
            'Kabel & Selang',
            'Baut & Mur',
            'Lainnya'
        ];

        foreach($kategoriList as $k) {
            Kategori::create(['nama_kategori' => $k]);
        }
    }
}