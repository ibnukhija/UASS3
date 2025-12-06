<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Perhatikan namespace modelnya (User atau Users)
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Pastikan menggunakan Model yang benar (User atau Users sesuai aplikasi Anda)
        // Di percakapan sebelumnya kita sepakat pakai App\Models\Users
        
        \App\Models\Users::create([
            'nama' => 'Pemilik Bengkel',
            'username' => 'husna',
            'password' => Hash::make('husna123') 
        ]);
    }
}