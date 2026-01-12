# Troubleshooting Form Pelanggaran & Prestasi

## Masalah yang Sudah Diperbaiki

### 1. Error Kolom `poin` di Prestasi
**Masalah**: PrestasiController menggunakan `$jenisPrestasi->poin` yang tidak ada
**Solusi**: Diganti menjadi `$jenisPrestasi->poin_reward`
**File**: `app/Http/Controllers/PrestasiController.php` line 77 dan 130

### 2. Data Requirement
**Diperlukan**:
- ✅ Siswa: 41 data tersedia
- ✅ Guru: 4 data tersedia  
- ✅ Jenis Pelanggaran: 60 data tersedia
- ✅ Jenis Prestasi: 66 data tersedia

## Cara Mengakses Form

### Form Pelanggaran
**URL**: `/pelanggaran/create`
**Route**: `pelanggaran.create`
**Method**: GET
**Role Access**: admin, kesiswaan, guru, wali_kelas

### Form Prestasi
**URL**: `/prestasi/create`
**Route**: `prestasi.create`
**Method**: GET
**Role Access**: admin, kesiswaan, guru, wali_kelas

## Field yang Diperlukan

### Form Pelanggaran
```php
- siswa_id: required|exists:siswas,id
- guru_pencatat: required|exists:gurus,id
- jenis_pelanggaran_id: required|exists:jenis_pelanggarans,id
- tanggal_pelanggaran: nullable|date (default: today)
- keterangan: nullable|string
```

### Form Prestasi
```php
- siswa_id: required|exists:siswas,id
- guru_pencatat: required|exists:gurus,id
- jenis_prestasi_id: required|exists:jenis_prestasis,id
- tanggal_prestasi: nullable|date (default: today)
- keterangan: nullable|string
```

## Auto-Fill Data

### Pelanggaran
- `tahun_ajaran_id`: Auto dari siswa
- `poin`: Auto dari jenis_pelanggaran
- `status_verifikasi`: 'menunggu'
- `tanggal_pelanggaran`: Default today

### Prestasi
- `tahun_ajaran_id`: Auto dari siswa
- `poin`: Auto dari jenis_prestasi.poin_reward
- `status_verifikasi`: 'pending'
- `tanggal_prestasi`: Default today

## Jika Form Tidak Muncul

### 1. Clear Cache
```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### 2. Cek Data
```bash
php artisan tinker
>>> App\Models\Siswa::count()
>>> App\Models\Guru::count()
>>> App\Models\JenisPelanggaran::count()
>>> App\Models\JenisPrestasi::count()
```

### 3. Cek Log
```bash
tail -f storage/logs/laravel.log
```

### 4. Test Controller
```bash
php artisan tinker
>>> $controller = new App\Http\Controllers\PelanggaranController();
>>> $controller->create();
```

## Status Verifikasi

### Pelanggaran
- `menunggu`: Baru dibuat, belum diverifikasi
- `terverifikasi` / `diverifikasi`: Sudah disetujui
- `ditolak`: Ditolak oleh admin/kesiswaan

### Prestasi
- `pending`: Baru dibuat, belum diverifikasi
- `verified`: Sudah disetujui
- `rejected` / `ditolak`: Ditolak oleh admin/kesiswaan

## Auto Sanksi (Pelanggaran)

Sistem akan otomatis membuat sanksi jika:
- Total poin >= 31: Sanksi Berat (7 hari)
- Total poin >= 76: Sanksi Sangat Berat (14 hari)

## Kolom Database

### Tabel: pelanggarans
- siswa_id, guru_pencatat, jenis_pelanggaran_id
- tahun_ajaran_id, poin, tanggal_pelanggaran
- status_verifikasi, keterangan
- guru_verifikator, tanggal_verifikasi, alasan_penolakan

### Tabel: prestasis
- siswa_id, guru_pencatat, jenis_prestasi_id
- tahun_ajaran_id, poin, tanggal_prestasi
- status_verifikasi, keterangan
- guru_verifikator, tanggal_verifikasi

## Status: ✅ FIXED

Semua masalah sudah diperbaiki:
- ✅ Kolom poin_reward sudah benar
- ✅ Controller sudah sinkron dengan database
- ✅ Data master sudah lengkap
- ✅ Form siap digunakan
