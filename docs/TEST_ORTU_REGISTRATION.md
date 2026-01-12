# Test Manual - Pendaftaran Orang Tua

## Persiapan Test

### 1. Pastikan ada data siswa di database
```sql
-- Cek data siswa yang tersedia
SELECT nis, nama_siswa FROM siswas LIMIT 5;
```

Contoh output:
```
+-------+------------------+
| nis   | nama_siswa       |
+-------+------------------+
| 12345 | Ahmad Santoso    |
| 12346 | Budi Pratama     |
| 12347 | Citra Dewi       |
+-------+------------------+
```

## Test Case 1: Pendaftaran Berhasil (Data Valid)

### Langkah:
1. Buka browser: `http://localhost:8000/signup?role=ortu`
2. Isi form:
   - Nama Lengkap: `Pak Ahmad`
   - Email: `ahmad.ortu@test.com`
   - Password: `password123`
   - Konfirmasi Password: `password123`
   - Nama Anak: `Ahmad Santoso` (sesuai database)
   - NIS Anak: `12345` (sesuai database)
3. Klik tombol "Daftar"

### Expected Result:
✓ Redirect ke landing page
✓ Muncul notifikasi: "Pendaftaran berhasil! Akun Anda menunggu persetujuan admin..."
✓ Data tersimpan di database dengan status 'pending'

### Verifikasi Database:
```sql
SELECT nama, email, nama_anak, nis_anak, status 
FROM users 
WHERE email = 'ahmad.ortu@test.com';
```

Expected:
```
+-----------+----------------------+-----------------+----------+---------+
| nama      | email                | nama_anak       | nis_anak | status  |
+-----------+----------------------+-----------------+----------+---------+
| Pak Ahmad | ahmad.ortu@test.com  | Ahmad Santoso   | 12345    | pending |
+-----------+----------------------+-----------------+----------+---------+
```

---

## Test Case 2: Pendaftaran Gagal (NIS Salah)

### Langkah:
1. Buka browser: `http://localhost:8000/signup?role=ortu`
2. Isi form:
   - Nama Lengkap: `Pak Budi`
   - Email: `budi.ortu@test.com`
   - Password: `password123`
   - Konfirmasi Password: `password123`
   - Nama Anak: `Ahmad Santoso` (benar)
   - NIS Anak: `99999` (SALAH - tidak ada di database)
3. Klik tombol "Daftar"

### Expected Result:
✗ Tetap di halaman form
✗ Muncul error: "Data anak tidak ditemukan. Pastikan Nama dan NIS anak sesuai..."
✗ Data TIDAK tersimpan di database

### Verifikasi Database:
```sql
SELECT COUNT(*) as jumlah 
FROM users 
WHERE email = 'budi.ortu@test.com';
```

Expected: `jumlah = 0` (tidak ada data)

---

## Test Case 3: Pendaftaran Gagal (Nama Salah)

### Langkah:
1. Buka browser: `http://localhost:8000/signup?role=ortu`
2. Isi form:
   - Nama Lengkap: `Pak Citra`
   - Email: `citra.ortu@test.com`
   - Password: `password123`
   - Konfirmasi Password: `password123`
   - Nama Anak: `Ahmad Salah` (SALAH - tidak sesuai database)
   - NIS Anak: `12345` (benar)
3. Klik tombol "Daftar"

### Expected Result:
✗ Tetap di halaman form
✗ Muncul error: "Data anak tidak ditemukan. Pastikan Nama dan NIS anak sesuai..."
✗ Data TIDAK tersimpan di database

---

## Test Case 4: Pendaftaran Gagal (Field Kosong)

### Langkah:
1. Buka browser: `http://localhost:8000/signup?role=ortu`
2. Isi form:
   - Nama Lengkap: `Pak Dedi`
   - Email: `dedi.ortu@test.com`
   - Password: `password123`
   - Konfirmasi Password: `password123`
   - Nama Anak: (KOSONG)
   - NIS Anak: (KOSONG)
3. Klik tombol "Daftar"

