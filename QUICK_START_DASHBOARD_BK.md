# 🚀 Quick Start - Dashboard BK

## Cara Testing Dashboard BK

### 1. Pastikan User BK Ada
```sql
-- Cek user dengan role BK
SELECT * FROM users WHERE role = 'bk';

-- Jika belum ada, buat user BK
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
VALUES ('Guru BK', 'bk@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bk', NOW(), NOW(), NOW());
-- Password: password
```

### 2. Pastikan Data Guru BK Terkoneksi
```sql
-- Cek koneksi guru dengan user BK
SELECT g.*, u.email, u.role 
FROM gurus g 
JOIN users u ON g.users_id = u.id 
WHERE u.role = 'bk';

-- Jika belum ada, buat data guru BK
INSERT INTO gurus (users_id, nip, nama_guru, bidang_studi, status, created_at, updated_at)
SELECT id, CONCAT('NIP-BK-', id), name, 'Bimbingan Konseling (BK)', 'aktif', NOW(), NOW()
FROM users WHERE role = 'bk' AND id NOT IN (SELECT users_id FROM gurus);
```

### 3. Login dan Test
1. Buka: `http://localhost:8000/login`
2. Email: `bk@test.com`
3. Password: `password`
4. Setelah login, akan redirect ke dashboard BK

### 4. Verifikasi Fitur

#### ✅ Checklist Testing:
- [ ] Card "Total Sesi BK" menampilkan angka benar
- [ ] Card "BK Bulan Ini" menampilkan angka benar
- [ ] Card "Siswa Bermasalah" menampilkan angka benar
- [ ] Card "Total Siswa" menampilkan angka benar
- [ ] Grafik muncul dan menampilkan data 3 bulan
- [ ] Tabel "Sesi BK Terbaru" menampilkan 5 data terakhir
- [ ] Badge kategori (Pribadi/Sosial/Belajar/Karir) muncul
- [ ] Badge status (Selesai/Proses/Terjadwal) muncul
- [ ] Link "Lihat Detail" di card berfungsi
- [ ] Link "Tambah Sesi BK" di card berfungsi
- [ ] Link "Lihat Semua" di tabel berfungsi
- [ ] Quick Actions 4 tombol berfungsi semua
- [ ] Responsive di mobile (resize browser)

### 5. Tambah Data Dummy (Jika Kosong)

```sql
-- Tambah data BK dummy
INSERT INTO bimbingan_konselings (siswa_id, guru_id, kategori, tanggal, catatan, status, created_at, updated_at)
SELECT 
    s.id,
    (SELECT id FROM gurus WHERE bidang_studi LIKE '%BK%' LIMIT 1),
    CASE FLOOR(RAND() * 4)
        WHEN 0 THEN 'pribadi'
        WHEN 1 THEN 'sosial'
        WHEN 2 THEN 'belajar'
        ELSE 'karir'
    END,
    DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 90) DAY),
    'Catatan bimbingan konseling',
    CASE FLOOR(RAND() * 3)
        WHEN 0 THEN 'selesai'
        WHEN 1 THEN 'proses'
        ELSE 'terjadwal'
    END,
    NOW(),
    NOW()
FROM siswas s
LIMIT 10;
```

## 🐛 Troubleshooting

### Error: "Undefined variable: totalBK"
**Solusi**: Pastikan DashboardController sudah diupdate dengan method bkDashboard()

### Error: "Call to undefined method"
**Solusi**: Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Dashboard Tidak Muncul
**Solusi**: 
1. Cek role user: `SELECT role FROM users WHERE email = 'bk@test.com';`
2. Pastikan role = 'bk' (bukan 'guru')
3. Logout dan login ulang

### Grafik Tidak Muncul
**Solusi**:
1. Cek console browser (F12) untuk error JavaScript
2. Pastikan Chart.js CDN terload
3. Cek data statistikBulanan tidak null

### Link Broken (404)
**Solusi**: Pastikan route sudah terdaftar
```bash
php artisan route:list | grep bk
```

## 📊 Query Verifikasi Data

```sql
-- Total Sesi BK
SELECT COUNT(*) as total_bk FROM bimbingan_konselings;

-- BK Bulan Ini
SELECT COUNT(*) as bk_bulan_ini 
FROM bimbingan_konselings 
WHERE MONTH(tanggal) = MONTH(NOW()) 
AND YEAR(tanggal) = YEAR(NOW());

-- Siswa Bermasalah (poin >= 50)
SELECT COUNT(DISTINCT siswa_id) as siswa_bermasalah
FROM (
    SELECT siswa_id, SUM(poin) as total_poin
    FROM pelanggarans
    WHERE status_verifikasi IN ('diverifikasi', 'terverifikasi')
    GROUP BY siswa_id
    HAVING total_poin >= 50
) as subquery;

-- Total Siswa
SELECT COUNT(*) as total_siswa FROM siswas;

-- 5 BK Terbaru
SELECT 
    bk.tanggal,
    s.nama_siswa,
    k.nama_kelas,
    bk.kategori,
    bk.status
FROM bimbingan_konselings bk
JOIN siswas s ON bk.siswa_id = s.id
LEFT JOIN kelas k ON s.kelas_id = k.id
ORDER BY bk.tanggal DESC
LIMIT 5;

-- Statistik 3 Bulan Terakhir
SELECT 
    DATE_FORMAT(tanggal, '%b %Y') as bulan,
    COUNT(*) as jumlah
FROM bimbingan_konselings
WHERE tanggal >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
ORDER BY tanggal;
```

## 🎯 Expected Results

### Dashboard Harus Menampilkan:
1. **4 Cards Statistik** dengan angka dan icon
2. **1 Grafik Line Chart** dengan data 3 bulan
3. **1 Tabel** dengan 5 data BK terbaru
4. **4 Tombol Quick Actions** dengan icon dan warna berbeda

### Warna yang Benar:
- Card 1 (Total BK): Biru Info
- Card 2 (BK Bulan Ini): Hijau Success
- Card 3 (Siswa Bermasalah): Kuning Warning
- Card 4 (Total Siswa): Biru Primary

---

**Tips**: Jika semua checklist ✅, dashboard BK sudah siap digunakan!
