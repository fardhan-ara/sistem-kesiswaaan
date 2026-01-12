# 🎯 Cara Memperbaiki Data Master Siswa - Step by Step

## 📋 Daftar Isi
1. [Identifikasi Masalah](#identifikasi-masalah)
2. [Solusi Cepat](#solusi-cepat)
3. [Solusi Detail](#solusi-detail)
4. [Verifikasi](#verifikasi)
5. [FAQ](#faq)

---

## 🔍 Identifikasi Masalah

### Gejala 1: Tidak Bisa Akses Halaman Siswa
```
❌ Error: "Unauthorized. Required roles: admin, kesiswaan"
❌ Redirect ke halaman lain
❌ Error 403 Forbidden
```

### Gejala 2: CRUD Tidak Berfungsi
```
❌ Tombol "Tambah Siswa" tidak berfungsi
❌ Form tidak bisa submit
❌ Edit/Hapus tidak berfungsi
❌ Tidak ada feedback setelah submit
```

---

## ⚡ Solusi Cepat (5 Menit)

### Opsi 1: Gunakan User Default

**Langkah 1**: Logout dari sistem

**Langkah 2**: Login dengan user admin default
```
Email    : admin@test.com
Password : password
```

**Langkah 3**: Akses Data Master Siswa
```
URL: http://localhost:8000/siswa
```

**Langkah 4**: Test CRUD
- ✅ Klik "Tambah Siswa"
- ✅ Isi form dan submit
- ✅ Edit siswa
- ✅ Hapus siswa

### Opsi 2: Perbaiki Role User Anda

**Langkah 1**: Buka Command Prompt/Terminal di folder project

**Langkah 2**: Cek akses user Anda
```bash
php artisan siswa:check-access email@anda.com
```

**Langkah 3**: Jika tidak bisa akses, perbaiki role
```bash
php artisan user:fix-role email@anda.com admin
```

**Langkah 4**: Login ulang dan test

---

## 🔧 Solusi Detail

### Metode 1: Via Command Artisan (RECOMMENDED)

#### Step 1: Cek Status Sistem
```bash
# Buka Command Prompt/Terminal
cd c:\xampp\htdocs\sistem-kesiswaan

# Cek statistik lengkap
php artisan siswa:check-access
```

**Output yang diharapkan**:
```
=== SISTEM KESISWAAN - CHECK SISWA ACCESS ===

1. USER STATISTICS
+------------+-------+
| Role       | Count |
+------------+-------+
| admin      | 1     |
| kesiswaan  | 1     |
| guru       | 1     |
+------------+-------+

2. USERS WITH SISWA ACCESS (admin & kesiswaan)
+------------------+----------------------+------------+----------+
| Nama             | Email                | Role       | Verified |
+------------------+----------------------+------------+----------+
| Admin            | admin@test.com       | admin      | Yes      |
| Staff Kesiswaan  | kesiswaan@test.com   | kesiswaan  | Yes      |
+------------------+----------------------+------------+----------+

3. SISWA STATISTICS
+---------------+-------+
| Metric        | Value |
+---------------+-------+
| Total Siswa   | 10    |
| Laki-laki     | 6     |
| Perempuan     | 4     |
+---------------+-------+
```

#### Step 2: Cek User Spesifik
```bash
php artisan siswa:check-access guru@test.com
```

**Output jika user TIDAK BISA akses**:
```
User Information:
+----------------+------------------+
| Field          | Value            |
+----------------+------------------+
| Nama           | Guru Test        |
| Email          | guru@test.com    |
| Role           | guru             |
| Email Verified | Yes              |
| Can Access     | NO ✗             |
+----------------+------------------+

⚠ User ini TIDAK BISA mengakses Data Master Siswa!
Role yang dibutuhkan: admin atau kesiswaan

Apakah Anda ingin mengubah role user ini menjadi admin? (yes/no):
```

#### Step 3: Perbaiki Role User
```bash
# Ubah role menjadi admin
php artisan user:fix-role guru@test.com admin

# Atau ubah menjadi kesiswaan
php artisan user:fix-role guru@test.com kesiswaan
```

**Output**:
```
✓ Role user berhasil diubah!
+-------+-----------+-----------+
| Field | Old Value | New Value |
+-------+-----------+-----------+
| Nama  | Guru Test | Guru Test |
| Email | guru@...  | guru@...  |
| Role  | guru      | admin     |
+-------+-----------+-----------+

✓ User sekarang BISA mengakses Data Master Siswa!
```

#### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Step 5: Login dan Test
1. Logout dari sistem
2. Login dengan user yang sudah diperbaiki
3. Akses: `http://localhost:8000/siswa`
4. Test CRUD

---

### Metode 2: Via Browser Debug

#### Step 1: Cek Akses via Browser
```
URL: http://localhost:8000/debug-siswa
```

**Response JSON**:
```json
{
  "user": "Guru Test",
  "role": "guru",
  "can_access_siswa": false,
  "siswa_count": 10
}
```

#### Step 2: Jika `can_access_siswa: false`
Gunakan Metode 1 (Command Artisan) untuk perbaiki role

---

### Metode 3: Via Database (Manual)

#### Step 1: Buka phpMyAdmin
```
URL: http://localhost/phpmyadmin
```

#### Step 2: Pilih Database
```
Database: sistem-kesiswaan
```

#### Step 3: Cek Data User
```sql
SELECT id, nama, email, role, email_verified_at 
FROM users 
ORDER BY role;
```

#### Step 4: Update Role User
```sql
-- Ganti 'email@user.com' dengan email user Anda
UPDATE users 
SET role = 'admin' 
WHERE email = 'email@user.com';
```

#### Step 5: Verifikasi Email (jika belum)
```sql
UPDATE users 
SET email_verified_at = NOW() 
WHERE email = 'email@user.com';
```

#### Step 6: Clear Cache dan Login Ulang

---

### Metode 4: Via Laravel Tinker

#### Step 1: Buka Tinker
```bash
php artisan tinker
```

#### Step 2: Cari User
```php
$user = App\Models\User::where('email', 'email@user.com')->first();
```

#### Step 3: Cek Role
```php
echo $user->role;
```

#### Step 4: Update Role
```php
$user->role = 'admin';
$user->save();
```

#### Step 5: Verifikasi
```php
echo $user->role; // Harus menampilkan: admin
```

#### Step 6: Exit Tinker
```php
exit
```

---

## ✅ Verifikasi

### Checklist Setelah Perbaikan

#### 1. Cek Akses
- [ ] Bisa akses `http://localhost:8000/siswa`
- [ ] Tidak ada error 403
- [ ] Tidak redirect ke halaman lain

#### 2. Test CREATE (Tambah Siswa)
- [ ] Tombol "Tambah Siswa" berfungsi
- [ ] Form muncul dengan lengkap
- [ ] Dropdown Kelas terisi
- [ ] Dropdown Tahun Ajaran terisi
- [ ] Bisa submit form
- [ ] Muncul alert success setelah submit
- [ ] Data muncul di tabel

#### 3. Test READ (Lihat Data)
- [ ] Tabel siswa muncul
- [ ] Data siswa tampil lengkap
- [ ] Pagination berfungsi (jika data > 20)
- [ ] Badge poin pelanggaran muncul

#### 4. Test UPDATE (Edit Siswa)
- [ ] Tombol edit (icon pensil) berfungsi
- [ ] Form edit muncul dengan data lama
- [ ] Bisa ubah data
- [ ] Bisa submit form
- [ ] Muncul alert success
- [ ] Data berubah di tabel

#### 5. Test DELETE (Hapus Siswa)
- [ ] Tombol hapus (icon trash) berfungsi
- [ ] Muncul konfirmasi hapus
- [ ] Bisa hapus siswa (yang tidak punya pelanggaran)
- [ ] Muncul alert success
- [ ] Data hilang dari tabel

---

## ❓ FAQ (Frequently Asked Questions)

### Q1: Kenapa saya tidak bisa akses Data Master Siswa?
**A**: Hanya user dengan role `admin` atau `kesiswaan` yang bisa akses. Gunakan command:
```bash
php artisan siswa:check-access email@anda.com
```

### Q2: Bagaimana cara mengubah role user?
**A**: Gunakan command:
```bash
php artisan user:fix-role email@anda.com admin
```

### Q3: Dropdown Kelas kosong, kenapa?
**A**: Belum ada data kelas. Tambahkan kelas terlebih dahulu:
```
URL: http://localhost:8000/kelas
```

### Q4: Dropdown Tahun Ajaran kosong, kenapa?
**A**: Belum ada data tahun ajaran. Tambahkan tahun ajaran terlebih dahulu:
```
URL: http://localhost:8000/tahun-ajaran
```

### Q5: Form tidak bisa submit, kenapa?
**A**: Pastikan semua field wajib sudah diisi:
- NIS (harus unique)
- Nama Siswa
- Jenis Kelamin
- Kelas
- Tahun Ajaran

### Q6: Kenapa siswa tidak bisa dihapus?
**A**: Siswa yang memiliki data pelanggaran atau prestasi tidak bisa dihapus untuk menjaga integritas data.

### Q7: Bagaimana cara melihat error yang terjadi?
**A**: Cek log Laravel:
```
File: storage/logs/laravel.log
```

### Q8: Bagaimana cara reset database?
**A**: HATI-HATI, ini akan menghapus semua data:
```bash
php artisan migrate:fresh --seed
```

### Q9: Apakah ada user default yang bisa digunakan?
**A**: Ya, ada:
```
Email    : admin@test.com
Password : password
Role     : admin (bisa akses Data Master Siswa)
```

### Q10: Bagaimana cara membuat user admin baru?
**A**: Via SQL:
```sql
INSERT INTO users (nama, email, password, role, email_verified_at, created_at, updated_at)
VALUES (
    'Admin Baru',
    'admin.baru@test.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    NOW(),
    NOW(),
    NOW()
);
```
Password default: `password`

---

## 📞 Bantuan Lebih Lanjut

Jika masih ada masalah setelah mengikuti panduan ini:

### 1. Baca Dokumentasi Lengkap
- `PERBAIKAN_SISWA.md` - Panduan lengkap
- `TROUBLESHOOTING_SISWA.md` - Troubleshooting detail
- `QUICK_FIX_SISWA.txt` - Solusi cepat

### 2. Gunakan Tools Debugging
```bash
# Cek akses
php artisan siswa:check-access

# Cek log
tail -f storage/logs/laravel.log

# Debug via browser
http://localhost:8000/debug-siswa
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Restart Server
```bash
# Stop server (Ctrl+C)
# Start ulang
php artisan serve
```

---

## 🎉 Kesimpulan

Dengan mengikuti panduan ini, masalah Data Master Siswa seharusnya sudah teratasi:

✅ Akses ke Data Master Siswa berfungsi
✅ CRUD (Create, Read, Update, Delete) berfungsi
✅ Error handling yang baik
✅ Feedback yang jelas untuk user
✅ Tools debugging tersedia

**Selamat menggunakan Sistem Kesiswaan!** 🚀

---

## 📝 Catatan Penting

1. **Backup Database**: Selalu backup database sebelum melakukan perubahan besar
2. **Role yang Tepat**: Gunakan role yang sesuai untuk setiap user
3. **Jangan Hapus Data**: Siswa dengan pelanggaran/prestasi tidak bisa dihapus
4. **Monitor Log**: Cek log secara berkala untuk mendeteksi masalah
5. **Clear Cache**: Clear cache setelah melakukan perubahan konfigurasi

---

**Dibuat dengan ❤️ untuk Sistem Kesiswaan**
