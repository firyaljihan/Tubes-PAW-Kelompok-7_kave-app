<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        {
        // Buat 1 Akun Admin Utama
        Admin::create([
            'username' => 'admin1',
            'email' => 'admin1@kave.com',
            'password' => Hash::make('password123'),
        ]);

        // (Opsional) Kalau mau nge-print pesan di terminal biar tau udah sukses
        $this->command->info('Akun Admin berhasil dibuat! Email: admin@kave.com | Pass: password123');
    }
    }
}
