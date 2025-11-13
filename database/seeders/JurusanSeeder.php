<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'MM', 'nama' => 'Multimedia'],
        ];

        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }
    }
}
