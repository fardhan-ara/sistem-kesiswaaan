<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $guruData = [
            ['nama' => 'Budi Santoso', 'nip' => '198501012010011001', 'bidang_studi' => 'Matematika'],
            ['nama' => 'Siti Aminah', 'nip' => '198602022010012001', 'bidang_studi' => 'Bahasa Indonesia'],
            ['nama' => 'Ahmad Fauzi', 'nip' => '198703032010011002', 'bidang_studi' => 'Bahasa Inggris'],
            ['nama' => 'Dewi Lestari', 'nip' => '198804042010012002', 'bidang_studi' => 'Pemrograman'],
            ['nama' => 'Rizki Ramadhan', 'nip' => '198905052010011003', 'bidang_studi' => 'Jaringan Komputer'],
        ];

        foreach ($guruData as $data) {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => strtolower(str_replace(' ', '', $data['nama'])) . '@guru.test',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]);

            Guru::create([
                'users_id' => $user->id,
                'nip' => $data['nip'],
                'nama_guru' => $data['nama'],
                'bidang_studi' => $data['bidang_studi'],
                'status' => 'aktif',
            ]);
        }
    }
}
