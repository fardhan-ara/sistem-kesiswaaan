<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Admin System',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'nama' => 'Staff Kesiswaan',
                'email' => 'kesiswaan@test.com',
                'password' => Hash::make('password'),
                'role' => 'kesiswaan',
            ],
            [
                'nama' => 'Guru Test',
                'email' => 'guru@test.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ],
            [
                'nama' => 'Wali Kelas Test',
                'email' => 'walikelas@test.com',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
            ],
            [
                'nama' => 'Siswa Test',
                'email' => 'siswa@test.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ],
            [
                'nama' => 'Orang Tua Test',
                'email' => 'ortu@test.com',
                'password' => Hash::make('password'),
                'role' => 'ortu',
            ],
        ];

        foreach ($users as $userData) {
            $userData['email_verified_at'] = now();
            
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
