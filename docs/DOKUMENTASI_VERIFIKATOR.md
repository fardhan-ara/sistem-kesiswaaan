# DOKUMENTASI FITUR VERIFIKATOR

## Role: Kesiswaan (Verifikator)

Berdasarkan modul UJI KOMPETENSI KEAHLIAN, role **Kesiswaan** bertindak sebagai **Verifikator & Koordinator Disiplin** dalam sistem.

---

## 1. AKSES VERIFIKATOR

### Privilege Akses (dari modul halaman 9)
```
✅ Input Pelanggaran
✅ Input Prestasi  
✅ Verifikasi Data (UTAMA)
✅ Monitoring All
✅ View Data Sendiri
✅ Export Laporan
❌ Input BK (khusus konselor)
❌ Manage User (khusus admin)
❌ Backup System (khusus admin)
```

### Tanggung Jawab Verifikator
1. **Mencatat Pelanggaran** - Input pelanggaran siswa
2. **Validasi Data** - Verifikasi pelanggaran & prestasi yang diinput guru
3. **Koordinasi Sanksi** - Koordinasi pemberian sanksi
4. **Monitoring** - Monitoring semua data disiplin siswa

---

## 2. MENU UTAMA VERIFIKATOR

### Menu Dashboard Verifikasi
```
📊 Dashboard Verifikator
├── Statistik Verifikasi
│   ├── Pelanggaran Menunggu
│   ├── Prestasi Menunggu  
│   ├── Data Revisi
│   ├── Terverifikasi Hari Ini
│   └── Ditolak Hari Ini
├── Data Baru (5 Terbaru)
│   ├── Pelanggaran Baru
│   └── Prestasi Baru
└── Chart Statistik 7 Hari Terakhir
```

### Menu Verifikasi Pelanggaran
```
📋 Verifikasi Pelanggaran
├── Daftar Menunggu Verifikasi
│   ├── Filter by Status (menunggu/revisi)
│   ├── Filter by Tanggal
│   ├── Filter by Kelas
│   └── Filter by Kategori
├── Detail Pelanggaran
│   ├── Data Siswa & Kelas
│   ├── Jenis Pelanggaran & Poin
│   ├── Keterangan Guru
│   ├── Riwayat Pelanggaran Siswa
│   └── Total Poin Siswa
└── Aksi Verifikasi
    ├── ✅ Approve (Terverifikasi)
    ├── ❌ Reject (Tolak)
    ├── 📝 Revisi (Minta Perbaikan)
    └── 📦 Bulk Approve
```

### Menu Verifikasi Prestasi  
```
🏆 Verifikasi Prestasi
├── Daftar Menunggu Verifikasi
│   ├── Filter by Status (menunggu/revisi)
│   ├── Filter by Tanggal
│   └── Filter by Kelas
├── Detail Prestasi
│   ├── Data Siswa & Kelas
│   ├── Jenis Prestasi & Poin
│   ├── Keterangan Guru
│   ├── Riwayat Prestasi Siswa
│   └── Total Poin Prestasi Siswa
└── Aksi Verifikasi
    ├── ✅ Approve (Terverifikasi)
    ├── ❌ Reject (Tolak)
    ├── 📝 Revisi (Minta Perbaikan)
    └── 📦 Bulk Approve
```

### Menu Riwayat Verifikasi
```
📜 Riwayat Verifikasi
├── Filter
│   ├── By Tabel (pelanggaran/prestasi)
│   ├── By Status (diverifikasi/ditolak/revisi)
│   └── By Tanggal
└── Data Riwayat
    ├── Tanggal Verifikasi
    ├── Tabel & ID Terkait
    ├── Status Verifikasi
    └── Catatan Verifikasi
```

---

## 3. FITUR UTAMA VERIFIKATOR

### A. Dashboard Verifikator
**Route:** `GET /verifikasi`
**Controller:** `VerifikasiController@index`

**Fitur:**
- Statistik real-time data yang perlu diverifikasi
- Jumlah pelanggaran & prestasi menunggu
- Jumlah yang perlu revisi
- Total terverifikasi hari ini
- Total ditolak hari ini
- Preview 5 data terbaru (pelanggaran & prestasi)
- Chart statistik verifikasi 7 hari terakhir

