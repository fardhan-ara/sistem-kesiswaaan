<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'nama' => 'Guru BK',
            'email' => 'guru@test.com',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);

        Guru::create([
            'users_id' => $user->id,
            'nip' => '198501012010011001',
            'nama_guru' => 'Guru BK'
        ]);
    }
}
