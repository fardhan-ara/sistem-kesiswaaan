-- ============================================
-- SQL HELPER: INTEGRASI ORANG TUA - SISWA
-- ============================================

-- 1. CEK STATUS BIODATA ORANG TUA
-- ============================================
SELECT 
    u.id AS user_id,
    u.nama AS nama_ortu,
    u.email,
    u.role,
    u.status AS user_status,
    bo.id AS biodata_id,
    bo.status_approval,
    bo.siswa_id,
    s.nis,
    s.nama_siswa
FROM users u
LEFT JOIN biodata_ortus bo ON u.id = bo.user_id
LEFT JOIN siswas s ON bo.siswa_id = s.id
WHERE u.role = 'ortu'
ORDER BY u.created_at DESC;

-- 2. CEK DATA LENGKAP ORANG TUA & ANAK
-- ============================================
SELECT 
    u.nama AS nama_ortu,
    u.email AS email_ortu,
    bo.nama_ayah,
    bo.nama_ibu,
    bo.no_telp,
    bo.status_approval,
    s.nis,
    s.nama_siswa,
    k.nama_kelas,
    ta.tahun_ajaran,
    ta.semester
FROM users u
INNER JOIN biodata_ortus bo ON u.id = bo.user_id
INNER JOIN siswas s ON bo.siswa_id = s.id
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN tahun_ajarans ta ON s.tahun_ajaran_id = ta.id
WHERE u.role = 'ortu' AND bo.status_approval = 'approved';

-- 3. CEK STATISTIK ANAK UNTUK ORANG TUA
-- ============================================
SELECT 
    s.nis,
    s.nama_siswa,
    COUNT(DISTINCT p.id) AS total_pelanggaran,
    COALESCE(SUM(p.poin), 0) AS total_poin,
    COUNT(DISTINCT pr.id) AS total_prestasi,
    COUNT(DISTINCT sa.id) AS total_sanksi,
    COUNT(DISTINCT CASE WHEN sa.status_sanksi = 'aktif' THEN sa.id END) AS sanksi_aktif
FROM siswas s
LEFT JOIN pelanggarans p ON s.id = p.siswa_id AND p.status_verifikasi IN ('diverifikasi', 'terverifikasi')
LEFT JOIN prestasis pr ON s.id = pr.siswa_id AND pr.status_verifikasi = 'verified'
LEFT JOIN sanksis sa ON s.id = sa.siswa_id
WHERE s.id IN (SELECT siswa_id FROM biodata_ortus WHERE status_approval = 'approved')
GROUP BY s.id, s.nis, s.nama_siswa;

-- 4. APPROVE BIODATA ORANG TUA (MANUAL)
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
UPDATE biodata_ortus 
SET status_approval = 'approved',
    approved_by = 1,  -- ID admin
    approved_at = NOW()
WHERE user_id = [USER_ID];

-- 5. REJECT BIODATA ORANG TUA (MANUAL)
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
UPDATE biodata_ortus 
SET status_approval = 'rejected',
    rejection_reason = 'Dokumen tidak lengkap atau tidak valid'
WHERE user_id = [USER_ID];

-- 6. HUBUNGKAN ORANG TUA KE SISWA
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
-- Ganti [SISWA_ID] dengan ID siswa
UPDATE biodata_ortus 
SET siswa_id = [SISWA_ID]
WHERE user_id = [USER_ID];

-- 7. CEK PELANGGARAN ANAK UNTUK ORANG TUA TERTENTU
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
SELECT 
    p.id,
    p.created_at AS tanggal,
    jp.nama_pelanggaran,
    p.poin,
    p.keterangan,
    g.nama_guru AS pencatat,
    p.status_verifikasi
FROM pelanggarans p
INNER JOIN biodata_ortus bo ON p.siswa_id = bo.siswa_id
INNER JOIN jenis_pelanggarans jp ON p.jenis_pelanggaran_id = jp.id
LEFT JOIN gurus g ON p.guru_pencatat = g.id
WHERE bo.user_id = [USER_ID] 
  AND bo.status_approval = 'approved'
  AND p.status_verifikasi IN ('diverifikasi', 'terverifikasi')
ORDER BY p.created_at DESC
LIMIT 10;

-- 8. CEK PRESTASI ANAK UNTUK ORANG TUA TERTENTU
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
SELECT 
    pr.id,
    pr.created_at AS tanggal,
    jp.nama_prestasi,
    pr.tingkat,
    pr.poin,
    pr.keterangan,
    g.nama_guru AS pencatat
FROM prestasis pr
INNER JOIN biodata_ortus bo ON pr.siswa_id = bo.siswa_id
INNER JOIN jenis_prestasis jp ON pr.jenis_prestasi_id = jp.id
LEFT JOIN gurus g ON pr.guru_pencatat = g.id
WHERE bo.user_id = [USER_ID] 
  AND bo.status_approval = 'approved'
  AND pr.status_verifikasi = 'verified'
ORDER BY pr.created_at DESC
LIMIT 10;