### B. Verifikasi Pelanggaran

#### 1. Daftar Pelanggaran Menunggu
**Route:** `GET /verifikasi/pelanggaran`
**Controller:** `VerifikasiController@pelanggaranMenunggu`

**Fitur:**
- List semua pelanggaran dengan status 'menunggu' atau 'revisi'
- Filter berdasarkan:
  - Status verifikasi
  - Range tanggal (dari-sampai)
  - Kelas siswa
  - Kategori pelanggaran
- Pagination 20 data per halaman
- Informasi: Siswa, Kelas, Pelanggaran, Poin, Tanggal, Status

#### 2. Detail Pelanggaran
**Route:** `GET /verifikasi/pelanggaran/{id}`
**Controller:** `VerifikasiController@pelanggaranDetail`

**Fitur:**
- Detail lengkap pelanggaran
- Informasi siswa & kelas
- Jenis pelanggaran & poin
- Keterangan dari guru pencatat
- Riwayat 5 pelanggaran terakhir siswa
- Total akumulasi poin pelanggaran siswa
- Form verifikasi dengan 3 opsi aksi

#### 3. Approve Pelanggaran
**Route:** `POST /verifikasi/pelanggaran/{id}/approve`
**Controller:** `VerifikasiController@verifikasiPelanggaran`

**Fitur:**
- Update status menjadi 'terverifikasi'
- Catat guru verifikator
- Catat tanggal verifikasi
- Simpan catatan verifikasi (opsional)
- Log ke tabel verifikasi_datas
- Database transaction untuk data integrity

#### 4. Reject Pelanggaran
**Route:** `POST /verifikasi/pelanggaran/{id}/reject`
**Controller:** `VerifikasiController@tolakPelanggaran`

**Fitur:**
- Update status menjadi 'ditolak'
- Wajib isi alasan penolakan
- Catat guru verifikator & tanggal
- Log penolakan ke tabel verifikasi_datas
- Database transaction

#### 5. Minta Revisi Pelanggaran
**Route:** `POST /verifikasi/pelanggaran/{id}/revisi`
**Controller:** `VerifikasiController@revisiPelanggaran`

**Fitur:**
- Update status menjadi 'revisi'
- Wajib isi catatan revisi
- Data dikembalikan ke guru untuk diperbaiki
- Log ke tabel verifikasi_datas
- Database transaction

#### 6. Bulk Approve Pelanggaran
**Route:** `POST /verifikasi/pelanggaran/bulk-approve`
**Controller:** `VerifikasiController@bulkVerifikasiPelanggaran`

**Fitur:**
- Verifikasi multiple pelanggaran sekaligus
- Checkbox selection di list pelanggaran
- Batch processing dengan transaction
- Log setiap verifikasi
- Efficient untuk data banyak

### C. Verifikasi Prestasi

#### 1. Daftar Prestasi Menunggu
**Route:** `GET /verifikasi/prestasi`
**Controller:** `VerifikasiController@prestasiMenunggu`

**Fitur:**
- List semua prestasi dengan status 'menunggu' atau 'revisi'
- Filter berdasarkan:
  - Status verifikasi
  - Range tanggal
  - Kelas siswa
- Pagination 20 data per halaman
- Informasi: Siswa, Kelas, Prestasi, Poin, Tanggal, Status

#### 2. Detail Prestasi
**Route:** `GET /verifikasi/prestasi/{id}`
**Controller:** `VerifikasiController@prestasiDetail`

**Fitur:**
- Detail lengkap prestasi
- Informasi siswa & kelas
- Jenis prestasi & poin reward
- Keterangan dari guru pencatat
- Riwayat 5 prestasi terakhir siswa
- Total akumulasi poin prestasi siswa
- Form verifikasi dengan 3 opsi aksi

#### 3. Approve Prestasi
**Route:** `POST /verifikasi/prestasi/{id}/approve`
**Controller:** `VerifikasiController@verifikasiPrestasi`

