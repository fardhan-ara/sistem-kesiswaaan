# INTEGRASI DATA ORANG TUA - SISWA

## 📋 Overview

Sistem telah diintegrasikan penuh untuk role **Orang Tua** agar dapat mengakses semua data anak (siswa) mereka secara real-time.

## ✅ Fitur yang Telah Diintegrasikan

### 1. **Dashboard Orang Tua**
- Menampilkan profil anak (NIS, nama, kelas, tahun ajaran)
- Statistik lengkap: Total Pelanggaran, Prestasi, Poin, Sanksi Aktif
- Status poin dengan kategori (Baik, Sedang, Berat, Sangat Berat)
- Riwayat pelanggaran terbaru (5 terakhir)
- Riwayat prestasi terbaru (5 terakhir)
- Daftar sanksi yang sedang berjalan
- Dokumen orang tua (KK)

### 2. **Menu Data Anak**
Orang tua dapat mengakses:

#### a. **Profil Anak** (`/ortu/profil-anak`)
- Data lengkap siswa (NIS, nama, kelas, jenis kelamin, alamat)
- Statistik: pelanggaran, prestasi, poin, sanksi aktif
- Data orang tua (nama ayah, ibu, telepon, alamat)

#### b. **Pelanggaran** (`/ortu/pelanggaran`)
- Daftar semua pelanggaran anak dengan pagination
- Detail: tanggal, jenis pelanggaran, poin, keterangan, pencatat, status
- Total poin pelanggaran

#### c. **Prestasi** (`/ortu/prestasi`)
- Daftar semua prestasi anak dengan pagination
- Detail: tanggal, jenis prestasi, tingkat, poin, keterangan, pencatat

#### d. **Sanksi** (`/ortu/sanksi`)
- Daftar semua sanksi anak dengan pagination
- Detail: pelanggaran, jenis sanksi, tanggal mulai/selesai, status

#### e. **Bimbingan Konseling** (`/ortu/bimbingan`)
- Daftar semua sesi BK anak dengan pagination
- Detail: tanggal, permasalahan, solusi, tindak lanjut, konselor

### 3. **Komunikasi Sekolah**
- Akses ke fitur komunikasi dengan guru/wali kelas/BK
- Melihat panggilan orang tua
- Reply pesan dari sekolah

## 🔐 Sistem Keamanan

### Validasi Akses
- Hanya orang tua dengan biodata **approved** yang dapat mengakses data anak
- Setiap query difilter berdasarkan `siswa_id` dari biodata orang tua
- Tidak ada akses ke data siswa lain

### Status Biodata
1. **Pending**: Menampilkan pesan "Biodata sedang ditinjau"
2. **Rejected**: Menampilkan pesan "Biodata ditolak" dengan alasan
3. **Approved**: Full akses ke semua data anak

## 📁 File yang Dibuat/Dimodifikasi

### Controllers
- ✅ `app/Http/Controllers/OrtuController.php` (BARU)
- ✅ `app/Http/Controllers/DashboardController.php` (UPDATE)

### Views
- ✅ `resources/views/ortu/pelanggaran.blade.php` (BARU)
- ✅ `resources/views/ortu/prestasi.blade.php` (BARU)
- ✅ `resources/views/ortu/sanksi.blade.php` (BARU)
- ✅ `resources/views/ortu/bimbingan.blade.php` (BARU)
- ✅ `resources/views/ortu/profil.blade.php` (BARU)
- ✅ `resources/views/layouts/app.blade.php` (UPDATE - sidebar menu)
- ✅ `resources/views/dashboard/ortu.blade.php` (SUDAH ADA)

### Routes
- ✅ `routes/web.php` (UPDATE - tambah route ortu)

## 🎯 Cara Menggunakan

### Untuk Orang Tua:
1. Login dengan akun orang tua
2. Lengkapi biodata (jika belum)
3. Tunggu approval dari admin
4. Setelah approved, akses menu:
   - Dashboard: Lihat ringkasan data anak
   - Profil Anak: Data lengkap siswa
   - Pelanggaran: Riwayat pelanggaran
   - Prestasi: Riwayat prestasi
   - Sanksi: Daftar sanksi
   - Bimbingan Konseling: Sesi BK
   - Komunikasi Sekolah: Pesan dari sekolah

