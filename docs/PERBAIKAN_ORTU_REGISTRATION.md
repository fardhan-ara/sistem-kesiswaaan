# Perbaikan Pendaftaran Orang Tua

## Perubahan yang Dilakukan

### 1. Database Schema
- **Tabel**: `users`
- **Kolom Baru**:
  - `nama_anak` (string, nullable) - Nama lengkap anak/siswa
  - `nis_anak` (string, nullable) - NIS anak/siswa

### 2. Form Pendaftaran
- Menambahkan 2 field baru untuk role **Orang Tua**:
  - **Nama Anak (Siswa)** - Input text untuk nama lengkap anak
  - **NIS Anak** - Input text untuk Nomor Induk Siswa anak

### 3. Validasi Data
- Field `nama_anak` dan `nis_anak` **wajib diisi** untuk role `ortu`
- Sistem akan **memvalidasi kecocokan data** dengan tabel `siswas`:
  - Nama anak harus sesuai dengan `nama_siswa` di database
  - NIS anak harus sesuai dengan `nis` di database
  - Jika tidak cocok, pendaftaran akan ditolak dengan pesan error

### 4. Tampilan Admin
- Di halaman **Manajemen User** (`/users`), admin dapat melihat:
  - Nama anak dan NIS anak pada kolom Status
  - Indikator jika data anak belum lengkap

## Cara Kerja

### Alur Pendaftaran Orang Tua

1. **User mengakses halaman pendaftaran** dengan role `ortu`
   - URL: `http://localhost:8000/signup?role=ortu`

2. **User mengisi form**:
   - Nama Lengkap (orang tua)
   - Email
   - Password
   - Konfirmasi Password
   - **Nama Anak (Siswa)** ← BARU (tidak harus persis huruf besar/kecil)
   - **NIS Anak** ← BARU (harus benar)

3. **Sistem melakukan validasi**:
   ```php
   // Cek berdasarkan NIS (paling penting) dan nama (case-insensitive)
   $siswa = Siswa::where('nis', $nis_anak)
                 ->whereRaw('LOWER(nama_siswa) = ?', [strtolower($nama_anak)])
                 ->first();
   
   if (!$siswa) {
       // Pendaftaran ditolak
       return error('Data anak dengan NIS tidak ditemukan');
   }
   ```

4. **Jika validasi berhasil**:
   - User dibuat dengan status `pending`
   - Data `nama_anak` dan `nis_anak` disimpan
   - User menunggu approval admin

5. **Admin melakukan approval**:
   - Admin dapat melihat data anak yang didaftarkan
   - Admin memverifikasi kecocokan data
   - Admin menyetujui atau menolak pendaftaran

## Contoh Penggunaan

### Pendaftaran Berhasil
```
Nama Lengkap: Budi Santoso
Email: budi@example.com
Password: ******
Nama Anak: Ahmad Santoso
NIS Anak: 12345

✓ Data ditemukan di database siswa
✓ Pendaftaran berhasil, menunggu approval admin
```

### Pendaftaran Gagal
```
Nama Lengkap: Budi Santoso
Email: budi@example.com
Password: ******
Nama Anak: Ahmad Santoso
NIS Anak: 99999

✗ Data anak tidak ditemukan
✗ Pastikan Nama dan NIS anak sesuai dengan data siswa yang terdaftar
```

## Keuntungan

1. **Validasi Otomatis**: Sistem memastikan orang tua yang mendaftar benar-benar memiliki anak di sekolah
2. **Data Akurat**: Admin dapat memverifikasi kecocokan data dengan mudah
3. **Keamanan**: Mencegah pendaftaran orang tua palsu
4. **Transparansi**: Admin dapat melihat data anak langsung di halaman manajemen user
5. **User-Friendly**: Nama anak tidak harus persis huruf besar/kecilnya, yang penting NIS benar

## File yang Dimodifikasi

1. `database/migrations/2026_01_11_203341_add_child_info_to_users_table.php` - Migration baru
2. `app/Models/User.php` - Menambahkan `nama_anak` dan `nis_anak` ke fillable
3. `app/Http/Controllers/AuthController.php` - Validasi dan logika pendaftaran
4. `resources/views/auth/register_public.blade.php` - Form pendaftaran
5. `resources/views/users/index.blade.php` - Tampilan data anak di admin

## Testing

### Manual Testing
1. Buka `http://localhost:8000/signup?role=ortu`
2. Isi form dengan data siswa yang **ada** di database
3. Submit form → Harus berhasil
4. Isi form dengan data siswa yang **tidak ada** di database
5. Submit form → Harus gagal dengan pesan error

### Database Check
```sql
-- Cek data siswa yang tersedia
SELECT nis, nama_siswa FROM siswas;

-- Cek user orang tua yang sudah mendaftar
SELECT nama, email, nama_anak, nis_anak, status 
FROM users 
WHERE role = 'ortu';
```

## Troubleshooting

### Error: "Data anak tidak ditemukan"
**Penyebab**: Nama atau NIS anak tidak sesuai dengan data di database

**Solusi**:
1. Pastikan nama anak ditulis **persis sama** dengan data di database (case-sensitive)
2. Pastikan NIS anak benar
3. Hubungi admin untuk memverifikasi data siswa

### Field tidak muncul di form
**Penyebab**: JavaScript tidak berjalan atau role tidak terdeteksi

**Solusi**:
1. Clear cache browser (Ctrl + Shift + Delete)
2. Pastikan URL mengandung `?role=ortu`
3. Cek console browser untuk error JavaScript

## Catatan Penting

- **NIS anak HARUS benar** - Ini yang paling penting untuk validasi
- **Nama anak tidak harus persis** - Huruf besar/kecil tidak masalah (case-insensitive)
  - Contoh: "Ahmad Santoso" = "ahmad santoso" = "AHMAD SANTOSO" → Semua valid
- Validasi dilakukan **sebelum** user dibuat, jadi tidak ada data sampah di database
- Admin tetap perlu melakukan approval setelah validasi otomatis berhasil
