<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use Illuminate\Database\Seeder;

class JenisPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        JenisPelanggaran::create(['nama_pelanggaran' => 'Terlambat', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran lisan']);
        JenisPelanggaran::create(['nama_pelanggaran' => 'Tidak mengerjakan tugas', 'poin' => 10, 'sanksi_rekomendasi' => 'Membuat tugas tambahan']);
        JenisPelanggaran::create(['nama_pelanggaran' => 'Tidak memakai seragam', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua']);
        JenisPelanggaran::create(['nama_pelanggaran' => 'Berkelahi', 'poin' => 50, 'sanksi_rekomendasi' => 'Skorsing 3 hari']);
        JenisPelanggaran::create(['nama_pelanggaran' => 'Merokok', 'poin' => 100, 'sanksi_rekomendasi' => 'Skorsing 1 minggu']);
    }
}
