# Troubleshooting - Error "Email Already Taken"

## Masalah
Saat mendaftar sebagai orang tua, muncul error:
```
The email has already been taken.
```

Padahal email yang digunakan belum pernah dipakai sebelumnya.

## Penyebab

1. **Email benar-benar sudah terdaftar** - Mungkin Anda pernah mendaftar sebelumnya dan lupa
2. **Data testing lama** - Ada data testing yang tersisa di database
3. **Validasi Laravel terlalu generic** - Error message tidak jelas email mana yang sudah terdaftar

## Solusi yang Sudah Diterapkan

### 1. Custom Error Message
Sekarang sistem akan menampilkan error yang lebih jelas:
```
Email aep@gmail.com sudah terdaftar dengan role ortu. 
Silakan gunakan email lain atau login jika ini akun Anda.
```

### 2. Validasi Manual
Sistem sekarang melakukan pengecekan manual sebelum validasi Laravel, sehingga bisa memberikan pesan error yang lebih informatif.

## Cara Mengecek Email yang Sudah Terdaftar

### Via Database (MySQL)
```sql
-- Cek semua email yang terdaftar
SELECT email, role, status, created_at 
FROM users 
ORDER BY created_at DESC;

-- Cek email spesifik
SELECT email, role, status, created_at 
FROM users 
WHERE email = 'email@anda.com';

-- Cek email orang tua saja
SELECT email, nama, nama_anak, nis_anak, status 
FROM users 
WHERE role = 'ortu';
```

### Via Tinker
```bash
php artisan tinker
```

Kemudian jalankan:
```php
// Lihat semua email
DB::table('users')->select('email', 'role')->get();

// Cek email spesifik
User::where('email', 'email@anda.com')->first();

// Lihat email orang tua
User::where('role', 'ortu')->get(['email', 'nama', 'status']);
```

## Cara Mengatasi

### Opsi 1: Gunakan Email Lain
Jika email memang sudah terdaftar, gunakan email yang berbeda untuk mendaftar.

### Opsi 2: Login dengan Akun yang Ada
Jika Anda pernah mendaftar sebelumnya, coba login dengan email tersebut:
1. Buka `http://localhost:8000`
2. Klik "Masuk"
3. Masukkan email dan password

### Opsi 3: Hapus Data Lama (Untuk Testing)
**HATI-HATI! Hanya untuk development/testing**

```sql
-- Hapus user dengan email tertentu
DELETE FROM users WHERE email = 'email@anda.com';

-- Atau hapus semua user ortu yang pending
DELETE FROM users WHERE role = 'ortu' AND status = 'pending';
```

### Opsi 4: Reset Password (Jika Lupa)
Jika Anda lupa password, minta admin untuk reset:

```sql
-- Admin bisa update password manual
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'email@anda.com';
-- Password di atas adalah: password
```

## Testing Setelah Perbaikan

### Test 1: Email Baru (Harus Berhasil)
1. Buka `http://localhost:8000/signup?role=ortu`
2. Gunakan email yang BELUM pernah terdaftar
3. Isi semua field dengan benar
4. Submit → Harus berhasil

### Test 2: Email yang Sudah Ada (Harus Error dengan Pesan Jelas)
1. Buka `http://localhost:8000/signup?role=ortu`
2. Gunakan email yang SUDAH terdaftar (misal: `aep@gmail.com`)
3. Isi semua field
4. Submit → Harus muncul error:
   ```
   Email aep@gmail.com sudah terdaftar dengan role ortu. 
   Silakan gunakan email lain atau login jika ini akun Anda.
   ```

### Test 3: Data Anak Salah (Harus Error dengan Detail)
1. Buka `http://localhost:8000/signup?role=ortu`
2. Gunakan email baru
3. Isi nama anak dan NIS yang TIDAK ADA di database
4. Submit → Harus muncul error:
   ```
   Data anak tidak ditemukan. Pastikan Nama Anak "Nama Yang Salah" 
   dan NIS "12345" sesuai dengan data siswa yang terdaftar. 
   Hubungi admin jika ada kesalahan data.
   ```

## Perintah Berguna

### Clear Cache (Jika Form Tidak Update)
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Restart Server
```bash
# Stop server (Ctrl + C)
# Start lagi
php artisan serve
```

### Cek Log Error
```bash
# Windows
type storage\logs\laravel.log

# Atau buka file di:
# storage/logs/laravel.log
```

## Contoh Kasus Nyata

### Kasus 1: Email Sudah Terdaftar
**Input:**
- Email: `aep@gmail.com`
- Role: `ortu`

**Error Lama:**
```
The email has already been taken.
```

**Error Baru (Lebih Jelas):**
```
Email aep@gmail.com sudah terdaftar dengan role ortu. 
Silakan gunakan email lain atau login jika ini akun Anda.
```

**Solusi:**
- Gunakan email lain: `aep2@gmail.com` atau `aep.ortu@gmail.com`
- Atau login dengan akun yang sudah ada

### Kasus 2: Data Anak Tidak Ditemukan
**Input:**
- Email: `budi@gmail.com` (baru)
- Nama Anak: `Ahmad Salah`
- NIS Anak: `99999`

**Error:**
```
Data anak tidak ditemukan. Pastikan Nama Anak "Ahmad Salah" 
dan NIS "99999" sesuai dengan data siswa yang terdaftar. 
Hubungi admin jika ada kesalahan data.
```

**Solusi:**
1. Cek data siswa yang benar:
   ```sql
   SELECT nis, nama_siswa FROM siswas;
   ```
2. Gunakan nama dan NIS yang sesuai dengan database

## FAQ

**Q: Bagaimana cara mengetahui email apa saja yang sudah terdaftar?**
A: Jalankan query SQL:
```sql
SELECT email, role FROM users;
```

**Q: Apakah bisa menggunakan email yang sama untuk role berbeda?**
A: Tidak. Satu email hanya bisa untuk satu akun.

**Q: Bagaimana jika saya lupa sudah mendaftar atau belum?**
A: Coba login dulu. Jika gagal, berarti belum terdaftar atau password salah.

**Q: Apakah admin bisa melihat semua email yang terdaftar?**
A: Ya, admin bisa melihat di halaman `/users` atau via database.

**Q: Bagaimana cara menghapus akun yang salah?**
A: Hubungi admin untuk menghapus akun, atau admin bisa hapus via halaman users management.

## Kesimpulan

Perbaikan yang dilakukan:
✅ Error message lebih jelas dan informatif
✅ Menampilkan email dan role yang sudah terdaftar
✅ Validasi manual sebelum Laravel validation
✅ Pesan error untuk data anak juga lebih detail

Sekarang user akan tahu persis kenapa pendaftaran gagal dan apa yang harus dilakukan!
