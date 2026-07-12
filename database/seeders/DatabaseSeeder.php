<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi database dengan data awal.
     *
     * Jalankan perintah: php artisan db:seed
     * Atau untuk reset total: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // ====================================
        // Buat Akun Admin
        // ====================================
        // Akun ini digunakan untuk mengelola semua data
        // Admin TIDAK perlu register lewat form, cukup lewat seeder ini

        User::create([
            'nama'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'peran'    => 'admin',
        ]);

        // ====================================
        // Buat Akun User Biasa (untuk testing)
        // ====================================
        // Akun ini untuk menguji tampilan dashboard user
        // User baru bisa juga mendaftar lewat halaman register

        User::create([
            'nama'     => 'User Biasa',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'peran'    => 'user',
        ]);
    }
}