-- 9. CEK SANKSI ANAK UNTUK ORANG TUA TERTENTU
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
SELECT 
    s.id,
    s.jenis_sanksi,
    s.tanggal_mulai,
    s.tanggal_selesai,
    s.status_sanksi,
    jp.nama_pelanggaran
FROM sanksis s
INNER JOIN biodata_ortus bo ON s.siswa_id = bo.siswa_id
INNER JOIN pelanggarans p ON s.pelanggaran_id = p.id
INNER JOIN jenis_pelanggarans jp ON p.jenis_pelanggaran_id = jp.id
WHERE bo.user_id = [USER_ID] 
  AND bo.status_approval = 'approved'
ORDER BY s.created_at DESC;

-- 10. CEK BIMBINGAN KONSELING ANAK
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
SELECT 
    bk.id,
    bk.tanggal_bimbingan,
    bk.permasalahan,
    bk.solusi,
    bk.tindak_lanjut,
    g.nama_guru AS konselor
FROM bimbingan_konselings bk
INNER JOIN biodata_ortus bo ON bk.siswa_id = bo.siswa_id
LEFT JOIN gurus g ON bk.guru_id = g.id
WHERE bo.user_id = [USER_ID] 
  AND bo.status_approval = 'approved'
ORDER BY bk.tanggal_bimbingan DESC;

-- 11. BUAT USER ORANG TUA BARU (TESTING)
-- ============================================
INSERT INTO users (nama, email, password, role, status, created_at, updated_at)
VALUES ('Orang Tua Test', 'ortu.test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ortu', 'approved', NOW(), NOW());

-- Ambil ID user yang baru dibuat
SET @user_id = LAST_INSERT_ID();

-- Buat biodata untuk user tersebut (ganti [SISWA_ID] dengan ID siswa yang valid)
INSERT INTO biodata_ortus (user_id, siswa_id, nama_ayah, nama_ibu, no_telp, alamat, foto_kk, foto_ktp, status_approval, created_at, updated_at)
VALUES (@user_id, [SISWA_ID], 'Nama Ayah Test', 'Nama Ibu Test', '081234567890', 'Alamat Test', 'kk.jpg', 'ktp.jpg', 'approved', NOW(), NOW());

-- 12. RESET STATUS BIODATA KE PENDING
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
UPDATE biodata_ortus 
SET status_approval = 'pending',
    approved_by = NULL,
    approved_at = NULL,
    rejection_reason = NULL
WHERE user_id = [USER_ID];

-- 13. CEK ORANG TUA TANPA BIODATA
-- ============================================
SELECT 
    u.id,
    u.nama,
    u.email,
    u.status,
    u.created_at
FROM users u
LEFT JOIN biodata_ortus bo ON u.id = bo.user_id
WHERE u.role = 'ortu' AND bo.id IS NULL;

-- 14. CEK ORANG TUA DENGAN BIODATA PENDING
-- ============================================
SELECT 
    u.id,
    u.nama,
    u.email,
    bo.status_approval,
    bo.created_at AS biodata_created
FROM users u
INNER JOIN biodata_ortus bo ON u.id = bo.user_id
WHERE u.role = 'ortu' AND bo.status_approval = 'pending';

-- 15. HAPUS BIODATA ORANG TUA (TESTING)
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
DELETE FROM biodata_ortus WHERE user_id = [USER_ID];

-- 16. HAPUS USER ORANG TUA (TESTING)
-- ============================================
-- Ganti [USER_ID] dengan ID user orang tua
-- HATI-HATI: Hapus biodata dulu sebelum hapus user
DELETE FROM biodata_ortus WHERE user_id = [USER_ID];
DELETE FROM users WHERE id = [USER_ID];

-- ============================================
-- QUERY UNTUK DEBUGGING
-- ============================================

-- Cek total orang tua di sistem
SELECT COUNT(*) AS total_ortu FROM users WHERE role = 'ortu';

-- Cek total biodata approved
SELECT COUNT(*) AS total_approved FROM biodata_ortus WHERE status_approval = 'approved';

-- Cek total biodata pending
SELECT COUNT(*) AS total_pending FROM biodata_ortus WHERE status_approval = 'pending';

-- Cek total biodata rejected
SELECT COUNT(*) AS total_rejected FROM biodata_ortus WHERE status_approval = 'rejected';

-- Cek siswa yang sudah punya orang tua
SELECT 
    s.nis,
    s.nama_siswa,
    u.nama AS nama_ortu,
    u.email AS email_ortu,
    bo.status_approval
FROM siswas s
INNER JOIN biodata_ortus bo ON s.id = bo.siswa_id
INNER JOIN users u ON bo.user_id = u.id
WHERE bo.status_approval = 'approved';

-- Cek siswa yang belum punya orang tua
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    k.nama_kelas
FROM siswas s
LEFT JOIN biodata_ortus bo ON s.id = bo.siswa_id
LEFT JOIN kelas k ON s.kelas_id = k.id
WHERE bo.id IS NULL;
