<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::where('status_aktif', 'aktif')->first();
        $gurus = Guru::all();

        if (!$tahunAjaran) {
            $this->command->warn('Tahun Ajaran aktif tidak ditemukan.');
            return;
        }

        $kelasData = [
            ['nama_kelas' => 'X RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'X RPL 2', 'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'X TKJ 1', 'jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_kelas' => 'XI RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'XI TKJ 1', 'jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_kelas' => 'XII RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak'],
        ];

        foreach ($kelasData as $index => $data) {
            Kelas::create([
                'nama_kelas' => $data['nama_kelas'],
                'jurusan' => $data['jurusan'],
                'wali_kelas_id' => $gurus->skip($index)->first()->id ?? null,
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]);
        }
    }
}
