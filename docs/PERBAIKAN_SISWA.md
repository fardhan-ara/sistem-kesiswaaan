# PERBAIKAN DATA MASTER SISWA

## 🎯 Masalah yang Telah Diperbaiki

### 1. ✅ Akses ke Data Master Siswa
- Middleware role sekarang memberikan pesan error yang jelas
- Redirect ke dashboard dengan notifikasi jika akses ditolak
- Logging untuk debugging akses

### 2. ✅ CRUD Tidak Berfungsi
- Semua operasi CRUD (Create, Read, Update, Delete) sekarang berfungsi
- Error handling yang lebih baik
- Alert success/error di setiap halaman
- Logging untuk tracking operasi

### 3. ✅ Validasi Form
- Validasi input yang ketat
- Pesan error yang jelas
- Highlight field yang error

## 🚀 Cara Menggunakan

### Langkah 1: Cek Akses User

Jalankan command berikut untuk mengecek apakah user Anda bisa akses Data Master Siswa:

```bash
php artisan siswa:check-access email@anda.com
```

Atau untuk melihat statistik lengkap:

```bash
php artisan siswa:check-access
```

### Langkah 2: Perbaiki Role User (Jika Perlu)

Jika user Anda tidak bisa akses, ubah role menjadi admin atau kesiswaan:

```bash
# Ubah menjadi admin
php artisan user:fix-role email@anda.com admin

# Atau ubah menjadi kesiswaan
php artisan user:fix-role email@anda.com kesiswaan
```

### Langkah 3: Login dan Akses Data Siswa

1. Login ke sistem dengan user yang sudah diperbaiki
2. Akses menu Data Master Siswa atau langsung ke: `http://localhost:8000/siswa`
3. Sekarang Anda bisa melakukan operasi CRUD

## 📋 Operasi CRUD yang Tersedia

### 1. CREATE (Tambah Siswa)
- Klik tombol "Tambah Siswa"
- Isi form dengan lengkap:
  - NIS (wajib, unique)
  - Nama Siswa (wajib)
  - Jenis Kelamin (wajib)
  - Kelas (wajib)
  - Tahun Ajaran (wajib)
- Klik "Simpan"
- Akan muncul notifikasi sukses jika berhasil

### 2. READ (Lihat Data)
- Data siswa ditampilkan dalam tabel
- Menampilkan: NIS, Nama, Jenis Kelamin, Kelas, Tahun Ajaran, Total Poin
- Pagination otomatis jika data lebih dari 20
- Badge warna untuk kategori poin pelanggaran

### 3. UPDATE (Edit Siswa)
- Klik tombol edit (icon pensil) pada baris siswa
- Ubah data yang diperlukan
- Klik "Update"
- Akan muncul notifikasi sukses jika berhasil

### 4. DELETE (Hapus Siswa)
- Klik tombol hapus (icon trash) pada baris siswa
- Konfirmasi penghapusan
- Akan muncul notifikasi sukses jika berhasil

**CATATAN**: Siswa yang memiliki data pelanggaran atau prestasi TIDAK BISA dihapus untuk menjaga integritas data.

## 🔍 Debugging

### Cek Akses via Browser
Akses URL: `http://localhost:8000/debug-siswa`

Response JSON akan menampilkan:
```json
{
  "user": "Nama User",
  "role": "admin",
  "can_access_siswa": true,
  "siswa_count": 10
}
```

### Cek Log Laravel
Buka file: `storage/logs/laravel.log`

Keyword untuk dicari:
- `RoleMiddleware check` - pengecekan akses
- `Storing siswa data` - proses create
- `Updating siswa` - proses update
- `Error` - error yang terjadi

### Clear Cache (Jika Diperlukan)
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🔐 User Default (dari Seeder)

| Role | Email | Password | Akses Siswa |
|------|-------|----------|-------------|
| Admin | admin@test.com | password | ✅ YA |
| Kesiswaan | kesiswaan@test.com | password | ✅ YA |
| Guru | guru@test.com | password | ❌ TIDAK |
| Siswa | siswa@test.com | password | ❌ TIDAK |

