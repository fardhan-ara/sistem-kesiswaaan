-- Hapus Data Testing Lama
-- Jalankan script ini untuk membersihkan data testing

-- 1. Hapus user testing (guru, siswa, ortu)
DELETE FROM users WHERE email IN ('aep@gmail.com', 'tatang@gmail.com', 'eds@gmail.com');

-- 2. Hapus guru dengan NIP temporary
DELETE FROM gurus WHERE nip LIKE 'TEMP-%';

-- 3. Hapus siswa yang tidak memiliki user_id valid
DELETE FROM siswas WHERE users_id NOT IN (SELECT id FROM users) AND users_id IS NOT NULL;

-- 4. Verifikasi data yang tersisa
SELECT 'Users yang tersisa:' as info;
SELECT id, nama, email, role, status FROM users ORDER BY role, created_at;

SELECT 'Guru yang tersisa:' as info;
SELECT id, nip, nama_guru FROM gurus;

SELECT 'Siswa yang tersisa:' as info;
SELECT id, nis, nama_siswa FROM siswas LIMIT 10;
