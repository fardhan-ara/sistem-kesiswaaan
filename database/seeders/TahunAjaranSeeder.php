<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjarans = [
            [
                'tahun_ajaran' => '2023/2024',
                'tahun_mulai' => 2023,
                'tahun_selesai' => 2024,
                'semester' => 'ganjil',
                'status_aktif' => 'nonaktif',
            ],
            [
                'tahun_ajaran' => '2024/2025',
                'tahun_mulai' => 2024,
                'tahun_selesai' => 2025,
                'semester' => 'ganjil',
                'status_aktif' => 'aktif',
            ],
            [
                'tahun_ajaran' => '2025/2026',
                'tahun_mulai' => 2025,
                'tahun_selesai' => 2026,
                'semester' => 'ganjil',
                'status_aktif' => 'nonaktif',
            ],
        ];

        foreach ($tahunAjarans as $tahunAjaran) {
            TahunAjaran::create($tahunAjaran);
        }
    }
}
