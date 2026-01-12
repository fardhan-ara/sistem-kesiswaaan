# Instruksi Perbaikan Error Database

## Error
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'prestasis.poin' in 'field list'
```

## Penyebab
Tabel `prestasis` tidak memiliki kolom `poin` yang dibutuhkan oleh query di DashboardController.

## Solusi

### Opsi 1: Jalankan Migration (Recommended)
```bash
php artisan migrate
```

### Opsi 2: Manual SQL (jika migration gagal)
Buka phpMyAdmin atau MySQL client, lalu jalankan:
```sql
-- Add poin column to prestasis table
ALTER TABLE `prestasis` ADD COLUMN `poin` INT NOT NULL DEFAULT 0 AFTER `jenis_prestasi_id`;

-- Update existing data to copy poin from jenis_prestasis
UPDATE prestasis p
JOIN jenis_prestasis jp ON p.jenis_prestasi_id = jp.id
SET p.poin = jp.poin_reward;
```

### Opsi 3: Gunakan Batch File
Double-click file `run_migrate.bat` di folder root project.

## Perubahan Yang Dilakukan

1. **Migration File**: Ditambahkan kolom `poin` di `2024_01_01_000010_create_prestasis_table.php`
2. **New Migration**: Dibuat `2025_11_19_100000_add_poin_to_prestasis_table.php` untuk menambah kolom
3. **DashboardController**: Diperbaiki status verifikasi dari 'terverifikasi' menjadi 'verified'
4. **Model Prestasi**: Sudah include 'poin' di fillable

## Verifikasi
Setelah menjalankan migration, cek struktur tabel:
```sql
DESCRIBE prestasis;
```

Kolom `poin` harus muncul dengan tipe INT.

## Status Verifikasi Yang Benar
- Prestasi: 'pending', 'verified', 'rejected'
- Pelanggaran: 'menunggu', 'verified', 'rejected'

Gunakan 'verified' bukan 'terverifikasi' untuk prestasi.
