<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::create(['nama_tahun' => '2024/2025', 'semester' => 'Ganjil', 'status' => 'aktif']);
    }
}
