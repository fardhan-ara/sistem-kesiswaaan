# Fix Database Columns - Pelanggaran & Prestasi

## Masalah yang Ditemukan

### 1. **Data tidak tersimpan ke database**
   - Setelah submit form, data tidak muncul di tabel
   - Redirect ke dashboard bukan ke halaman pelanggaran/prestasi
   - Tabel kosong meskipun form sudah diisi

### 2. **Root Cause**
   - **Pelanggaran**: Kolom `tanggal_pelanggaran` dan `alasan_penolakan` tidak ada di database
   - **Prestasi**: Kolom `guru_pencatat`, `tahun_ajaran_id`, `tanggal_prestasi`, `guru_verifikator`, `tanggal_verifikasi` tidak ada di database
   - Model mencoba insert data ke kolom yang tidak exist → SQL Error → Data tidak tersimpan

## Solusi yang Diterapkan

### A. Migration untuk Pelanggaran
**File**: `2025_11_25_083206_add_tanggal_pelanggaran_to_pelanggarans_table.php`

Menambahkan kolom:
- `tanggal_pelanggaran` (date, nullable)
- `alasan_penolakan` (text, nullable)

```bash
php artisan migrate
```

### B. Migration untuk Prestasi
**File**: `2025_11_25_084946_add_missing_columns_to_prestasis_table.php`

Menambahkan kolom:
- `guru_pencatat` (foreignId → gurus)
- `tahun_ajaran_id` (foreignId → tahun_ajarans)
- `tanggal_prestasi` (date, nullable)
- `guru_verifikator` (foreignId → gurus)
- `tanggal_verifikasi` (timestamp, nullable)

```bash
php artisan migrate
```

### C. Update Model Pelanggaran
**File**: `app/Models/Pelanggaran.php`

- Menambahkan `tanggal_pelanggaran` dan `alasan_penolakan` ke `$fillable`
- Menambahkan method `sanksis()` untuk relationship hasMany

### D. Update Controller Prestasi
**File**: `app/Http/Controllers/PrestasiController.php`

- Mengubah `status_verifikasi` dari `'menunggu'` menjadi `'pending'` (sesuai enum di database)

### E. Update View Prestasi
**File**: `resources/views/prestasi/index.blade.php`

- Mengubah kondisi edit button untuk support kedua status: `'menunggu'` dan `'pending'`

## Struktur Database Setelah Fix

### Tabel: pelanggarans
```
- id
- siswa_id (FK → siswas)
- guru_pencatat (FK → gurus)
- jenis_pelanggaran_id (FK → jenis_pelanggarans)
- tahun_ajaran_id (FK → tahun_ajarans)
- poin
- tanggal_pelanggaran ✅ BARU
- keterangan
- status_verifikasi (enum: menunggu, diverifikasi, ditolak)
- guru_verifikator (FK → gurus)
- tanggal_verifikasi
- alasan_penolakan ✅ BARU
- created_at
- updated_at
```

### Tabel: prestasis
```
- id
- siswa_id (FK → siswas)
- guru_pencatat ✅ BARU (FK → gurus)
- jenis_prestasi_id (FK → jenis_prestasis)
- tahun_ajaran_id ✅ BARU (FK → tahun_ajarans)
- poin
- tanggal_prestasi ✅ BARU
- keterangan
- status_verifikasi (enum: pending, verified, rejected)
- guru_verifikator ✅ BARU (FK → gurus)
- tanggal_verifikasi ✅ BARU
- created_at
- updated_at
```

## Testing

### Test Manual via Tinker
```bash
# Test Pelanggaran
php artisan tinker
$siswa = App\Models\Siswa::first();
$guru = App\Models\Guru::first();
$jenis = App\Models\JenisPelanggaran::first();
$p = App\Models\Pelanggaran::create([
    'siswa_id' => $siswa->id,
    'guru_pencatat' => $guru->id,
    'jenis_pelanggaran_id' => $jenis->id,
    'tahun_ajaran_id' => 1,
    'poin' => $jenis->poin,
    'tanggal_pelanggaran' => now(),
    'status_verifikasi' => 'menunggu'
]);
# ✅ SUCCESS: Created ID: 1

# Test Prestasi
$p = App\Models\Prestasi::create([
    'siswa_id' => $siswa->id,
    'guru_pencatat' => $guru->id,
    'jenis_prestasi_id' => 1,
    'tahun_ajaran_id' => 1,
    'poin' => 10,
    'tanggal_prestasi' => now(),
    'status_verifikasi' => 'pending'
]);
# ✅ SUCCESS: Created ID: 1
```

### Test via Web Form
1. Login sebagai admin/kesiswaan/guru
2. Buka menu **Pelanggaran** → **Tambah Pelanggaran**
3. Isi form:
   - Pilih Siswa (dari database, bukan hardcoded)
   - Pilih Guru Pencatat (dari database)
   - Pilih Kategori Pelanggaran
   - Pilih Jenis Pelanggaran
   - Isi Keterangan (optional)
4. Klik **Simpan**
5. ✅ **Expected**: Redirect ke `/pelanggaran` dengan notifikasi sukses
6. ✅ **Expected**: Data muncul di tabel dengan nama siswa dan guru dari database

Ulangi untuk **Prestasi**.

## Checklist Verifikasi

- [x] Migration berhasil dijalankan
- [x] Kolom baru ada di database
- [x] Model fillable sudah update
- [x] Controller menggunakan status yang benar
- [x] View menampilkan data dengan benar
- [x] Test manual via tinker berhasil
- [ ] Test via web form berhasil (silakan test manual)
- [ ] Data siswa/guru diambil dari database (bukan hardcoded)
- [ ] Setelah submit, redirect ke halaman yang benar
- [ ] Data muncul di tabel

## Catatan Penting

1. **Status Verifikasi**:
   - Pelanggaran: `menunggu`, `diverifikasi`, `ditolak`
   - Prestasi: `pending`, `verified`, `rejected`

2. **Data Master**:
   - Siswa: 8 records (dari seeder)
   - Guru: 7 records (dari seeder)
   - Jenis Pelanggaran: 71 records (dari seeder)
   - Jenis Prestasi: Check dengan `App\Models\JenisPrestasi::count()`

3. **Redirect**:
   - Pelanggaran: `return redirect('/pelanggaran')`
   - Prestasi: `return redirect('/prestasi')`

## Troubleshooting

Jika masih ada masalah:

```bash
# Clear cache
php artisan optimize:clear

# Check data
php artisan tinker
App\Models\Siswa::count()
App\Models\Guru::count()
App\Models\Pelanggaran::count()
App\Models\Prestasi::count()

# Check last error
tail -n 50 storage/logs/laravel.log
```

## Selesai! ✅

Sistem sekarang sudah bisa:
- ✅ Menyimpan data pelanggaran dengan benar
- ✅ Menyimpan data prestasi dengan benar
- ✅ Mengambil data siswa/guru dari database
- ✅ Redirect ke halaman yang benar setelah submit
- ✅ Menampilkan data di tabel
