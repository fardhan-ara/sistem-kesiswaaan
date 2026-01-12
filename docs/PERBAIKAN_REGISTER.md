# Perbaikan Register Public & Database Query

## Masalah yang Diperbaiki

### 1. Error SQLSTATE[42S22]: Column not found 'status'
**Lokasi Error:** Query TahunAjaran menggunakan kolom `status` yang tidak ada

**Penyebab:** 
- Kolom di database adalah `status_aktif` (enum: 'aktif', 'nonaktif')
- Beberapa query menggunakan `where('status', 'aktif')` atau `where('status_aktif', 1)`

**Solusi:**
- AuthController.php: Ubah `where('status', 'aktif')` → `where('status_aktif', 'aktif')`
- PelanggaranController.php: Ubah `where('status_aktif', 1)` → `where('status_aktif', 'aktif')`
- SiswaController.php: Ubah `where('status_aktif', 1)` → `where('status_aktif', 'aktif')`

### 2. UI/UX Register Public
**Perbaikan:**
- Menambahkan JavaScript libraries (jQuery, Bootstrap, AdminLTE)
- Menggunakan AdminLTE input-group dengan icon FontAwesome
- Menambahkan validasi client-side untuk password matching
- Memperbaiki HTML tag yang tidak lengkap (CSS link)
- Menambahkan placeholder pada input fields
- Validasi HTML5 (pattern untuk NIP, minlength untuk password)

## File yang Dimodifikasi

1. `app/Http/Controllers/AuthController.php`
   - Baris 100: Query TahunAjaran
   - Baris 175: Query TahunAjaran

2. `app/Http/Controllers/PelanggaranController.php`
   - Baris 52: Query TahunAjaran

3. `app/Http/Controllers/SiswaController.php`
   - Baris 28: Query TahunAjaran
   - Baris 45: Query TahunAjaran

4. `resources/views/auth/register_public.blade.php`
   - Struktur HTML menggunakan AdminLTE input-group
   - Menambahkan JavaScript libraries
   - Validasi form client-side

## Cara Testing

1. Pastikan database sudah di-migrate dan di-seed:
   ```bash
   php artisan migrate:fresh --seed
   ```

2. Akses halaman register untuk setiap role:
   - Siswa: http://127.0.0.1:8000/signup?role=siswa
   - Orang Tua: http://127.0.0.1:8000/signup?role=ortu
   - Kesiswaan: http://127.0.0.1:8000/signup?role=kesiswaan
   - Guru: http://127.0.0.1:8000/signup?role=guru

3. Test validasi:
   - Password minimal 8 karakter
   - Password confirmation harus sama
   - NIP harus 8-20 digit angka (untuk kesiswaan/verifikator)

## Catatan Penting

- Kolom `status_aktif` di tabel `tahun_ajarans` adalah ENUM dengan nilai: 'aktif' atau 'nonaktif'
- Jangan gunakan nilai integer (0, 1) untuk query status_aktif
- Pastikan selalu ada minimal 1 tahun ajaran dengan status_aktif = 'aktif'