### Untuk Admin/Kesiswaan:
1. Approve biodata orang tua di menu "Biodata Orang Tua"
2. Pastikan `siswa_id` terisi dengan benar
3. Setelah approved, orang tua otomatis dapat akses data anak

## 🔄 Sinkronisasi Data

### Real-time Sync
Semua data yang ditampilkan ke orang tua adalah **real-time** dari database:
- Pelanggaran: Hanya yang sudah diverifikasi/terverifikasi
- Prestasi: Hanya yang sudah verified
- Sanksi: Semua status (aktif, berjalan, selesai)
- Bimbingan: Semua sesi BK

### Filter Data
- Pelanggaran: `status_verifikasi IN ('diverifikasi', 'terverifikasi')`
- Prestasi: `status_verifikasi = 'verified'`
- Sanksi: Semua data
- Bimbingan: Semua data

## 📊 Menu Sidebar Orang Tua

```
Dashboard
├── DATA ANAK
│   ├── Profil Anak
│   ├── Pelanggaran
│   ├── Prestasi
│   ├── Sanksi
│   └── Bimbingan Konseling
└── KOMUNIKASI
    └── Komunikasi Sekolah
```

## 🚀 Testing

### Test Akses Orang Tua:
```bash
# 1. Login sebagai orang tua
# 2. Cek dashboard: http://localhost:8000/dashboard
# 3. Cek profil anak: http://localhost:8000/ortu/profil-anak
# 4. Cek pelanggaran: http://localhost:8000/ortu/pelanggaran
# 5. Cek prestasi: http://localhost:8000/ortu/prestasi
# 6. Cek sanksi: http://localhost:8000/ortu/sanksi
# 7. Cek bimbingan: http://localhost:8000/ortu/bimbingan
```

### Test Keamanan:
```bash
# 1. Login sebagai orang tua dengan biodata pending
#    → Harus muncul pesan "Biodata sedang ditinjau"
# 2. Login sebagai orang tua dengan biodata rejected
#    → Harus muncul pesan "Biodata ditolak"
# 3. Login sebagai orang tua tanpa biodata
#    → Harus muncul pesan "Silakan lengkapi biodata"
```

## ⚠️ Troubleshooting

### Orang tua tidak bisa akses data anak?
```bash
# Cek status biodata
SELECT * FROM biodata_ortus WHERE user_id = [USER_ID];

# Pastikan:
# 1. status_approval = 'approved'
# 2. siswa_id terisi dengan benar
# 3. siswa_id ada di tabel siswas
```

### Data tidak muncul?
```bash
# Clear cache
php artisan optimize:clear

# Cek relasi biodata
SELECT bo.*, s.nama_siswa 
FROM biodata_ortus bo
LEFT JOIN siswas s ON bo.siswa_id = s.id
WHERE bo.user_id = [USER_ID];
```

### Error 403 Forbidden?
```bash
# Pastikan middleware role:ortu aktif di routes/web.php
# Cek role user
SELECT id, nama, email, role FROM users WHERE role = 'ortu';
```

## 📝 Catatan Penting

1. **Biodata harus approved** sebelum orang tua dapat akses data anak
2. **Satu orang tua = satu anak** (relasi one-to-one via biodata_ortus)
3. **Data real-time** langsung dari database tanpa cache
4. **Keamanan ketat** dengan filter siswa_id di setiap query
5. **Pagination** untuk performa optimal (10 data per halaman)

## ✨ Fitur Tambahan

- Badge status untuk pelanggaran/prestasi/sanksi
- Responsive table untuk mobile
- Alert kategori poin (Baik/Sedang/Berat/Sangat Berat)
- Pagination untuk semua list data
- Icon yang jelas untuk setiap menu

---

**Status**: ✅ SELESAI & SIAP DIGUNAKAN
**Tanggal**: 2025-01-14
**Versi**: 1.0.0
