<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPelanggaran;

class JenisPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggarans = [
            // A. KETERTIBAN
            ['kelompok' => 'A. KETERTIBAN', 'nama_pelanggaran' => 'Membuat kerbau/kegaduhan dalam kelas pada saat berlangsung/mengganggu pelajaran', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KETERTIBAN', 'nama_pelanggaran' => 'Tidak mengikuti kegiatan belajar (membolos)', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KETERTIBAN', 'nama_pelanggaran' => 'Siswa keluar kelas saat proses belajar mengajar berlangsung tanpa izin', 'poin' => 6, 'kategori' => 'ringan'],
            
            // B. PAKAIAN
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Membawa seragam tidak rapi (baju tidak dimasukkan)', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Siswa putri memakai seragam yang ketat atau rok pendek', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Salah memakai baju, rok atau celana', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Salah atau tidak memakai ikat pinggang', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Tidak memakai kaus kaki', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Salah/tidak memakai kaos dalam', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Siswa putri memakai perhiasan perempuan', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. PAKAIAN', 'nama_pelanggaran' => 'Siswa putri memakai perhiasan atau aksesoris (kalung)', 'poin' => 8, 'kategori' => 'ringan'],
            
            // C. RAMBUT
            ['kelompok' => 'C. RAMBUT', 'nama_pelanggaran' => 'Dicukur/rambut-warna (putra-putri)', 'poin' => 15, 'kategori' => 'ringan'],
            
            // D. BUKU, MAJALAH ATAU KASET TERLARANG
            ['kelompok' => 'D. BUKU, MAJALAH ATAU KASET TERLARANG', 'nama_pelanggaran' => 'Membawa buku majalah kaset terlarang atau HP berisi gambar dan film porno', 'poin' => 25, 'kategori' => 'sedang'],
            ['kelompok' => 'D. BUKU, MAJALAH ATAU KASET TERLARANG', 'nama_pelanggaran' => 'Menyebarkan belikan buku, majalah atau kaset terlarang', 'poin' => 75, 'kategori' => 'berat'],
            
            // E. BENKATA
            ['kelompok' => 'E. BENKATA', 'nama_pelanggaran' => 'Membawa senjata tajam tanpa izin', 'poin' => 40, 'kategori' => 'berat'],
            ['kelompok' => 'E. BENKATA', 'nama_pelanggaran' => 'Membawa senjata tajam dengan izin sekolah', 'poin' => 40, 'kategori' => 'berat'],
            ['kelompok' => 'E. BENKATA', 'nama_pelanggaran' => 'Menggunakan senjata tajam untuk mengancam', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'E. BENKATA', 'nama_pelanggaran' => 'Menggunakan senjata tajam', 'poin' => 75, 'kategori' => 'berat'],
            
            // F. OBAT/MINUMAN TERLARANG
            ['kelompok' => 'F. OBAT/MINUMAN TERLARANG', 'nama_pelanggaran' => 'Membawa obat terlarang/minuman terlarang', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'F. OBAT/MINUMAN TERLARANG', 'nama_pelanggaran' => 'Menggunakan obat/minuman terlarang di dalam lingkungan sekolah', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'F. OBAT/MINUMAN TERLARANG', 'nama_pelanggaran' => 'Menggunakan obat/minuman terlarang di dalam/di luar sekolah', 'poin' => 100, 'kategori' => 'sangat_berat'],
            
            // G. PERKELAHIAN
            ['kelompok' => 'G. PERKELAHIAN', 'nama_pelanggaran' => 'Perkelahian dan siswa di dalam sekolah (Intern)', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'G. PERKELAHIAN', 'nama_pelanggaran' => 'Perkelahian dan sekolah lain (ringan)', 'poin' => 25, 'kategori' => 'sedang'],
            ['kelompok' => 'G. PERKELAHIAN', 'nama_pelanggaran' => 'Perkelahian dan sekolah lain (berat)', 'poin' => 75, 'kategori' => 'berat'],
            
            // H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN
            ['kelompok' => 'H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN', 'nama_pelanggaran' => 'Disertai ancaman', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN', 'nama_pelanggaran' => 'Disertai pemukulan', 'poin' => 100, 'kategori' => 'sangat_berat'],
            
            // I. KERAJINAN
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Satu kali terlambat', 'poin' => 2, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Dua kali terlambat', 'poin' => 3, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Tiga kali dan seterusnya terlambat', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Terlambat masuk karena izin', 'poin' => 3, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Terlambat masuk karena tidak izin guru', 'poin' => 2, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Terlambat masuk karena alasan yang tidak dapat dipertanggungjawabkan dan tidak kembali', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Siswa tidak masuk sekolah tanpa izin atau tidak kembali', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'I. KERAJINAN', 'nama_pelanggaran' => 'Pulang tanpa izin', 'poin' => 10, 'kategori' => 'ringan'],
            
            // J. KEHADIRAN
            ['kelompok' => 'J. KEHADIRAN', 'nama_pelanggaran' => 'Sakit tanpa keterangan (surat)', 'poin' => 2, 'kategori' => 'ringan'],
        ];

        foreach ($pelanggarans as $pelanggaran) {
            JenisPelanggaran::create($pelanggaran);
        }
    }
}
