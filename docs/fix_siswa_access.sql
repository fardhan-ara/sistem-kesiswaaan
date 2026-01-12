-- Script SQL untuk memperbaiki akses Data Master Siswa
-- Sistem Kesiswaan

-- ============================================
-- 1. CEK DATA USER DAN ROLE
-- ============================================
SELECT 
    id,
    nama,
    email,
    role,
    email_verified_at,
    created_at
FROM users
ORDER BY role, nama;

-- ============================================
-- 2. CEK JUMLAH DATA PER ROLE
-- ============================================
SELECT 
    role,
    COUNT(*) as jumlah
FROM users
GROUP BY role
ORDER BY jumlah DESC;

-- ============================================
-- 3. UPDATE ROLE USER MENJADI ADMIN
-- ============================================
-- Ganti 'email@user.com' dengan email user yang ingin diubah
-- UPDATE users 
-- SET role = 'admin' 
-- WHERE email = 'email@user.com';

-- ============================================
-- 4. UPDATE ROLE USER MENJADI KESISWAAN
-- ============================================
-- Ganti 'email@user.com' dengan email user yang ingin diubah
-- UPDATE users 
-- SET role = 'kesiswaan' 
-- WHERE email = 'email@user.com';

-- ============================================
-- 5. CEK DATA SISWA
-- ============================================
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    s.jenis_kelamin,
    k.nama_kelas,
    ta.tahun_ajaran,
    u.nama as nama_user,
    u.email
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN tahun_ajarans ta ON s.tahun_ajaran_id = ta.id
LEFT JOIN users u ON s.users_id = u.id
ORDER BY s.nama_siswa;

-- ============================================
-- 6. CEK SISWA TANPA USER
-- ============================================
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    s.users_id
FROM siswas s
WHERE s.users_id IS NULL OR s.users_id NOT IN (SELECT id FROM users);

-- ============================================
-- 7. CEK KELAS TERSEDIA
-- ============================================
SELECT 
    k.id,
    k.nama_kelas,
    k.tingkat,
    ta.tahun_ajaran,
    COUNT(s.id) as jumlah_siswa
FROM kelas k
LEFT JOIN tahun_ajarans ta ON k.tahun_ajaran_id = ta.id
LEFT JOIN siswas s ON k.id = s.kelas_id
GROUP BY k.id, k.nama_kelas, k.tingkat, ta.tahun_ajaran
ORDER BY k.tingkat, k.nama_kelas;

-- ============================================
-- 8. CEK TAHUN AJARAN AKTIF
-- ============================================
SELECT 
    id,
    tahun_ajaran,
    semester,
    status_aktif,
    tanggal_mulai,
    tanggal_selesai
FROM tahun_ajarans
ORDER BY status_aktif DESC, tahun_ajaran DESC;

-- ============================================
-- 9. BUAT USER ADMIN BARU (JIKA DIPERLUKAN)
-- ============================================
-- Password default: password (sudah di-hash)
-- INSERT INTO users (nama, email, password, role, email_verified_at, created_at, updated_at)
-- VALUES (
--     'Admin Baru',
--     'admin.baru@test.com',
--     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
--     'admin',
--     NOW(),
--     NOW(),
--     NOW()
-- );

-- ============================================
-- 10. BUAT USER KESISWAAN BARU (JIKA DIPERLUKAN)
-- ============================================
-- Password default: password (sudah di-hash)
-- INSERT INTO users (nama, email, password, role, email_verified_at, created_at, updated_at)
-- VALUES (
--     'Staff Kesiswaan Baru',
--     'kesiswaan.baru@test.com',
--     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
--     'kesiswaan',
--     NOW(),
--     NOW(),
--     NOW()
-- );

-- ============================================
-- 11. VERIFIKASI EMAIL USER (JIKA BELUM)
-- ============================================
-- UPDATE users 
-- SET email_verified_at = NOW() 
-- WHERE email_verified_at IS NULL;

-- ============================================
-- 12. CEK STATISTIK SISWA
-- ============================================
SELECT 
    COUNT(*) as total_siswa,
    SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as laki_laki,
    SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as perempuan
FROM siswas;

-- ============================================
-- 13. CEK SISWA DENGAN PELANGGARAN
-- ============================================
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    COUNT(p.id) as jumlah_pelanggaran,
    SUM(CASE WHEN p.status_verifikasi = 'diverifikasi' THEN p.poin ELSE 0 END) as total_poin
FROM siswas s
LEFT JOIN pelanggarans p ON s.id = p.siswa_id
GROUP BY s.id, s.nis, s.nama_siswa
HAVING jumlah_pelanggaran > 0
ORDER BY total_poin DESC;

-- ============================================
-- 14. RESET AUTO INCREMENT (JIKA DIPERLUKAN)
-- ============================================
-- ALTER TABLE siswas AUTO_INCREMENT = 1;

-- ============================================
-- CATATAN PENTING
-- ============================================
-- 1. Backup database sebelum menjalankan UPDATE/DELETE
-- 2. Uncomment (hapus --) pada query yang ingin dijalankan
-- 3. Ganti nilai placeholder dengan data yang sesuai
-- 4. Password default 'password' sudah di-hash dengan bcrypt
-- 5. Untuk generate password baru, gunakan: php artisan tinker
--    kemudian: bcrypt('password_baru')
