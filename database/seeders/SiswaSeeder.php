<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::all();
        $tahunAjaran = TahunAjaran::where('status_aktif', 'aktif')->first();

        if ($kelasList->isEmpty() || !$tahunAjaran) {
            $this->command->warn('Kelas atau Tahun Ajaran tidak ditemukan. Jalankan seeder Kelas dan TahunAjaran terlebih dahulu.');
            return;
        }

        $siswas = [
            ['nis' => '2024001', 'nama_siswa' => 'Andi Wijaya', 'jenis_kelamin' => 'L'],
            ['nis' => '2024002', 'nama_siswa' => 'Siti Nurhaliza', 'jenis_kelamin' => 'P'],
            ['nis' => '2024003', 'nama_siswa' => 'Budi Setiawan', 'jenis_kelamin' => 'L'],
            ['nis' => '2024004', 'nama_siswa' => 'Dewi Sartika', 'jenis_kelamin' => 'P'],
            ['nis' => '2024005', 'nama_siswa' => 'Rizki Pratama', 'jenis_kelamin' => 'L'],
            ['nis' => '2024006', 'nama_siswa' => 'Fitri Handayani', 'jenis_kelamin' => 'P'],
            ['nis' => '2024007', 'nama_siswa' => 'Doni Saputra', 'jenis_kelamin' => 'L'],
            ['nis' => '2024008', 'nama_siswa' => 'Rina Wati', 'jenis_kelamin' => 'P'],
        ];

        foreach ($siswas as $index => $siswaData) {
            $user = User::create([
                'nama' => $siswaData['nama_siswa'],
                'email' => strtolower(str_replace(' ', '', $siswaData['nama_siswa'])) . '@siswa.test',
                'password' => Hash::make('testing'),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'users_id' => $user->id,
                'nis' => $siswaData['nis'],
                'nama_siswa' => $siswaData['nama_siswa'],
                'jenis_kelamin' => $siswaData['jenis_kelamin'],
                'kelas_id' => $kelasList[$index % $kelasList->count()]->id,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]);
        }
    }
}
