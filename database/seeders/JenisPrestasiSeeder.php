<?php

namespace Database\Seeders;

use App\Models\JenisPrestasi;
use Illuminate\Database\Seeder;

class JenisPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        JenisPrestasi::create(['nama_prestasi' => 'Juara 1 Lomba Akademik', 'poin_reward' => 50]);
        JenisPrestasi::create(['nama_prestasi' => 'Juara 2 Lomba Olahraga', 'poin_reward' => 30]);
        JenisPrestasi::create(['nama_prestasi' => 'Juara 3 Lomba Seni', 'poin_reward' => 20]);
    }
}
