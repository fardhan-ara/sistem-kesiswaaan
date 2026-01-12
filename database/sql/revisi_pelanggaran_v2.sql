-- =====================================================
-- REVISI DATA PELANGGARAN - SISTEM KESISWAAN
-- Tanggal: 6 Januari 2026
-- Versi: 2.0
-- =====================================================

-- STEP 1: BACKUP DATA LAMA (OPTIONAL)
-- Uncomment jika ingin backup
-- CREATE TABLE jenis_pelanggarans_backup AS SELECT * FROM jenis_pelanggarans;

-- STEP 2: HAPUS DATA LAMA
TRUNCATE TABLE jenis_pelanggarans;

-- STEP 3: INSERT DATA BARU

-- A. KEHADIRAN & KETERLAMBATAN (7 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('A. KEHADIRAN & KETERLAMBATAN', 'Terlambat masuk sekolah (1-15 menit)', 2, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Terlambat masuk sekolah (16-30 menit)', 5, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Terlambat masuk sekolah (lebih dari 30 menit)', 10, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Tidak masuk sekolah tanpa keterangan (alfa)', 10, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Sakit tanpa surat keterangan dokter/orang tua', 3, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Meninggalkan sekolah tanpa izin (membolos)', 15, 'ringan', NOW(), NOW()),
('A. KEHADIRAN & KETERLAMBATAN', 'Pulang sebelum waktunya tanpa izin', 10, 'ringan', NOW(), NOW());

-- B. KETERTIBAN & KEDISIPLINAN (6 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('B. KETERTIBAN & KEDISIPLINAN', 'Membuat keributan di dalam kelas saat pembelajaran', 10, 'ringan', NOW(), NOW()),
('B. KETERTIBAN & KEDISIPLINAN', 'Keluar masuk kelas tanpa izin saat pembelajaran', 8, 'ringan', NOW(), NOW()),
('B. KETERTIBAN & KEDISIPLINAN', 'Tidak mengikuti upacara bendera tanpa alasan jelas', 10, 'ringan', NOW(), NOW()),
('B. KETERTIBAN & KEDISIPLINAN', 'Tidak mengerjakan tugas/PR berulang kali', 5, 'ringan', NOW(), NOW()),
('B. KETERTIBAN & KEDISIPLINAN', 'Tidur saat pembelajaran berlangsung', 5, 'ringan', NOW(), NOW()),
('B. KETERTIBAN & KEDISIPLINAN', 'Bermain kartu/judi di lingkungan sekolah', 50, 'berat', NOW(), NOW());

-- C. SERAGAM & PENAMPILAN (8 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('C. SERAGAM & PENAMPILAN', 'Seragam tidak dimasukkan/tidak rapi', 5, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Tidak memakai ikat pinggang', 3, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Tidak memakai kaos kaki sesuai ketentuan', 3, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Memakai seragam yang tidak sesuai hari/ketentuan', 10, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Rok/celana terlalu pendek atau ketat', 10, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Rambut tidak sesuai ketentuan (panjang/dicat)', 15, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Memakai aksesoris berlebihan (kalung, gelang, anting besar)', 8, 'ringan', NOW(), NOW()),
('C. SERAGAM & PENAMPILAN', 'Berkuku panjang/dicat', 5, 'ringan', NOW(), NOW());

-- D. SIKAP & PERILAKU (7 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('D. SIKAP & PERILAKU', 'Berbicara tidak sopan kepada guru/karyawan', 25, 'sedang', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Berbohong kepada guru/karyawan', 20, 'sedang', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Melakukan bullying/intimidasi terhadap siswa lain', 50, 'berat', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Melakukan tindakan asusila/pelecehan', 100, 'sangat_berat', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Berpacaran dengan cara tidak wajar di lingkungan sekolah', 30, 'sedang', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Merokok di lingkungan sekolah', 50, 'berat', NOW(), NOW()),
('D. SIKAP & PERILAKU', 'Membawa/menyalakan petasan di lingkungan sekolah', 40, 'berat', NOW(), NOW());

-- E. PERKELAHIAN & KEKERASAN (5 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('E. PERKELAHIAN & KEKERASAN', 'Berkelahi dengan sesama siswa (ringan)', 40, 'berat', NOW(), NOW()),
('E. PERKELAHIAN & KEKERASAN', 'Berkelahi dengan sesama siswa (berat/berdarah)', 75, 'berat', NOW(), NOW()),
('E. PERKELAHIAN & KEKERASAN', 'Berkelahi dengan siswa sekolah lain', 75, 'berat', NOW(), NOW()),
('E. PERKELAHIAN & KEKERASAN', 'Mengancam/memukul guru atau karyawan', 100, 'sangat_berat', NOW(), NOW()),
('E. PERKELAHIAN & KEKERASAN', 'Terlibat tawuran antar sekolah', 100, 'sangat_berat', NOW(), NOW());

-- F. BARANG TERLARANG (8 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('F. BARANG TERLARANG', 'Membawa senjata tajam tanpa keperluan', 75, 'berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Menggunakan senjata tajam untuk mengancam', 100, 'sangat_berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Membawa minuman keras/alkohol', 75, 'berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Mengonsumsi minuman keras di lingkungan sekolah', 100, 'sangat_berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Membawa narkoba/obat terlarang', 100, 'sangat_berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Menggunakan/mengedarkan narkoba', 100, 'sangat_berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Membawa konten pornografi (buku/video/gambar)', 50, 'berat', NOW(), NOW()),
('F. BARANG TERLARANG', 'Menyebarkan konten pornografi', 75, 'berat', NOW(), NOW());

-- G. TEKNOLOGI & MEDIA (4 items) - BARU!
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('G. TEKNOLOGI & MEDIA', 'Menggunakan HP saat pembelajaran tanpa izin', 10, 'ringan', NOW(), NOW()),
('G. TEKNOLOGI & MEDIA', 'Bermain game saat pembelajaran', 10, 'ringan', NOW(), NOW()),
('G. TEKNOLOGI & MEDIA', 'Mengambil foto/video tanpa izin di lingkungan sekolah', 20, 'sedang', NOW(), NOW()),
('G. TEKNOLOGI & MEDIA', 'Menyebarkan informasi hoax/fitnah tentang sekolah', 50, 'berat', NOW(), NOW());

-- H. KEJUJURAN & AKADEMIK (5 items) - BARU!
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('H. KEJUJURAN & AKADEMIK', 'Mencontek saat ulangan/ujian', 20, 'sedang', NOW(), NOW()),
('H. KEJUJURAN & AKADEMIK', 'Membantu teman mencontek', 20, 'sedang', NOW(), NOW()),
('H. KEJUJURAN & AKADEMIK', 'Memalsukan tanda tangan orang tua/guru', 30, 'sedang', NOW(), NOW()),
('H. KEJUJURAN & AKADEMIK', 'Memalsukan surat izin/keterangan', 30, 'sedang', NOW(), NOW()),
('H. KEJUJURAN & AKADEMIK', 'Plagiarisme tugas/karya ilmiah', 25, 'sedang', NOW(), NOW());

-- I. FASILITAS & KEBERSIHAN (5 items) - BARU!
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('I. FASILITAS & KEBERSIHAN', 'Tidak menjaga kebersihan kelas/lingkungan', 5, 'ringan', NOW(), NOW()),
('I. FASILITAS & KEBERSIHAN', 'Merusak fasilitas sekolah (ringan)', 20, 'sedang', NOW(), NOW()),
('I. FASILITAS & KEBERSIHAN', 'Merusak fasilitas sekolah (berat)', 50, 'berat', NOW(), NOW()),
('I. FASILITAS & KEBERSIHAN', 'Vandalisme (coret-coret dinding/meja)', 25, 'sedang', NOW(), NOW()),
('I. FASILITAS & KEBERSIHAN', 'Membuang sampah sembarangan', 3, 'ringan', NOW(), NOW());

-- J. LAIN-LAIN (4 items)
INSERT INTO jenis_pelanggarans (kelompok, nama_pelanggaran, poin, kategori, created_at, updated_at) VALUES
('J. LAIN-LAIN', 'Membawa kendaraan bermotor tanpa SIM', 25, 'sedang', NOW(), NOW()),
('J. LAIN-LAIN', 'Parkir kendaraan tidak pada tempatnya', 5, 'ringan', NOW(), NOW()),
('J. LAIN-LAIN', 'Mencuri barang milik sekolah/teman', 75, 'berat', NOW(), NOW()),
('J. LAIN-LAIN', 'Membawa/menggunakan atribut organisasi terlarang', 100, 'sangat_berat', NOW(), NOW());

-- STEP 4: VERIFIKASI
SELECT 
    kelompok,
    COUNT(*) as jumlah,
    MIN(poin) as poin_min,
    MAX(poin) as poin_max
FROM jenis_pelanggarans
GROUP BY kelompok
ORDER BY kelompok;

-- STEP 5: TOTAL COUNT
SELECT COUNT(*) as total_pelanggaran FROM jenis_pelanggarans;

-- =====================================================
-- SELESAI
-- Total: 59 jenis pelanggaran
-- =====================================================
