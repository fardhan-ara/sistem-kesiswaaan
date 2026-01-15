# Perbaikan Error Monitoring Sanksi & Pemisahan Insights-Rekomendasi

## 📋 Ringkasan Perbaikan

Dokumen ini menjelaskan perbaikan error 500 pada monitoring sanksi dan pemisahan insights dengan rekomendasi manual.

## ✅ Masalah yang Diperbaiki

### 1. Error 500 pada Monitoring Sanksi
**Masalah:** Halaman monitoring sanksi error 500 karena query `having()` pada relasi.

**Penyebab:**
- Penggunaan `having()` pada query dengan `withCount()` menyebabkan error
- Seharusnya menggunakan `has()` untuk filter relasi

**Solusi:**
```php
// SEBELUM (ERROR)
$kasusEskalasi = Siswa::with('kelas')
    ->withCount(['sanksis'])
    ->having('sanksis_count', '>=', 2)  // ❌ ERROR
    ->orderBy('sanksis_count', 'desc')
    ->limit(10)
    ->get();

// SESUDAH (FIXED)
$kasusEskalasi = Siswa::with('kelas')
    ->withCount('sanksis')
    ->has('sanksis', '>=', 2)  // ✅ BENAR
    ->orderBy('sanksis_count', 'desc')
    ->limit(10)
    ->get();
```

**File yang Diubah:**
- `app/Http/Controllers/KepalaSekolahController.php` - Method `monitoringSanksi()`

### 2. Pemisahan Insights & Rekomendasi
**Masalah:** Insights dan rekomendasi digabung dalam satu card, tidak ada cara untuk input rekomendasi manual.

**Solusi:**
- **Insights**: Analisis otomatis dari sistem (tetap otomatis)
- **Rekomendasi**: Input manual oleh Kepala Sekolah dengan CRUD

**Fitur Baru:**
1. Tabel database `rekomendasi_executives`
2. Model `RekomendasiExecutive`
3. CRUD rekomendasi (Create, Read, Delete)
4. Modal untuk input rekomendasi
5. Tampilan terpisah untuk insights dan rekomendasi

## 🗄️ Database Schema

### Tabel: rekomendasi_executives
```sql
CREATE TABLE rekomendasi_executives (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    rekomendasi TEXT NOT NULL,
    periode VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Kolom:**
- `id`: Primary key
- `rekomendasi`: Isi rekomendasi (TEXT)
- `periode`: Periode rekomendasi, opsional (VARCHAR)
- `is_active`: Status aktif/tidak (BOOLEAN)
- `created_at`, `updated_at`: Timestamps

## 📁 File yang Dibuat/Diubah

### File Baru:
1. `database/migrations/2026_01_14_042158_create_rekomendasi_executives_table.php`
2. `app/Models/RekomendasiExecutive.php`

### File Diubah:
1. `app/Http/Controllers/KepalaSekolahController.php`
   - Method `monitoringSanksi()` - Fix error
   - Method `laporanExecutive()` - Tambah rekomendasi
   - Method `storeRekomendasi()` - BARU
   - Method `deleteRekomendasi()` - BARU

2. `resources/views/kepala-sekolah/laporan-executive.blade.php`
   - Pisahkan card Insights dan Rekomendasi
   - Tambah modal input rekomendasi
   - Tambah tombol hapus rekomendasi

3. `routes/web.php`
   - Route `POST /kepala-sekolah/rekomendasi`
   - Route `DELETE /kepala-sekolah/rekomendasi/{id}`

## 🚀 Cara Menggunakan

### Setup Database (PENTING!)

Jalankan migration untuk membuat tabel:
```bash
php artisan migrate
```

### Untuk Kepala Sekolah

#### 1. Melihat Laporan Executive
- Login sebagai Kepala Sekolah
- Akses menu "Laporan Executive"
- Pilih periode (hari ini, minggu ini, bulan ini, tahun ini)

#### 2. Melihat Insights (Otomatis)
- Insights muncul otomatis berdasarkan analisis sistem
- Menampilkan:
  - Efektivitas sanksi
  - Tingkat disiplin
  - Rasio prestasi
  - Trend pelanggaran

#### 3. Menambah Rekomendasi (Manual)
- Klik tombol "Tambah Rekomendasi"
- Isi form:
  - **Rekomendasi** (wajib): Isi rekomendasi
  - **Periode** (opsional): Contoh "Semester 1 2024"
- Klik "Simpan"

#### 4. Menghapus Rekomendasi
- Klik tombol trash (🗑️) di samping rekomendasi
- Konfirmasi penghapusan

### Monitoring Sanksi (Sudah Diperbaiki)
- Akses menu "Monitoring Sanksi"
- Filter berdasarkan status dan kelas
- Lihat kasus eskalasi (siswa dengan sanksi berulang)
- Tidak ada error lagi!

## 💻 Kode Penting

### Controller - Store Rekomendasi
```php
public function storeRekomendasi(Request $request)
{
    $request->validate([
        'rekomendasi' => 'required|string',
        'periode' => 'nullable|string'
    ]);
    
    RekomendasiExecutive::create([
        'rekomendasi' => $request->rekomendasi,
        'periode' => $request->periode,
        'is_active' => true
    ]);
    
    return redirect()->back()->with('success', 'Rekomendasi berhasil ditambahkan');
}
```

### Controller - Delete Rekomendasi
```php
public function deleteRekomendasi($id)
{
    RekomendasiExecutive::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Rekomendasi berhasil dihapus');
}
```

### View - Modal Input Rekomendasi
```blade
<div class="modal fade" id="modalRekomendasi">
    <div class="modal-dialog">
        <form action="{{ route('kepala-sekolah.rekomendasi.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <textarea name="rekomendasi" required></textarea>
                <input type="text" name="periode" placeholder="Semester 1 2024">
            </div>
            <button type="submit">Simpan</button>
        </form>
    </div>
