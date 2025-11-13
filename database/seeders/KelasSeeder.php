<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::create(['nama_kelas' => 'X RPL 1']);
        Kelas::create(['nama_kelas' => 'XI TKJ 1']);
        Kelas::create(['nama_kelas' => 'XII MM 1']);
    }
}
