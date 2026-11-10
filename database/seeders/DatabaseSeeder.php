<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smansaba.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Guru BK (Untuk Testing)
        User::create([
            'name' => 'Bapak/Ibu Guru BK',
            'email' => 'gurubk@smansaba.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru_bk',
        ]);

        // 3. Membuat Akun Siswa (Untuk Testing)
        User::create([
            'name' => 'Siswa SMANSABA',
            'email' => 'siswa@smansaba.sch.id', // Bisa juga menggunakan NISN nanti
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);
    }
}