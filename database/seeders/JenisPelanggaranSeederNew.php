<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelanggaranSeederNew extends Seeder
{
    public function run()
    {
        $data = [
            // KETERTIBAN
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Membuat keributan di kelas', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Keluar kelas tanpa izin', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Tidur saat pelajaran', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Mengganggu teman saat belajar', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Tidak mengikuti upacara bendera', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Membuat kegaduhan di perpustakaan', 'poin' => 8, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'ketertiban', 'nama_pelanggaran' => 'Tidak mengikuti kegiatan ekstrakurikuler wajib', 'poin' => 10, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            
            // KEHADIRAN
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Terlambat masuk kelas (1-15 menit)', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Terlambat masuk kelas (>15 menit)', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Tidak masuk tanpa keterangan (1 hari)', 'poin' => 10, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Tidak masuk tanpa keterangan (2-3 hari)', 'poin' => 20, 'sanksi_rekomendasi' => 'Panggilan orang tua + surat peringatan'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Tidak masuk tanpa keterangan (>3 hari)', 'poin' => 30, 'sanksi_rekomendasi' => 'Skorsing 1 hari'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Membolos saat jam pelajaran', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Pulang sebelum waktunya tanpa izin', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'kehadiran', 'nama_pelanggaran' => 'Meninggalkan sekolah saat jam pelajaran', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            
            // PAKAIAN & PENAMPILAN
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Seragam tidak dimasukkan', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Tidak memakai ikat pinggang', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Tidak memakai kaos kaki', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Tidak memakai dasi/badge', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Sepatu tidak sesuai ketentuan', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Seragam tidak lengkap/tidak sesuai', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Rok terlalu pendek (putri)', 'poin' => 8, 'sanksi_rekomendasi' => 'Ganti seragam + teguran'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Celana terlalu ketat/pensil', 'poin' => 8, 'sanksi_rekomendasi' => 'Ganti seragam + teguran'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Rambut tidak rapi (putra)', 'poin' => 8, 'sanksi_rekomendasi' => 'Potong rambut'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Rambut dicat/diwarnai', 'poin' => 15, 'sanksi_rekomendasi' => 'Cat hitam + panggilan ortu'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Memakai aksesoris berlebihan', 'poin' => 5, 'sanksi_rekomendasi' => 'Disita + teguran'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Memakai make up berlebihan', 'poin' => 5, 'sanksi_rekomendasi' => 'Hapus + teguran'],
            ['kategori' => 'pakaian', 'nama_pelanggaran' => 'Kuku panjang/dicat', 'poin' => 3, 'sanksi_rekomendasi' => 'Potong kuku'],
            
            // SIKAP & ETIKA
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Berkata kasar kepada teman', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Berkata kasar kepada guru', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Tidak sopan kepada guru/karyawan', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Membully teman', 'poin' => 35, 'sanksi_rekomendasi' => 'Skorsing + konseling'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Menyebarkan hoax/fitnah', 'poin' => 30, 'sanksi_rekomendasi' => 'Klarifikasi + skorsing'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Menghasut teman berbuat negatif', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Berbohong kepada guru', 'poin' => 15, 'sanksi_rekomendasi' => 'Teguran tertulis'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Membawa HP saat pelajaran', 'poin' => 15, 'sanksi_rekomendasi' => 'Disita 1 minggu'],
            ['kategori' => 'sikap', 'nama_pelanggaran' => 'Main game saat pelajaran', 'poin' => 12, 'sanksi_rekomendasi' => 'Disita HP + teguran'],
            
            // AKADEMIK
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Tidak mengerjakan tugas', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran lisan'],
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Tidak mengerjakan tugas berulang (3x)', 'poin' => 10, 'sanksi_rekomendasi' => 'Panggilan orang tua'],
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Menyontek saat ujian', 'poin' => 20, 'sanksi_rekomendasi' => 'Nilai 0 + teguran tertulis'],
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Membantu teman menyontek', 'poin' => 20, 'sanksi_rekomendasi' => 'Nilai 0 + teguran tertulis'],
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Memalsukan tanda tangan/surat', 'poin' => 30, 'sanksi_rekomendasi' => 'Skorsing 3 hari'],
            ['kategori' => 'akademik', 'nama_pelanggaran' => 'Merusak/menghilangkan nilai', 'poin' => 35, 'sanksi_rekomendasi' => 'Skorsing 5 hari'],
            
            // FASILITAS
            ['kategori' => 'fasilitas', 'nama_pelanggaran' => 'Mencoret-coret meja/kursi', 'poin' => 10, 'sanksi_rekomendasi' => 'Bersihkan + teguran'],
            ['kategori' => 'fasilitas', 'nama_pelanggaran' => 'Mencoret-coret dinding/tembok', 'poin' => 15, 'sanksi_rekomendasi' => 'Cat ulang + teguran'],
            ['kategori' => 'fasilitas', 'nama_pelanggaran' => 'Merusak fasilitas sekolah (ringan)', 'poin' => 20, 'sanksi_rekomendasi' => 'Ganti rugi + teguran'],
            ['kategori' => 'fasilitas', 'nama_pelanggaran' => 'Merusak fasilitas sekolah (berat)', 'poin' => 35, 'sanksi_rekomendasi' => 'Ganti rugi + skorsing'],
            ['kategori' => 'fasilitas', 'nama_pelanggaran' => 'Membuang sampah sembarangan', 'poin' => 5, 'sanksi_rekomendasi' => 'Piket kebersihan'],
            
            // KRIMINAL & BERAT
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Merokok di lingkungan sekolah', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Membawa rokok ke sekolah', 'poin' => 20, 'sanksi_rekomendasi' => 'Disita + panggilan ortu'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Berkelahi dengan teman', 'poin' => 30, 'sanksi_rekomendasi' => 'Skorsing 5 hari'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Berkelahi dengan siswa sekolah lain', 'poin' => 50, 'sanksi_rekomendasi' => 'Skorsing 1 minggu'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Membawa senjata tajam', 'poin' => 40, 'sanksi_rekomendasi' => 'Disita + skorsing'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Mengancam teman/guru', 'poin' => 50, 'sanksi_rekomendasi' => 'Skorsing + lapor polisi'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Mencuri barang milik sekolah/teman', 'poin' => 40, 'sanksi_rekomendasi' => 'Ganti rugi + skorsing'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Membawa minuman keras', 'poin' => 75, 'sanksi_rekomendasi' => 'Skorsing 2 minggu'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Membawa/menggunakan narkoba', 'poin' => 100, 'sanksi_rekomendasi' => 'Dikeluarkan + lapor polisi'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Berjudi di lingkungan sekolah', 'poin' => 40, 'sanksi_rekomendasi' => 'Skorsing 1 minggu'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Melakukan tindakan asusila', 'poin' => 100, 'sanksi_rekomendasi' => 'Dikeluarkan'],
            ['kategori' => 'kriminal', 'nama_pelanggaran' => 'Membawa konten pornografi', 'poin' => 50, 'sanksi_rekomendasi' => 'Disita + skorsing'],
        ];

        DB::table('jenis_pelanggarans')->insert($data);
    }
}
