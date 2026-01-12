-- ============================================
-- SQL Helper untuk Pendaftaran Orang Tua
-- ============================================

-- 1. Lihat semua siswa yang terdaftar (untuk referensi pendaftaran ortu)
SELECT 
    id,
    nis,
    nama_siswa,
    jenis_kelamin,
    kelas_id
FROM siswas
ORDER BY nama_siswa;

-- 2. Lihat pendaftaran orang tua yang pending
SELECT 
    u.id,
    u.nama AS nama_ortu,
    u.email,
    u.nama_anak,
    u.nis_anak,
    u.status,
    u.created_at
FROM users u
WHERE u.role = 'ortu' 
  AND u.status = 'pending'
ORDER BY u.created_at DESC;

-- 3. Validasi kecocokan data orang tua dengan siswa
SELECT 
    u.id AS user_id,
    u.nama AS nama_ortu,
    u.email,
    u.nama_anak,
    u.nis_anak,
    s.id AS siswa_id,
    s.nama_siswa,
    s.nis,
    CASE 
        WHEN s.id IS NULL THEN '❌ Data siswa tidak ditemukan'
        WHEN u.nama_anak = s.nama_siswa AND u.nis_anak = s.nis THEN '✓ Data cocok'
        ELSE '⚠ Data tidak cocok'
    END AS status_validasi
FROM users u
LEFT JOIN siswas s ON u.nis_anak = s.nis AND u.nama_anak = s.nama_siswa
WHERE u.role = 'ortu'
ORDER BY u.created_at DESC;

-- 4. Lihat orang tua yang sudah approved
SELECT 
    u.id,
    u.nama AS nama_ortu,
    u.email,
    u.nama_anak,
    u.nis_anak,
    u.status,
    u.verified_at,
    v.nama AS verified_by_name
FROM users u
LEFT JOIN users v ON u.verified_by = v.id
WHERE u.role = 'ortu' 
  AND u.status = 'approved'
ORDER BY u.verified_at DESC;

-- 5. Cari siswa berdasarkan nama (untuk membantu ortu yang lupa NIS)
-- Ganti 'NAMA_SISWA' dengan nama yang dicari
SELECT 
    nis,
    nama_siswa,
    jenis_kelamin,
    k.nama_kelas
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
WHERE nama_siswa LIKE '%NAMA_SISWA%'
ORDER BY nama_siswa;

-- 6. Cari siswa berdasarkan NIS
-- Ganti 'NIS_SISWA' dengan NIS yang dicari
SELECT 
    nis,
    nama_siswa,
    jenis_kelamin,
    k.nama_kelas
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
WHERE nis = 'NIS_SISWA';

-- 7. Update data anak jika salah input (HATI-HATI!)
-- Ganti USER_ID, NAMA_ANAK_BARU, dan NIS_ANAK_BARU
-- UPDATE users 
-- SET nama_anak = 'NAMA_ANAK_BARU', 
--     nis_anak = 'NIS_ANAK_BARU'
-- WHERE id = USER_ID AND role = 'ortu';

-- 8. Hapus pendaftaran ortu yang data anaknya tidak valid
-- DELETE FROM users 
-- WHERE role = 'ortu' 
--   AND status = 'pending'
--   AND NOT EXISTS (
--       SELECT 1 FROM siswas 
--       WHERE siswas.nis = users.nis_anak 
--         AND siswas.nama_siswa = users.nama_anak
--   );

-- 9. Statistik pendaftaran orang tua
SELECT 
    status,
    COUNT(*) as jumlah,
    COUNT(CASE WHEN nama_anak IS NOT NULL AND nis_anak IS NOT NULL THEN 1 END) as dengan_data_anak,
    COUNT(CASE WHEN nama_anak IS NULL OR nis_anak IS NULL THEN 1 END) as tanpa_data_anak
FROM users
WHERE role = 'ortu'
GROUP BY status;

-- 10. Cek duplikasi pendaftaran (ortu yang mendaftar lebih dari 1x untuk anak yang sama)
SELECT 
    nama_anak,
    nis_anak,
    COUNT(*) as jumlah_pendaftaran,
    GROUP_CONCAT(email SEPARATOR ', ') as email_list
FROM users
WHERE role = 'ortu'
  AND nama_anak IS NOT NULL
  AND nis_anak IS NOT NULL
GROUP BY nama_anak, nis_anak
HAVING COUNT(*) > 1;

-- 11. Approve user ortu secara manual (jika diperlukan)
-- Ganti USER_ID dan ADMIN_ID
-- UPDATE users 
-- SET status = 'approved',
--     verified_by = ADMIN_ID,
--     verified_at = NOW()
-- WHERE id = USER_ID AND role = 'ortu';

-- 12. Reject user ortu secara manual (jika diperlukan)
-- Ganti USER_ID dan ALASAN_PENOLAKAN
-- UPDATE users 
-- SET status = 'rejected',
--     rejection_reason = 'ALASAN_PENOLAKAN'
-- WHERE id = USER_ID AND role = 'ortu';