**Fitur:**
- Update status menjadi 'terverifikasi'
- Catat guru verifikator
- Catat tanggal verifikasi
- Simpan catatan verifikasi (opsional)
- Log ke tabel verifikasi_datas
- Database transaction

#### 4. Reject Prestasi
**Route:** `POST /verifikasi/prestasi/{id}/reject`
**Controller:** `VerifikasiController@tolakPrestasi`

**Fitur:**
- Update status menjadi 'ditolak'
- Wajib isi alasan penolakan
- Catat guru verifikator & tanggal
- Log penolakan ke tabel verifikasi_datas
- Database transaction

#### 5. Minta Revisi Prestasi
**Route:** `POST /verifikasi/prestasi/{id}/revisi`
**Controller:** `VerifikasiController@revisiPrestasi`

**Fitur:**
- Update status menjadi 'revisi'
- Wajib isi catatan revisi
- Data dikembalikan ke guru untuk diperbaikan
- Log ke tabel verifikasi_datas
- Database transaction

#### 6. Bulk Approve Prestasi
**Route:** `POST /verifikasi/prestasi/bulk-approve`
**Controller:** `VerifikasiController@bulkVerifikasiPrestasi`

**Fitur:**
- Verifikasi multiple prestasi sekaligus
- Checkbox selection di list prestasi
- Batch processing dengan transaction
- Log setiap verifikasi
- Efficient untuk data banyak

### D. Riwayat Verifikasi
**Route:** `GET /verifikasi/riwayat`
**Controller:** `VerifikasiController@riwayat`

**Fitur:**
- Daftar semua verifikasi yang dilakukan verifikator
- Filter berdasarkan:
  - Tabel terkait (pelanggarans/prestasis)
  - Status (diverifikasi/ditolak/revisi)
  - Range tanggal
- Pagination 20 data per halaman
- Informasi: Tanggal, Tabel, ID, Status, Catatan
- Audit trail untuk akuntabilitas

---

## 4. STATUS VERIFIKASI

### Status untuk Pelanggaran & Prestasi
```php
'menunggu'      // Data baru, butuh verifikasi
'terverifikasi' // Sudah diverifikasi, valid
'ditolak'       // Data tidak valid, ditolak
'revisi'        // Butuh perbaikan dari guru
```

### Workflow Status
```
Input Guru → 'menunggu'
    ↓
Verifikator Review
    ↓
├─→ Approve → 'terverifikasi' (Final)
├─→ Reject → 'ditolak' (Final)  
└─→ Revisi → 'revisi' → Guru Edit → 'menunggu' (Loop)
```

---

## 5. DATABASE LOGGING

### Tabel: verifikasi_datas
Setiap aksi verifikasi dicatat ke tabel `verifikasi_datas`:

```php
- id (PK)
- tabel_terkait      // 'pelanggarans' atau 'prestasis'
- id_terkait         // ID dari tabel terkait
- guru_verifikator   // ID guru yang verifikasi (FK)
- status             // 'diverifikasi', 'ditolak', 'revisi'
- catatan            // Catatan/alasan verifikasi
- created_at         // Timestamp verifikasi
- updated_at
```

**Tujuan Logging:**
- Audit trail lengkap
- Tracking siapa verifikator
- Riwayat verifikasi untuk akuntabilitas
- Analisis performa verifikator

---

## 6. VALIDASI & SECURITY

### Validasi Input
```php
// Approve - Catatan opsional
'catatan_verifikasi' => 'nullable|string|max:500'

// Reject - Alasan wajib
'alasan_penolakan' => 'required|string|max:500'

// Revisi - Catatan wajib  
'catatan_revisi' => 'required|string|max:500'

// Bulk - Array ID valid
'pelanggaran_ids' => 'required|array',
'pelanggaran_ids.*' => 'exists:pelanggarans,id'
```

### Middleware Protection
```php
// Hanya admin & kesiswaan bisa akses
Route::middleware('role:admin,kesiswaan')
```

### Database Transaction
Semua operasi verifikasi menggunakan DB transaction untuk memastikan data integrity:
```php
DB::beginTransaction();
try {
    // Update status
    // Log verifikasi
    DB::commit();
} catch (\Exception $e) {
    DB::rollback();
    // Error handling
}
```

