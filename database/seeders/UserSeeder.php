<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'nama' => 'Siswa ' . $i,
                'email' => 'siswa' . $i . '@test.com',
                'password' => Hash::make('password'),
                'role' => 'siswa'
            ]);
        }
    }
}
