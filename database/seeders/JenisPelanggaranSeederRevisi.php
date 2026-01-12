<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPelanggaran;

class JenisPelanggaranSeederRevisi extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        JenisPelanggaran::truncate();

        $pelanggarans = [
            // A. KEHADIRAN & KETERLAMBATAN
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Terlambat masuk sekolah (1-15 menit)', 'poin' => 2, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Terlambat masuk sekolah (16-30 menit)', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Terlambat masuk sekolah (lebih dari 30 menit)', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Tidak masuk sekolah tanpa keterangan (alfa)', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Sakit tanpa surat keterangan dokter/orang tua', 'poin' => 3, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Meninggalkan sekolah tanpa izin (membolos)', 'poin' => 15, 'kategori' => 'ringan'],
            ['kelompok' => 'A. KEHADIRAN & KETERLAMBATAN', 'nama_pelanggaran' => 'Pulang sebelum waktunya tanpa izin', 'poin' => 10, 'kategori' => 'ringan'],

            // B. KETERTIBAN & KEDISIPLINAN
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Membuat keributan di dalam kelas saat pembelajaran', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Keluar masuk kelas tanpa izin saat pembelajaran', 'poin' => 8, 'kategori' => 'ringan'],
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Tidak mengikuti upacara bendera tanpa alasan jelas', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Tidak mengerjakan tugas/PR berulang kali', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Tidur saat pembelajaran berlangsung', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'B. KETERTIBAN & KEDISIPLINAN', 'nama_pelanggaran' => 'Bermain kartu/judi di lingkungan sekolah', 'poin' => 50, 'kategori' => 'berat'],

            // C. SERAGAM & PENAMPILAN
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Seragam tidak dimasukkan/tidak rapi', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Tidak memakai ikat pinggang', 'poin' => 3, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Tidak memakai kaos kaki sesuai ketentuan', 'poin' => 3, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Memakai seragam yang tidak sesuai hari/ketentuan', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Rok/celana terlalu pendek atau ketat', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Rambut tidak sesuai ketentuan (panjang/dicat)', 'poin' => 15, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Memakai aksesoris berlebihan (kalung, gelang, anting besar)', 'poin' => 8, 'kategori' => 'ringan'],
            ['kelompok' => 'C. SERAGAM & PENAMPILAN', 'nama_pelanggaran' => 'Berkuku panjang/dicat', 'poin' => 5, 'kategori' => 'ringan'],

            // D. SIKAP & PERILAKU
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Berbicara tidak sopan kepada guru/karyawan', 'poin' => 25, 'kategori' => 'sedang'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Berbohong kepada guru/karyawan', 'poin' => 20, 'kategori' => 'sedang'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Melakukan bullying/intimidasi terhadap siswa lain', 'poin' => 50, 'kategori' => 'berat'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Melakukan tindakan asusila/pelecehan', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Berpacaran dengan cara tidak wajar di lingkungan sekolah', 'poin' => 30, 'kategori' => 'sedang'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Merokok di lingkungan sekolah', 'poin' => 50, 'kategori' => 'berat'],
            ['kelompok' => 'D. SIKAP & PERILAKU', 'nama_pelanggaran' => 'Membawa/menyalakan petasan di lingkungan sekolah', 'poin' => 40, 'kategori' => 'berat'],

            // E. PERKELAHIAN & KEKERASAN
            ['kelompok' => 'E. PERKELAHIAN & KEKERASAN', 'nama_pelanggaran' => 'Berkelahi dengan sesama siswa (ringan)', 'poin' => 40, 'kategori' => 'berat'],
            ['kelompok' => 'E. PERKELAHIAN & KEKERASAN', 'nama_pelanggaran' => 'Berkelahi dengan sesama siswa (berat/berdarah)', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'E. PERKELAHIAN & KEKERASAN', 'nama_pelanggaran' => 'Berkelahi dengan siswa sekolah lain', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'E. PERKELAHIAN & KEKERASAN', 'nama_pelanggaran' => 'Mengancam/memukul guru atau karyawan', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'E. PERKELAHIAN & KEKERASAN', 'nama_pelanggaran' => 'Terlibat tawuran antar sekolah', 'poin' => 100, 'kategori' => 'sangat_berat'],

            // F. BARANG TERLARANG
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Membawa senjata tajam tanpa keperluan', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Menggunakan senjata tajam untuk mengancam', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Membawa minuman keras/alkohol', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Mengonsumsi minuman keras di lingkungan sekolah', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Membawa narkoba/obat terlarang', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Menggunakan/mengedarkan narkoba', 'poin' => 100, 'kategori' => 'sangat_berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Membawa konten pornografi (buku/video/gambar)', 'poin' => 50, 'kategori' => 'berat'],
            ['kelompok' => 'F. BARANG TERLARANG', 'nama_pelanggaran' => 'Menyebarkan konten pornografi', 'poin' => 75, 'kategori' => 'berat'],

            // G. TEKNOLOGI & MEDIA
            ['kelompok' => 'G. TEKNOLOGI & MEDIA', 'nama_pelanggaran' => 'Menggunakan HP saat pembelajaran tanpa izin', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'G. TEKNOLOGI & MEDIA', 'nama_pelanggaran' => 'Bermain game saat pembelajaran', 'poin' => 10, 'kategori' => 'ringan'],
            ['kelompok' => 'G. TEKNOLOGI & MEDIA', 'nama_pelanggaran' => 'Mengambil foto/video tanpa izin di lingkungan sekolah', 'poin' => 20, 'kategori' => 'sedang'],
            ['kelompok' => 'G. TEKNOLOGI & MEDIA', 'nama_pelanggaran' => 'Menyebarkan informasi hoax/fitnah tentang sekolah', 'poin' => 50, 'kategori' => 'berat'],

            // H. KEJUJURAN & AKADEMIK
            ['kelompok' => 'H. KEJUJURAN & AKADEMIK', 'nama_pelanggaran' => 'Mencontek saat ulangan/ujian', 'poin' => 20, 'kategori' => 'sedang'],
            ['kelompok' => 'H. KEJUJURAN & AKADEMIK', 'nama_pelanggaran' => 'Membantu teman mencontek', 'poin' => 20, 'kategori' => 'sedang'],
            ['kelompok' => 'H. KEJUJURAN & AKADEMIK', 'nama_pelanggaran' => 'Memalsukan tanda tangan orang tua/guru', 'poin' => 30, 'kategori' => 'sedang'],
            ['kelompok' => 'H. KEJUJURAN & AKADEMIK', 'nama_pelanggaran' => 'Memalsukan surat izin/keterangan', 'poin' => 30, 'kategori' => 'sedang'],
            ['kelompok' => 'H. KEJUJURAN & AKADEMIK', 'nama_pelanggaran' => 'Plagiarisme tugas/karya ilmiah', 'poin' => 25, 'kategori' => 'sedang'],

            // I. FASILITAS & KEBERSIHAN
            ['kelompok' => 'I. FASILITAS & KEBERSIHAN', 'nama_pelanggaran' => 'Tidak menjaga kebersihan kelas/lingkungan', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'I. FASILITAS & KEBERSIHAN', 'nama_pelanggaran' => 'Merusak fasilitas sekolah (ringan)', 'poin' => 20, 'kategori' => 'sedang'],
            ['kelompok' => 'I. FASILITAS & KEBERSIHAN', 'nama_pelanggaran' => 'Merusak fasilitas sekolah (berat)', 'poin' => 50, 'kategori' => 'berat'],
            ['kelompok' => 'I. FASILITAS & KEBERSIHAN', 'nama_pelanggaran' => 'Vandalisme (coret-coret dinding/meja)', 'poin' => 25, 'kategori' => 'sedang'],
            ['kelompok' => 'I. FASILITAS & KEBERSIHAN', 'nama_pelanggaran' => 'Membuang sampah sembarangan', 'poin' => 3, 'kategori' => 'ringan'],

            // J. LAIN-LAIN
            ['kelompok' => 'J. LAIN-LAIN', 'nama_pelanggaran' => 'Membawa kendaraan bermotor tanpa SIM', 'poin' => 25, 'kategori' => 'sedang'],
            ['kelompok' => 'J. LAIN-LAIN', 'nama_pelanggaran' => 'Parkir kendaraan tidak pada tempatnya', 'poin' => 5, 'kategori' => 'ringan'],
            ['kelompok' => 'J. LAIN-LAIN', 'nama_pelanggaran' => 'Mencuri barang milik sekolah/teman', 'poin' => 75, 'kategori' => 'berat'],
            ['kelompok' => 'J. LAIN-LAIN', 'nama_pelanggaran' => 'Membawa/menggunakan atribut organisasi terlarang', 'poin' => 100, 'kategori' => 'sangat_berat'],
        ];

        foreach ($pelanggarans as $pelanggaran) {
            JenisPelanggaran::create($pelanggaran);
        }
    }
}
