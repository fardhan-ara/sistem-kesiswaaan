<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            ['users_id' => 1, 'nis' => '2024001', 'nama_siswa' => 'Ahmad Fauzi', 'kelas_id' => 1, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
            ['users_id' => 2, 'nis' => '2024002', 'nama_siswa' => 'Siti Nurhaliza', 'kelas_id' => 1, 'jenis_kelamin' => 'P', 'tahun_ajaran_id' => 1],
            ['users_id' => 3, 'nis' => '2024003', 'nama_siswa' => 'Budi Santoso', 'kelas_id' => 1, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
            ['users_id' => 4, 'nis' => '2024004', 'nama_siswa' => 'Dewi Lestari', 'kelas_id' => 2, 'jenis_kelamin' => 'P', 'tahun_ajaran_id' => 1],
            ['users_id' => 5, 'nis' => '2024005', 'nama_siswa' => 'Eko Prasetyo', 'kelas_id' => 2, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
            ['users_id' => 6, 'nis' => '2024006', 'nama_siswa' => 'Fitri Handayani', 'kelas_id' => 2, 'jenis_kelamin' => 'P', 'tahun_ajaran_id' => 1],
            ['users_id' => 7, 'nis' => '2024007', 'nama_siswa' => 'Gilang Ramadhan', 'kelas_id' => 3, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
            ['users_id' => 8, 'nis' => '2024008', 'nama_siswa' => 'Hani Safitri', 'kelas_id' => 3, 'jenis_kelamin' => 'P', 'tahun_ajaran_id' => 1],
            ['users_id' => 9, 'nis' => '2024009', 'nama_siswa' => 'Indra Gunawan', 'kelas_id' => 3, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
            ['users_id' => 10, 'nis' => '2024010', 'nama_siswa' => 'Joko Widodo', 'kelas_id' => 1, 'jenis_kelamin' => 'L', 'tahun_ajaran_id' => 1],
        ];

        foreach ($siswas as $siswa) {
            Siswa::create($siswa);
        }
    }
}
