<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPrestasiSeederNew extends Seeder
{
    public function run()
    {
        $data = [
            // AKADEMIK - SOLO
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'solo', 'poin_reward' => 15],
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'kecamatan', 'kategori_penampilan' => 'solo', 'poin_reward' => 25],
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'nasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 80],
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika', 'tingkat' => 'internasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 100],
            
            ['nama_prestasi' => 'Juara 1 Olimpiade Fisika', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Olimpiade Fisika', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Olimpiade Fisika', 'tingkat' => 'nasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 80],
            
            ['nama_prestasi' => 'Juara 1 Olimpiade Kimia', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Olimpiade Biologi', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Olimpiade Bahasa Inggris', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            
            // AKADEMIK - TIM
            ['nama_prestasi' => 'Juara 1 Lomba Cerdas Cermat', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'tim', 'poin_reward' => 12],
            ['nama_prestasi' => 'Juara 1 Lomba Cerdas Cermat', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Lomba Cerdas Cermat', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'tim', 'poin_reward' => 50],
            
            ['nama_prestasi' => 'Juara 1 Lomba Debat Bahasa Inggris', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Lomba Debat Bahasa Inggris', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'tim', 'poin_reward' => 55],
            
            // OLAHRAGA - SOLO
            ['nama_prestasi' => 'Juara 1 Atletik Lari 100m', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'solo', 'poin_reward' => 15],
            ['nama_prestasi' => 'Juara 1 Atletik Lari 100m', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Atletik Lari 100m', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Atletik Lari 100m', 'tingkat' => 'nasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 80],
            
            ['nama_prestasi' => 'Juara 1 Renang', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Bulu Tangkis', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Tenis Meja', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Catur', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            
            // OLAHRAGA - DUO
            ['nama_prestasi' => 'Juara 1 Bulu Tangkis Ganda', 'tingkat' => 'kota', 'kategori_penampilan' => 'duo', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Tenis Ganda', 'tingkat' => 'kota', 'kategori_penampilan' => 'duo', 'poin_reward' => 35],
            
            // OLAHRAGA - TIM
            ['nama_prestasi' => 'Juara 1 Sepak Bola', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'tim', 'poin_reward' => 12],
            ['nama_prestasi' => 'Juara 1 Sepak Bola', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Sepak Bola', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'tim', 'poin_reward' => 50],
            
            ['nama_prestasi' => 'Juara 1 Basket', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Voli', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Futsal', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 30],
            
            // SENI - SOLO
            ['nama_prestasi' => 'Juara 1 Lomba Menyanyi', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'solo', 'poin_reward' => 15],
            ['nama_prestasi' => 'Juara 1 Lomba Menyanyi', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Lomba Menyanyi', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Lomba Menyanyi', 'tingkat' => 'nasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 80],
            
            ['nama_prestasi' => 'Juara 1 Lomba Melukis', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Lomba Fotografi', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Lomba Desain Grafis', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Lomba Puisi', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Lomba Pidato', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            
            // SENI - DUO/TRIO
            ['nama_prestasi' => 'Juara 1 Lomba Duet Vokal', 'tingkat' => 'kota', 'kategori_penampilan' => 'duo', 'poin_reward' => 35],
            ['nama_prestasi' => 'Juara 1 Lomba Tari Berpasangan', 'tingkat' => 'kota', 'kategori_penampilan' => 'duo', 'poin_reward' => 35],
            
            // SENI - GRUP
            ['nama_prestasi' => 'Juara 1 Lomba Band', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'grup', 'poin_reward' => 12],
            ['nama_prestasi' => 'Juara 1 Lomba Band', 'tingkat' => 'kota', 'kategori_penampilan' => 'grup', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Lomba Tari Tradisional', 'tingkat' => 'kota', 'kategori_penampilan' => 'grup', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Lomba Teater', 'tingkat' => 'kota', 'kategori_penampilan' => 'grup', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Lomba Paduan Suara', 'tingkat' => 'kota', 'kategori_penampilan' => 'kolektif', 'poin_reward' => 25],
            
            // TEKNOLOGI - SOLO
            ['nama_prestasi' => 'Juara 1 Lomba Programming', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Lomba Programming', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Lomba Programming', 'tingkat' => 'nasional', 'kategori_penampilan' => 'solo', 'poin_reward' => 85],
            
            ['nama_prestasi' => 'Juara 1 Lomba Web Design', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Lomba Game Development', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 45],
            
            // TEKNOLOGI - TIM
            ['nama_prestasi' => 'Juara 1 Lomba Robotika', 'tingkat' => 'kota', 'kategori_penampilan' => 'tim', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Lomba Robotika', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'tim', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Lomba Robotika', 'tingkat' => 'nasional', 'kategori_penampilan' => 'tim', 'poin_reward' => 80],
            
            // KEAGAMAAN - SOLO
            ['nama_prestasi' => 'Juara 1 MTQ', 'tingkat' => 'kecamatan', 'kategori_penampilan' => 'solo', 'poin_reward' => 25],
            ['nama_prestasi' => 'Juara 1 MTQ', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 MTQ', 'tingkat' => 'provinsi', 'kategori_penampilan' => 'solo', 'poin_reward' => 60],
            
            ['nama_prestasi' => 'Juara 1 Lomba Hafalan Al-Quran', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Lomba Dai Muda', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 1 Lomba Kaligrafi', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 35],
            
            // PRESTASI LAINNYA
            ['nama_prestasi' => 'Siswa Teladan', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'solo', 'poin_reward' => 20],
            ['nama_prestasi' => 'Siswa Teladan', 'tingkat' => 'kota', 'kategori_penampilan' => 'solo', 'poin_reward' => 50],
            ['nama_prestasi' => 'Siswa Berprestasi', 'tingkat' => 'sekolah', 'kategori_penampilan' => 'solo', 'poin_reward' => 15],
        ];

        DB::table('jenis_prestasis')->insert($data);
    }
}