</div>
```

## 🎨 Tampilan

### Sebelum:
```
┌─────────────────────────────────────┐
│ Insights & Rekomendasi              │
│ • Insight 1                         │
│ • Insight 2                         │
└─────────────────────────────────────┘
```

### Sesudah:
```
┌─────────────────────────────────────┐
│ Insights (Analisis Otomatis)        │
│ • Efektivitas sanksi baik (85%)     │
│ • Tingkat disiplin sangat baik      │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│ Rekomendasi (Input Manual) [+ Tambah]│
│ • Tingkatkan program pembinaan      │
│   [Semester 1 2024] [🗑️]            │
│ • Adakan workshop untuk guru        │
│   [🗑️]                               │
└─────────────────────────────────────┘
```

## 🔧 Testing

### Test Monitoring Sanksi
```bash
# Akses halaman
http://localhost:8000/kepala-sekolah/monitoring-sanksi

# Cek tidak ada error
✅ Halaman load tanpa error 500
✅ Tabel sanksi tampil
✅ Kasus eskalasi tampil dengan nama kelas
✅ Filter berfungsi
```

### Test Laporan Executive
```bash
# Akses halaman
http://localhost:8000/kepala-sekolah/laporan-executive

# Test Insights
✅ Card insights terpisah
✅ Analisis otomatis muncul

# Test Rekomendasi
✅ Card rekomendasi terpisah
✅ Tombol "Tambah Rekomendasi" ada
✅ Modal muncul saat klik tombol
✅ Form bisa diisi dan disimpan
✅ Rekomendasi muncul di list
✅ Tombol hapus berfungsi
```

## 📝 Catatan Penting

### 1. Migration Wajib Dijalankan
Sebelum menggunakan fitur rekomendasi, WAJIB jalankan:
```bash
php artisan migrate
```

### 2. Perbedaan Insights vs Rekomendasi

**Insights (Otomatis):**
- Dihasilkan oleh sistem
- Berdasarkan data real-time
- Tidak bisa diedit manual
- Berubah sesuai kondisi data

**Rekomendasi (Manual):**
- Input oleh Kepala Sekolah
- Bisa ditambah/hapus kapan saja
- Tersimpan di database
- Bisa diberi label periode

### 3. Validasi
- Rekomendasi wajib diisi (required)
- Periode opsional
- Rekomendasi aktif secara default

### 4. Keamanan
- Hanya Kepala Sekolah yang bisa akses
- Middleware `role:kepala_sekolah`
- CSRF protection aktif

## 🔍 Troubleshooting

### Error: Table rekomendasi_executives doesn't exist
**Solusi:**
```bash
php artisan migrate
```

### Error 500 masih muncul di monitoring sanksi
**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cek log
tail -f storage/logs/laravel.log
```

### Modal tidak muncul
**Solusi:**
- Pastikan jQuery dan Bootstrap JS loaded
- Cek console browser untuk error
- Pastikan `data-toggle="modal"` dan `data-target="#modalRekomendasi"` benar

### Rekomendasi tidak tersimpan
**Solusi:**
- Cek validasi form
- Cek CSRF token
- Cek log error di `storage/logs/laravel.log`

## ✨ Kesimpulan

Perbaikan ini meningkatkan:
- **Stabilitas**: Error 500 monitoring sanksi sudah diperbaiki
- **Fleksibilitas**: Kepala Sekolah bisa input rekomendasi manual
- **Kejelasan**: Insights dan rekomendasi terpisah dengan jelas
- **Usability**: Interface yang lebih user-friendly dengan modal

---

**Tanggal Perbaikan:** 14 Januari 2026
**Versi:** 1.1
**Status:** ✅ Ready (Perlu Migration)
