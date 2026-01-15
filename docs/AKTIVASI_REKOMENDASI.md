# Aktivasi Fitur Rekomendasi Manual - Laporan Executive

## 🚀 Cara Mengaktifkan

### Langkah 1: Buat Tabel Database

Buka **phpMyAdmin** atau MySQL client, pilih database `sistem-kesiswaan`, lalu jalankan SQL berikut:

```sql
CREATE TABLE IF NOT EXISTS `rekomendasi_executives` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rekomendasi` text NOT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Langkah 2: (Opsional) Insert Data Contoh

```sql
INSERT INTO `rekomendasi_executives` (`rekomendasi`, `periode`, `is_active`, `created_at`, `updated_at`) VALUES
('Tingkatkan program pembinaan karakter siswa melalui kegiatan ekstrakurikuler', 'Semester 1 2024/2025', 1, NOW(), NOW()),
('Adakan workshop untuk guru tentang metode pembelajaran modern', 'Semester 1 2024/2025', 1, NOW(), NOW());
```

### Langkah 3: Refresh Browser

Akses: `http://localhost:8000/kepala-sekolah/laporan-executive`

## ✨ Fitur yang Tersedia

### 1. Tambah Rekomendasi
- Klik tombol **"Tambah Rekomendasi"**
- Isi form rekomendasi
- Isi periode (opsional)
- Klik **"Simpan"**

### 2. Lihat Rekomendasi
- Semua rekomendasi aktif akan ditampilkan di card "Rekomendasi (Input Manual)"
- Rekomendasi dengan periode akan menampilkan badge

### 3. Hapus Rekomendasi
- Klik tombol trash (🗑️) di samping rekomendasi
- Konfirmasi penghapusan

## 📋 Struktur Tabel

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| rekomendasi | text | Isi rekomendasi (wajib) |
| periode | varchar(255) | Periode rekomendasi (opsional) |
| is_active | tinyint(1) | Status aktif (1=aktif, 0=nonaktif) |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## 🔧 Troubleshooting

### Error: Table doesn't exist
**Solusi:** Jalankan SQL di Langkah 1

### Modal tidak muncul
**Solusi:** 
- Pastikan jQuery dan Bootstrap JS loaded
- Clear browser cache (Ctrl+Shift+Delete)

### Rekomendasi tidak tersimpan
**Solusi:**
- Cek tabel sudah dibuat
- Cek CSRF token
- Lihat log: `storage/logs/laravel.log`

## 📝 Catatan

- Fitur ini menggunakan DB Query Builder untuk kompatibilitas maksimal
- Error handling sudah ditambahkan untuk mencegah crash
- Jika tabel belum dibuat, sistem akan menampilkan pesan error yang informatif

---

**Status:** ✅ Ready to Use (Setelah SQL dijalankan)
**Tanggal:** 14 Januari 2026
