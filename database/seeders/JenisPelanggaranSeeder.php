<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPelanggaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // KETERTIBAN (10 data)
            ['nama_pelanggaran' => 'Terlambat masuk kelas', 'poin' => 2, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'ketertiban', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak mengikuti upacara bendera', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'ketertiban', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Membuat keributan di kelas', 'poin' => 10, 'sanksi_rekomendasi' => 'Panggilan orang tua', 'kategori' => 'ketertiban', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Tidak mengikuti kegiatan belajar (membolos)', 'poin' => 15, 'sanksi_rekomendasi' => 'Skorsing 1 hari', 'kategori' => 'kehadiran', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Keluar kelas tanpa izin saat pelajaran', 'poin' => 6, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'ketertiban', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidur saat pelajaran berlangsung', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'akademik', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak mengerjakan tugas berulang kali', 'poin' => 8, 'sanksi_rekomendasi' => 'Panggilan orang tua', 'kategori' => 'akademik', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Mengganggu teman saat belajar', 'poin' => 7, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'sikap', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak masuk tanpa keterangan', 'poin' => 10, 'sanksi_rekomendasi' => 'Panggilan orang tua', 'kategori' => 'kehadiran', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Pulang sebelum waktunya tanpa izin', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'ketertiban', 'kelompok' => 'sedang'],
            
            // PAKAIAN & PENAMPILAN (15 data)
            ['nama_pelanggaran' => 'Seragam tidak dimasukkan', 'poin' => 3, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak memakai ikat pinggang', 'poin' => 3, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak memakai kaos kaki', 'poin' => 3, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Memakai sepatu tidak sesuai ketentuan', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Seragam tidak lengkap/tidak sesuai', 'poin' => 5, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Rambut tidak rapi (putra)', 'poin' => 8, 'sanksi_rekomendasi' => 'Potong rambut', 'kategori' => 'pakaian', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Rambut dicat/diwarnai', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua + cat hitam', 'kategori' => 'pakaian', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Memakai aksesoris berlebihan', 'poin' => 5, 'sanksi_rekomendasi' => 'Disita + teguran', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Rok terlalu pendek (putri)', 'poin' => 8, 'sanksi_rekomendasi' => 'Ganti seragam', 'kategori' => 'pakaian', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Celana terlalu ketat/pensil', 'poin' => 8, 'sanksi_rekomendasi' => 'Ganti seragam', 'kategori' => 'pakaian', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Memakai make up berlebihan', 'poin' => 5, 'sanksi_rekomendasi' => 'Hapus + teguran', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Kuku panjang/dicat', 'poin' => 3, 'sanksi_rekomendasi' => 'Potong kuku', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Memakai tindik berlebihan', 'poin' => 5, 'sanksi_rekomendasi' => 'Lepas + teguran', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Memakai kaos dalam berwarna', 'poin' => 3, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            ['nama_pelanggaran' => 'Tidak memakai dasi/badge', 'poin' => 3, 'sanksi_rekomendasi' => 'Teguran lisan', 'kategori' => 'pakaian', 'kelompok' => 'ringan'],
            
            // PERILAKU BERAT (15 data)
            ['nama_pelanggaran' => 'Merokok di lingkungan sekolah', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari', 'kategori' => 'kriminal', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Membawa rokok ke sekolah', 'poin' => 20, 'sanksi_rekomendasi' => 'Disita + panggilan ortu', 'kategori' => 'kriminal', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Berkelahi dengan teman', 'poin' => 30, 'sanksi_rekomendasi' => 'Skorsing 5 hari', 'kategori' => 'sikap', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Berkelahi dengan siswa sekolah lain', 'poin' => 50, 'sanksi_rekomendasi' => 'Skorsing 1 minggu', 'kategori' => 'sikap', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Membawa senjata tajam', 'poin' => 40, 'sanksi_rekomendasi' => 'Disita + skorsing', 'kategori' => 'kriminal', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Mengancam teman/guru', 'poin' => 50, 'sanksi_rekomendasi' => 'Skorsing + lapor polisi', 'kategori' => 'kriminal', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Mencuri barang milik sekolah/teman', 'poin' => 40, 'sanksi_rekomendasi' => 'Ganti rugi + skorsing', 'kategori' => 'kriminal', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Merusak fasilitas sekolah', 'poin' => 35, 'sanksi_rekomendasi' => 'Ganti rugi + skorsing', 'kategori' => 'fasilitas', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Membawa/menggunakan narkoba', 'poin' => 100, 'sanksi_rekomendasi' => 'Dikeluarkan + lapor polisi', 'kategori' => 'kriminal', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Membawa minuman keras', 'poin' => 75, 'sanksi_rekomendasi' => 'Skorsing 2 minggu', 'kategori' => 'kriminal', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Berjudi di lingkungan sekolah', 'poin' => 40, 'sanksi_rekomendasi' => 'Skorsing 1 minggu', 'kategori' => 'kriminal', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Melakukan tindakan asusila', 'poin' => 100, 'sanksi_rekomendasi' => 'Dikeluarkan', 'kategori' => 'kriminal', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Membawa konten pornografi', 'poin' => 50, 'sanksi_rekomendasi' => 'Disita + skorsing', 'kategori' => 'kriminal', 'kelompok' => 'sangat_berat'],
            ['nama_pelanggaran' => 'Memalsukan tanda tangan/surat', 'poin' => 30, 'sanksi_rekomendasi' => 'Skorsing 3 hari', 'kategori' => 'akademik', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Menyontek saat ujian', 'poin' => 20, 'sanksi_rekomendasi' => 'Nilai 0 + teguran', 'kategori' => 'akademik', 'kelompok' => 'berat'],
            
            // SIKAP & ETIKA (10 data)
            ['nama_pelanggaran' => 'Berkata kasar kepada teman', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'sikap', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Berkata kasar kepada guru', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari', 'kategori' => 'sikap', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Tidak sopan kepada guru/karyawan', 'poin' => 15, 'sanksi_rekomendasi' => 'Panggilan orang tua', 'kategori' => 'sikap', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Membully teman', 'poin' => 35, 'sanksi_rekomendasi' => 'Skorsing + konseling', 'kategori' => 'sikap', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Menyebarkan hoax/fitnah', 'poin' => 30, 'sanksi_rekomendasi' => 'Klarifikasi + skorsing', 'kategori' => 'sikap', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Menghasut teman untuk berbuat negatif', 'poin' => 25, 'sanksi_rekomendasi' => 'Skorsing 3 hari', 'kategori' => 'sikap', 'kelompok' => 'berat'],
            ['nama_pelanggaran' => 'Tidak mengikuti tata tertib sekolah', 'poin' => 10, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'ketertiban', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Membawa HP saat pelajaran berlangsung', 'poin' => 15, 'sanksi_rekomendasi' => 'Disita 1 minggu', 'kategori' => 'ketertiban', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Main game saat pelajaran', 'poin' => 12, 'sanksi_rekomendasi' => 'Disita HP + teguran', 'kategori' => 'akademik', 'kelompok' => 'sedang'],
            ['nama_pelanggaran' => 'Membuat kegaduhan di perpustakaan', 'poin' => 8, 'sanksi_rekomendasi' => 'Teguran tertulis', 'kategori' => 'ketertiban', 'kelompok' => 'ringan'],
        ];

        DB::table('jenis_pelanggarans')->insert($data);
    }
}
