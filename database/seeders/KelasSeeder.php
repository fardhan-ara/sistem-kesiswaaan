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

        $jurusans = ['PPLG', 'BDP', 'AKT', 'DKV', 'ANM'];
        $jenjang = ['X', 'XI', 'XII'];
        $kelasData = [];

        foreach ($jenjang as $tingkat) {
            foreach ($jurusans as $jurusan) {
                $kelasData[] = [
                    'nama_kelas' => $tingkat . ' ' . $jurusan,
                    'jurusan' => $jurusan
                ];
            }
        }

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
