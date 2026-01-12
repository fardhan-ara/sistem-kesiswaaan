-- SQL TESTING WALI KELAS SYSTEM

-- 1. Cek struktur tabel users (pastikan kolom is_wali_kelas dan kelas_id ada)
DESCRIBE users;

-- 2. Contoh: Set user dengan ID 2 sebagai Kesiswaan + Wali Kelas untuk kelas ID 1
UPDATE users 
SET is_wali_kelas = 1, kelas_id = 1 
WHERE id = 2;

-- 3. Contoh: Set user dengan ID 3 sebagai BK + Wali Kelas untuk kelas ID 2
UPDATE users 
SET role = 'bk', is_wali_kelas = 1, kelas_id = 2 
WHERE id = 3;

-- 4. Cek user yang sudah jadi wali kelas
SELECT 
    u.id,
    u.nama,
    u.email,
    u.role,
    u.is_wali_kelas,
    u.kelas_id,
    k.nama_kelas,
    g.nama_guru
FROM users u
LEFT JOIN kelas k ON u.kelas_id = k.id
LEFT JOIN gurus g ON g.users_id = u.id
WHERE u.is_wali_kelas = 1;

-- 5. Cek siswa di kelas tertentu (misal kelas ID 1)
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    s.jenis_kelamin,
    k.nama_kelas,
    COUNT(DISTINCT p.id) as total_pelanggaran,
    SUM(CASE WHEN p.status_verifikasi = 'diverifikasi' THEN p.poin ELSE 0 END) as total_poin_pelanggaran,
    COUNT(DISTINCT pr.id) as total_prestasi,
    SUM(CASE WHEN pr.status_verifikasi = 'verified' THEN pr.poin ELSE 0 END) as total_poin_prestasi
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN pelanggarans p ON s.id = p.siswa_id
LEFT JOIN prestasis pr ON s.id = pr.siswa_id
WHERE s.kelas_id = 1
GROUP BY s.id, s.nis, s.nama_siswa, s.jenis_kelamin, k.nama_kelas;

-- 6. Reset wali kelas (jika perlu)
UPDATE users 
SET is_wali_kelas = 0, kelas_id = NULL 
WHERE id = 2;

-- 7. Cek data guru untuk user tertentu
SELECT 
    u.id as user_id,
    u.nama as nama_user,
    u.role,
    g.id as guru_id,
    g.nip,
    g.nama_guru
FROM users u
LEFT JOIN gurus g ON g.users_id = u.id
WHERE u.id = 2;

-- 8. Pastikan ada data guru untuk user yang akan jadi wali kelas
-- Jika belum ada, insert dulu:
-- INSERT INTO gurus (users_id, nip, nama_guru, jenis_kelamin, bidang_studi, status, status_approval, created_at, updated_at)
-- VALUES (2, '198001012000011001', 'Nama Guru', 'L', 'Matematika', 'aktif', 'approved', NOW(), NOW());