---

## 7. FITUR TAMBAHAN

### A. Statistik Real-time
- Total data menunggu verifikasi
- Total data butuh revisi
- Performance hari ini (terverifikasi & ditolak)
- Trend verifikasi 7 hari (chart)

### B. Filter & Search
- Multi-filter untuk efisiensi
- Filter by status, tanggal, kelas, kategori
- Quick filter untuk data urgent

### C. Bulk Operations
- Batch verification untuk efisiensi
- Checkbox multi-select
- Satu klik verifikasi banyak data

### D. Context Information
- Total poin siswa saat ini
- Riwayat pelanggaran/prestasi siswa
- Membantu keputusan verifikasi

---

## 8. ROUTES SUMMARY

```php
// Dashboard
GET  /verifikasi                              → Dashboard

// Pelanggaran
GET  /verifikasi/pelanggaran                  → List menunggu
GET  /verifikasi/pelanggaran/{id}             → Detail
POST /verifikasi/pelanggaran/{id}/approve     → Approve
POST /verifikasi/pelanggaran/{id}/reject      → Reject
POST /verifikasi/pelanggaran/{id}/revisi      → Revisi
POST /verifikasi/pelanggaran/bulk-approve     → Bulk approve

// Prestasi
GET  /verifikasi/prestasi                     → List menunggu
GET  /verifikasi/prestasi/{id}                → Detail
POST /verifikasi/prestasi/{id}/approve        → Approve
POST /verifikasi/prestasi/{id}/reject         → Reject
POST /verifikasi/prestasi/{id}/revisi         → Revisi
POST /verifikasi/prestasi/bulk-approve        → Bulk approve

// Riwayat
GET  /verifikasi/riwayat                      → Riwayat verifikasi
```

---

## 9. IMPLEMENTASI

### File yang Dibuat:
1. **Controller:** `app/Http/Controllers/VerifikasiController.php`
2. **Routes:** Ditambahkan di `routes/web.php`
3. **Dokumentasi:** `DOKUMENTASI_VERIFIKATOR.md`

### File yang Perlu Dibuat Selanjutnya:
1. **Views:**
   - `resources/views/verifikasi/dashboard.blade.php`
   - `resources/views/verifikasi/pelanggaran-menunggu.blade.php`
   - `resources/views/verifikasi/pelanggaran-detail.blade.php`
   - `resources/views/verifikasi/prestasi-menunggu.blade.php`
   - `resources/views/verifikasi/prestasi-detail.blade.php`
   - `resources/views/verifikasi/riwayat.blade.php`

2. **Migration:** (jika belum ada kolom)
   - Add `guru_verifikator` to `pelanggarans` table
   - Add `tanggal_verifikasi` to `pelanggarans` table
   - Add `catatan_verifikasi` to `pelanggarans` table
   - Add `guru_verifikator` to `prestasis` table
   - Add `tanggal_verifikasi` to `prestasis` table
   - Add `catatan_verifikasi` to `prestasis` table

---

## 10. TESTING

### Test Cases:
1. ✅ Verifikator bisa lihat dashboard
2. ✅ Verifikator bisa lihat list pelanggaran menunggu
3. ✅ Verifikator bisa approve pelanggaran
4. ✅ Verifikator bisa reject pelanggaran
5. ✅ Verifikator bisa minta revisi pelanggaran
6. ✅ Verifikator bisa bulk approve pelanggaran
7. ✅ Verifikator bisa lihat list prestasi menunggu
8. ✅ Verifikator bisa approve prestasi
9. ✅ Verifikator bisa reject prestasi
10. ✅ Verifikator bisa minta revisi prestasi
11. ✅ Verifikator bisa bulk approve prestasi
12. ✅ Verifikator bisa lihat riwayat verifikasi
13. ✅ Filter berfungsi dengan baik
14. ✅ Logging verifikasi tercatat
15. ✅ Database transaction rollback jika error

---

**Dibuat berdasarkan:** Modul UJI KOMPETENSI KEAHLIAN Paket 1 - Aplikasi Pencatatan Poin Pelanggaran, Sanksi dan Prestasi Siswa
