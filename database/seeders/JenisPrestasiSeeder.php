<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPrestasiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // AKADEMIK (15 data)
            ['nama_prestasi' => 'Juara 1 Olimpiade Matematika Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 2 Olimpiade Matematika Tingkat Kota', 'poin_reward' => 40],
            ['nama_prestasi' => 'Juara 3 Olimpiade Matematika Tingkat Kota', 'poin_reward' => 30],
            ['nama_prestasi' => 'Juara 1 Olimpiade Fisika Tingkat Provinsi', 'poin_reward' => 70],
            ['nama_prestasi' => 'Juara 1 Olimpiade Kimia Tingkat Sekolah', 'poin_reward' => 25],
            ['nama_prestasi' => 'Juara 1 Olimpiade Biologi Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah Tingkat Nasional', 'poin_reward' => 80],
            ['nama_prestasi' => 'Juara 2 Lomba Karya Tulis Ilmiah Tingkat Provinsi', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Lomba Debat Bahasa Inggris Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Lomba Cerdas Cermat Tingkat Kota', 'poin_reward' => 45],
            ['nama_prestasi' => 'Ranking 1 Kelas', 'poin_reward' => 20],
            ['nama_prestasi' => 'Ranking 2 Kelas', 'poin_reward' => 15],
            ['nama_prestasi' => 'Ranking 3 Kelas', 'poin_reward' => 10],
            ['nama_prestasi' => 'Nilai Sempurna Ujian Nasional', 'poin_reward' => 30],
            ['nama_prestasi' => 'Lulus Sertifikasi Bahasa Inggris (TOEFL/IELTS)', 'poin_reward' => 35],
            
            // OLAHRAGA (15 data)
            ['nama_prestasi' => 'Juara 1 Sepak Bola Tingkat Provinsi', 'poin_reward' => 70],
            ['nama_prestasi' => 'Juara 2 Sepak Bola Tingkat Kota', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Basket Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Voli Tingkat Sekolah', 'poin_reward' => 25],
            ['nama_prestasi' => 'Juara 1 Bulu Tangkis Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Atletik Lari 100m Tingkat Provinsi', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Renang Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Pencak Silat Tingkat Nasional', 'poin_reward' => 80],
            ['nama_prestasi' => 'Juara 2 Pencak Silat Tingkat Provinsi', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Karate Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Taekwondo Tingkat Provinsi', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Tenis Meja Tingkat Kota', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Futsal Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Catur Tingkat Provinsi', 'poin_reward' => 60],
            ['nama_prestasi' => 'Best Player Turnamen Olahraga', 'poin_reward' => 40],
            
            // SENI & BUDAYA (10 data)
            ['nama_prestasi' => 'Juara 1 Lomba Menyanyi Tingkat Nasional', 'poin_reward' => 75],
            ['nama_prestasi' => 'Juara 1 Lomba Tari Tradisional Tingkat Provinsi', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Lomba Band Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Lomba Melukis Tingkat Kota', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Lomba Fotografi Tingkat Provinsi', 'poin_reward' => 60],
            ['nama_prestasi' => 'Juara 1 Lomba Desain Grafis Tingkat Nasional', 'poin_reward' => 80],
            ['nama_prestasi' => 'Juara 1 Lomba Puisi Tingkat Kota', 'poin_reward' => 45],
            ['nama_prestasi' => 'Juara 1 Lomba Teater Tingkat Provinsi', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Lomba Kaligrafi Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Lomba Musik Tradisional Tingkat Provinsi', 'poin_reward' => 60],
            
            // TEKNOLOGI & INOVASI (5 data)
            ['nama_prestasi' => 'Juara 1 Lomba Robotika Tingkat Nasional', 'poin_reward' => 85],
            ['nama_prestasi' => 'Juara 1 Lomba Programming Tingkat Provinsi', 'poin_reward' => 70],
            ['nama_prestasi' => 'Juara 1 Lomba Web Design Tingkat Kota', 'poin_reward' => 55],
            ['nama_prestasi' => 'Juara 1 Lomba Inovasi Teknologi Tingkat Nasional', 'poin_reward' => 90],
            ['nama_prestasi' => 'Juara 1 Lomba Game Development Tingkat Provinsi', 'poin_reward' => 70],
            
            // KEAGAMAAN & SOSIAL (5 data)
            ['nama_prestasi' => 'Juara 1 MTQ Tingkat Provinsi', 'poin_reward' => 65],
            ['nama_prestasi' => 'Juara 1 Lomba Dai Muda Tingkat Kota', 'poin_reward' => 50],
            ['nama_prestasi' => 'Juara 1 Lomba Hafalan Al-Quran Tingkat Provinsi', 'poin_reward' => 70],
            ['nama_prestasi' => 'Relawan Terbaik Kegiatan Sosial', 'poin_reward' => 35],
            ['nama_prestasi' => 'Duta Anti Narkoba Tingkat Kota', 'poin_reward' => 45],
        ];

        DB::table('jenis_prestasis')->insert($data);
    }
}