## 📊 Validasi Form

### Field yang Wajib Diisi:
1. **NIS**: Nomor Induk Siswa (harus unique)
2. **Nama Siswa**: Nama lengkap siswa (max 255 karakter)
3. **Jenis Kelamin**: L (Laki-laki) atau P (Perempuan)
4. **Kelas**: Pilih dari dropdown (harus sudah ada data kelas)
5. **Tahun Ajaran**: Pilih dari dropdown (harus sudah ada data tahun ajaran)

### Pesan Error Validasi:
- "NIS wajib diisi" - jika NIS kosong
- "NIS sudah terdaftar" - jika NIS sudah digunakan siswa lain
- "Nama siswa wajib diisi" - jika nama kosong
- "Jenis kelamin wajib dipilih" - jika jenis kelamin tidak dipilih
- "Kelas wajib dipilih" - jika kelas tidak dipilih
- "Tahun ajaran wajib dipilih" - jika tahun ajaran tidak dipilih

## 🛠️ Troubleshooting

### Masalah: "Unauthorized. Required roles: admin, kesiswaan"
**Solusi**: 
```bash
php artisan user:fix-role email@anda.com admin
```

### Masalah: "Gagal memuat data siswa"
**Solusi**:
1. Cek koneksi database di `.env`
2. Pastikan migration sudah dijalankan: `php artisan migrate`
3. Cek log: `storage/logs/laravel.log`

### Masalah: "Kelas tidak muncul di dropdown"
**Solusi**:
1. Tambahkan data kelas terlebih dahulu
2. Akses: `http://localhost:8000/kelas`

### Masalah: "Tahun ajaran tidak muncul di dropdown"
**Solusi**:
1. Tambahkan data tahun ajaran terlebih dahulu
2. Akses: `http://localhost:8000/tahun-ajaran`

### Masalah: Form tidak bisa submit
**Solusi**:
1. Cek console browser (F12) untuk error JavaScript
2. Pastikan semua field wajib sudah diisi
3. Cek log Laravel untuk error server

## 📝 SQL Helper

File `fix_siswa_access.sql` berisi query SQL untuk:
- Cek data user dan role
- Update role user
- Cek data siswa
- Cek kelas dan tahun ajaran
- Buat user admin/kesiswaan baru

Cara menggunakan:
1. Buka phpMyAdmin atau MySQL client
2. Pilih database `sistem-kesiswaan`
3. Copy-paste query yang diperlukan dari file `fix_siswa_access.sql`
4. Jalankan query

## 🎓 Best Practices

1. **Selalu backup database** sebelum melakukan perubahan besar
2. **Gunakan user dengan role yang sesuai** untuk setiap operasi
3. **Jangan hapus siswa** yang memiliki data pelanggaran/prestasi
4. **Verifikasi email user** sebelum memberikan akses penuh
5. **Monitor log** secara berkala untuk mendeteksi masalah

## 📞 Bantuan Lebih Lanjut

Jika masih mengalami masalah:

1. **Cek dokumentasi lengkap**: `TROUBLESHOOTING_SISWA.md`
2. **Jalankan diagnostic**: `php artisan siswa:check-access`
3. **Cek log**: `storage/logs/laravel.log`
4. **Reset database** (HATI-HATI): `php artisan migrate:fresh --seed`

## ✨ Fitur Baru yang Ditambahkan

1. ✅ Logging lengkap untuk semua operasi
2. ✅ Alert success/error di setiap halaman
3. ✅ Middleware dengan pesan error yang jelas
4. ✅ Command artisan untuk troubleshooting
5. ✅ Debug endpoint untuk cek akses
6. ✅ Dokumentasi lengkap
7. ✅ SQL helper script
8. ✅ Error handling yang lebih baik

## 🔄 Update Terakhir

- Tanggal: 2024
- Versi: 1.0
- Status: ✅ FIXED - Semua fitur CRUD berfungsi normal
