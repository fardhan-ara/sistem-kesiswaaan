# Perbaikan Menu Tahun Ajaran

## Perubahan yang Dilakukan

### 1. Database Migration
**File**: `database/migrations/2024_01_01_000000_create_tahun_ajarans_table.php`

Struktur tabel diperbaiki menjadi:
- `id` (Primary Key)
- `tahun_ajaran` (string) - Format: 2024/2025
- `tahun_mulai` (year) - Tahun mulai
- `tahun_selesai` (year) - Tahun selesai
- `semester` (enum: ganjil, genap)
- `status_aktif` (enum: aktif, nonaktif)
- `timestamps`

### 2. Model
**File**: `app/Models/TahunAjaran.php`

- Field fillable disesuaikan: `tahun_ajaran`, `tahun_mulai`, `tahun_selesai`, `semester`, `status_aktif`
- Ditambahkan relasi: `pelanggarans()`, `prestasis()`

### 3. Controller
**File**: `app/Http/Controllers/TahunAjaranController.php`

- Validasi diperbaiki sesuai field baru
- Logic untuk memastikan hanya 1 tahun ajaran aktif
- Range tahun: 2020-2050

### 4. Views
**File**: `resources/views/tahun-ajaran/`

#### index.blade.php
- Kolom tabel disesuaikan: No, Tahun Ajaran, Periode, Semester, Status, Aksi
- Status badge: Aktif (hijau), Nonaktif (abu-abu)

#### create.blade.php
- Form input: Tahun Ajaran, Tahun Mulai, Tahun Selesai, Semester, Status
- Validasi error handling
- Helper text untuk format input

#### edit.blade.php (BARU)
- Form edit dengan pre-filled data
- Validasi error handling
- Konsisten dengan form create

### 5. Seeder
**File**: `database/seeders/TahunAjaranSeeder.php` (BARU)

Data sample:
- 2023/2024 (Nonaktif)
- 2024/2025 (Aktif)
- 2025/2026 (Nonaktif)

## Cara Menjalankan

### 1. Reset Database (HATI-HATI: Akan menghapus semua data)
```bash
php artisan migrate:fresh --seed
```

### 2. Atau Migrate Ulang Tabel Tahun Ajaran Saja
```bash
php artisan migrate:refresh --path=database/migrations/2024_01_01_000000_create_tahun_ajarans_table.php
php artisan db:seed --class=TahunAjaranSeeder
```

## Fitur yang Sudah Diperbaiki

✅ Struktur database konsisten dengan modul
✅ Field naming yang jelas dan standar
✅ Validasi input yang ketat
✅ Hanya 1 tahun ajaran yang bisa aktif
✅ Error handling yang baik
✅ UI/UX yang lebih baik dengan badge dan icon
✅ Seeder untuk data sample
✅ Relasi dengan tabel lain (siswa, pelanggaran, prestasi)

## Catatan Penting

- Pastikan field `tahun_ajaran` menggunakan format: YYYY/YYYY (contoh: 2024/2025)
- Tahun selesai harus lebih besar dari tahun mulai
- Sistem otomatis menonaktifkan tahun ajaran lain ketika ada yang diaktifkan
- Range tahun: 2020-2050 (bisa disesuaikan di controller)

## Sesuai dengan Modul

Perbaikan ini mengikuti struktur database yang dijelaskan di:
- **Modul 1**: Struktur Tabel Database (Halaman 11)
- **Modul 2**: Ruang Lingkup Sistem - Manajemen Data Master (Halaman 7)

Field `tahun_ajaran` di tabel ini akan digunakan sebagai Foreign Key di tabel:
- `siswas`
- `pelanggarans`
- `prestasis`