### Expected Result:
✗ Tetap di halaman form
✗ Muncul error validasi: "Nama anak wajib diisi untuk pendaftaran orang tua"
✗ Data TIDAK tersimpan di database

---

## Test Case 5: Admin Approval

### Langkah:
1. Login sebagai admin: `http://localhost:8000/admin/login`
   - Email: `admin@test.com`
   - Password: `password`
2. Buka halaman users: `http://localhost:8000/users`
3. Cari user dengan status "Menunggu" (pending)
4. Verifikasi data anak yang ditampilkan
5. Klik tombol "Setujui" (✓)

### Expected Result:
✓ Muncul konfirmasi approval
✓ Setelah approve, status berubah menjadi "Disetujui"
✓ User dapat login dengan akun tersebut

### Verifikasi Database:
```sql
SELECT nama, email, status, verified_by, verified_at 
FROM users 
WHERE email = 'ahmad.ortu@test.com';
```

Expected:
```
+-----------+----------------------+----------+-------------+---------------------+
| nama      | email                | status   | verified_by | verified_at         |
+-----------+----------------------+----------+-------------+---------------------+
| Pak Ahmad | ahmad.ortu@test.com  | approved | 1           | 2026-01-11 20:45:00 |
+-----------+----------------------+----------+-------------+---------------------+
```

---

## Test Case 6: Login Setelah Approval

### Langkah:
1. Buka landing page: `http://localhost:8000`
2. Klik "Masuk"
3. Isi form login:
   - Email: `ahmad.ortu@test.com`
   - Password: `password123`
4. Klik "Masuk"

### Expected Result:
✓ Redirect ke dashboard orang tua
✓ Muncul notifikasi: "Selamat datang Orang Tua!"
✓ Dashboard menampilkan data anak

---

## Test Case 7: Case Sensitivity Nama

### Langkah:
1. Buka browser: `http://localhost:8000/signup?role=ortu`
2. Isi form dengan nama anak huruf kecil semua:
   - Nama Anak: `ahmad santoso` (huruf kecil)
   - NIS Anak: `12345`
   - (field lain diisi lengkap)
3. Klik "Daftar"

### Expected Result:
✗ Muncul error jika nama di database adalah "Ahmad Santoso" (huruf besar)
✓ Sistem case-sensitive untuk validasi nama

### Note:
Jika ingin case-insensitive, bisa diubah di AuthController:
```php
$siswa = \App\Models\Siswa::whereRaw('LOWER(nis) = ?', [strtolower($validated['nis_anak'])])
    ->whereRaw('LOWER(nama_siswa) = ?', [strtolower($validated['nama_anak'])])
    ->first();
```

---

## Checklist Test

- [ ] Test Case 1: Pendaftaran berhasil dengan data valid
- [ ] Test Case 2: Pendaftaran gagal dengan NIS salah
- [ ] Test Case 3: Pendaftaran gagal dengan nama salah
- [ ] Test Case 4: Pendaftaran gagal dengan field kosong
- [ ] Test Case 5: Admin approval berhasil
- [ ] Test Case 6: Login berhasil setelah approval
- [ ] Test Case 7: Case sensitivity nama

## Hasil Test

| Test Case | Status | Catatan |
|-----------|--------|---------|
| TC1       | ☐ Pass / ☐ Fail | |
| TC2       | ☐ Pass / ☐ Fail | |
| TC3       | ☐ Pass / ☐ Fail | |
| TC4       | ☐ Pass / ☐ Fail | |
| TC5       | ☐ Pass / ☐ Fail | |
| TC6       | ☐ Pass / ☐ Fail | |
| TC7       | ☐ Pass / ☐ Fail | |

## Troubleshooting

### Error: "SQLSTATE[42S22]: Column not found"
**Solusi**: Jalankan migration
```bash
php artisan migrate
```

### Error: Field tidak muncul di form
**Solusi**: Clear cache browser atau hard refresh (Ctrl + Shift + R)

### Error: Validasi tidak berjalan
**Solusi**: 
1. Cek console browser untuk error JavaScript
2. Pastikan URL mengandung `?role=ortu`
3. Clear cache Laravel:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```
