# Sinkronisasi Data Antar Role - Dokumentasi

## Overview

Dokumentasi ini menjelaskan bagaimana data disinkronkan antar berbagai role (Admin, Kesiswaan, Guru, Wali Kelas, BK, Siswa, Orang Tua) untuk memastikan tidak ada miss/anomali data.

## Prinsip Sinkronisasi

### 1. Single Source of Truth
Setiap data memiliki satu sumber utama di database:
- **Siswa** → Tabel `siswas`
- **Pelanggaran** → Tabel `pelanggarans`
- **Prestasi** → Tabel `prestasis`
- **Sanksi** → Tabel `sanksis`
- **BK** → Tabel `bimbingan_konselings`

### 2. Status Verifikasi Konsisten
Semua role menggunakan status yang sama:

**Pelanggaran:**
- `menunggu` - Belum diverifikasi
- `diverifikasi` / `terverifikasi` - Sudah diverifikasi
- `ditolak` - Ditolak

**Prestasi:**
- `pending` - Belum diverifikasi
- `verified` - Sudah diverifikasi
- `rejected` - Ditolak

### 3. Query Konsisten
Semua role menggunakan query yang sama untuk menghitung poin:

```php
// Total Poin Pelanggaran (Terverifikasi)
$totalPoin = Pelanggaran::where('siswa_id', $siswaId)
    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
    ->sum('poin');

// Total Poin Prestasi (Terverifikasi)
$totalPoin = Prestasi::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'verified')
    ->sum('poin');
```

## Sinkronisasi Per Menu

### Menu: Data Siswa

**Role yang Akses:** Admin, Kesiswaan

**Query:**
```php
$siswas = Siswa::with(['kelas', 'tahunAjaran'])->paginate(20);
```

**Sinkronisasi:**
- ✅ Semua role lihat data siswa yang sama
- ✅ Update di satu tempat, semua role lihat perubahan
- ✅ Relasi kelas dan tahun ajaran selalu ter-load

### Menu: Pelanggaran

**Role yang Akses:** Admin, Kesiswaan, Guru, Wali Kelas

**Query Konsisten:**
```php
// Admin & Kesiswaan: Lihat semua
$pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])
    ->latest()->paginate(20);

// Guru: Lihat yang dicatat sendiri
$pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])
    ->where('guru_pencatat', $guruId)
    ->latest()->paginate(20);

// Wali Kelas: Lihat siswa di kelasnya
$pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])
    ->whereHas('siswa', function($q) use ($kelasIds) {
        $q->whereIn('kelas_id', $kelasIds);
    })
    ->latest()->paginate(20);
```

**Sinkronisasi:**
- ✅ Semua role lihat data pelanggaran yang sama (sesuai akses)
- ✅ Status verifikasi konsisten
- ✅ Poin snapshot saat kejadian (immutable)
- ✅ Total poin dihitung dinamis dari riwayat

### Menu: Prestasi

**Role yang Akses:** Admin, Kesiswaan, Guru, Wali Kelas

**Query Konsisten:**
```php
// Admin & Kesiswaan: Lihat semua
$prestasis = Prestasi::with(['siswa.kelas', 'guru', 'jenisPrestasi'])
    ->latest()->paginate(20);

// Guru: Lihat yang dicatat sendiri
$prestasis = Prestasi::with(['siswa.kelas', 'guru', 'jenisPrestasi'])
    ->where('guru_pencatat', $guruId)
    ->latest()->paginate(20);

// Wali Kelas: Lihat siswa di kelasnya
$prestasis = Prestasi::with(['siswa.kelas', 'guru', 'jenisPrestasi'])
    ->whereHas('siswa', function($q) use ($kelasIds) {
        $q->whereIn('kelas_id', $kelasIds);
    })
    ->latest()->paginate(20);
```

**Sinkronisasi:**
- ✅ Semua role lihat data prestasi yang sama (sesuai akses)
- ✅ Status verifikasi konsisten
- ✅ Poin reward dari `jenis_prestasis.poin_reward`
- ✅ Total poin dihitung dinamis

### Menu: Sanksi

**Role yang Akses:** Admin, Kesiswaan

**Query:**
```php
$sanksis = Sanksi::with(['siswa.kelas', 'pelanggaran'])
    ->latest()->paginate(20);
```

**Sinkronisasi:**
- ✅ Sanksi otomatis dibuat saat poin >= 100
- ✅ Semua role lihat sanksi yang sama
- ✅ Status sanksi konsisten (aktif, selesai, dibatalkan)

### Menu: Bimbingan Konseling

**Role yang Akses:** Admin, Kesiswaan, BK

**Query:**
```php
$bks = BimbinganKonseling::with(['siswa.kelas', 'guru'])
    ->latest()->paginate(20);
```

**Sinkronisasi:**
- ✅ Kategori konsisten (pribadi, sosial, belajar, karir)
- ✅ Status konsisten (terjadwal, proses, selesai)
- ✅ Relasi dengan pelanggaran/sanksi

## Dashboard Sinkronisasi

### Dashboard Admin/Kesiswaan

**Statistik:**
```php
$totalSiswa = Siswa::count();
$totalPelanggaran = Pelanggaran::where('status_verifikasi', 'diverifikasi')->count();
$totalPrestasi = Prestasi::where('status_verifikasi', 'verified')->count();
$sanksiAktif = Sanksi::where('status_sanksi', 'aktif')->count();
```

### Dashboard Guru

**Statistik:**
```php
$totalPelanggaran = Pelanggaran::where('guru_pencatat', $guruId)->count();
$totalPrestasi = Prestasi::where('guru_pencatat', $guruId)->count();
```

### Dashboard Wali Kelas

**Statistik:**
```php
$totalPelanggaran = Pelanggaran::whereIn('siswa_id', $siswaIds)->count();
$totalPrestasi = Prestasi::whereIn('siswa_id', $siswaIds)->count();
```

### Dashboard Siswa

**Statistik:**
```php
$totalPelanggaran = Pelanggaran::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'terverifikasi')->count();
$totalPrestasi = Prestasi::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'verified')->count();
$totalPoin = Pelanggaran::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'terverifikasi')->sum('poin');
```

### Dashboard Orang Tua

**Statistik:**
```php
$totalPelanggaran = Pelanggaran::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'diverifikasi')->count();
$totalPrestasi = Prestasi::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'verified')->count();
$totalPoin = Pelanggaran::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'diverifikasi')->sum('poin');
```

## Pencegahan Anomali

### 1. Foreign Key Constraints

```sql
ALTER TABLE pelanggarans 
    ADD CONSTRAINT fk_pelanggaran_siswa 
    FOREIGN KEY (siswa_id) REFERENCES siswas(id) ON DELETE CASCADE;

ALTER TABLE prestasis 
    ADD CONSTRAINT fk_prestasi_siswa 
    FOREIGN KEY (siswa_id) REFERENCES siswas(id) ON DELETE CASCADE;
```

### 2. Validasi di Controller

```php
// Validasi siswa exists
$siswa = Siswa::findOrFail($siswaId);

// Validasi guru exists
$guru = Guru::findOrFail($guruId);

// Validasi jenis pelanggaran exists
$jenis = JenisPelanggaran::findOrFail($jenisId);
```

### 3. Transaction untuk Operasi Kompleks

```php
DB::transaction(function() use ($data) {
    $pelanggaran = Pelanggaran::create($data);
    $this->checkAndCreateSanksi($pelanggaran->siswa_id);
});
```

### 4. Eager Loading untuk Performa

```php
// Hindari N+1 Query
$pelanggarans = Pelanggaran::with(['siswa.kelas', 'guru', 'jenisPelanggaran'])
    ->get();

// Bukan
$pelanggarans = Pelanggaran::all();
foreach($pelanggarans as $p) {
    echo $p->siswa->nama_siswa; // N+1 Query!
}
```

## Checklist Sinkronisasi

### ✅ Data Siswa
- [ ] Semua role lihat data siswa yang sama
- [ ] NIS unik dan tidak duplikat
- [ ] Relasi kelas dan tahun ajaran valid

### ✅ Data Pelanggaran
- [ ] Status verifikasi konsisten
- [ ] Poin snapshot saat kejadian
- [ ] Total poin dihitung dinamis
- [ ] Guru pencatat valid
- [ ] Auto sanksi berjalan

### ✅ Data Prestasi
- [ ] Status verifikasi konsisten
- [ ] Poin reward dari jenis prestasi
- [ ] Total poin dihitung dinamis
- [ ] Guru pencatat valid

### ✅ Data Sanksi
- [ ] Auto create saat poin >= 100
- [ ] Tidak duplikasi sanksi aktif
- [ ] Status sanksi konsisten

### ✅ Data BK
- [ ] Kategori konsisten
- [ ] Status konsisten
- [ ] Relasi dengan siswa valid

### ✅ Dashboard
- [ ] Statistik konsisten antar role
- [ ] Query optimized dengan eager loading
- [ ] Filter role berjalan dengan benar

## Testing Sinkronisasi

### Test 1: Create Pelanggaran dari Berbagai Role

```bash
# Guru A create pelanggaran untuk siswa X
# Admin lihat pelanggaran → Harus muncul
# Wali Kelas lihat pelanggaran → Harus muncul (jika siswa di kelasnya)
# Siswa X lihat dashboard → Total pelanggaran bertambah
# Ortu siswa X lihat dashboard → Total pelanggaran bertambah
```

### Test 2: Verifikasi Pelanggaran

```bash
# Admin verifikasi pelanggaran
# Siswa lihat dashboard → Total poin bertambah
# Ortu lihat dashboard → Total poin bertambah
# Jika poin >= 100 → Sanksi otomatis muncul di menu sanksi
```

### Test 3: Update Data Siswa

```bash
# Admin update nama siswa
# Guru lihat pelanggaran → Nama siswa terupdate
# Wali Kelas lihat siswa → Nama siswa terupdate
# Siswa login → Nama terupdate
# Ortu lihat dashboard → Nama anak terupdate
```

### Test 4: Delete Data

```bash
# Admin delete siswa
# Pelanggaran siswa → Terhapus (CASCADE)
# Prestasi siswa → Terhapus (CASCADE)
# Sanksi siswa → Terhapus (CASCADE)
# BK siswa → Terhapus (CASCADE)
```

## Query Monitoring Sinkronisasi

### 1. Cek Konsistensi Poin

```sql
SELECT 
    s.id,
    s.nama_siswa,
    (SELECT SUM(poin) FROM pelanggarans 
     WHERE siswa_id = s.id 
     AND status_verifikasi IN ('diverifikasi', 'terverifikasi')) as total_poin_db,
    (SELECT total_poin FROM sanksis 
     WHERE siswa_id = s.id 
     AND status_sanksi = 'aktif' 
     ORDER BY created_at DESC LIMIT 1) as total_poin_sanksi
FROM siswas s
HAVING total_poin_db != total_poin_sanksi;
```

### 2. Cek Orphan Records

```sql
-- Pelanggaran tanpa siswa
SELECT * FROM pelanggarans p
LEFT JOIN siswas s ON p.siswa_id = s.id
WHERE s.id IS NULL;

-- Prestasi tanpa siswa
SELECT * FROM prestasis p
LEFT JOIN siswas s ON p.siswa_id = s.id
WHERE s.id IS NULL;
```

### 3. Cek Duplikasi Sanksi

```sql
SELECT siswa_id, COUNT(*) as jumlah
FROM sanksis
WHERE status_sanksi = 'aktif'
GROUP BY siswa_id
HAVING jumlah > 1;
```

## Kesimpulan

✅ **Single Source of Truth** - Satu tabel untuk satu jenis data
✅ **Status Konsisten** - Semua role gunakan status yang sama
✅ **Query Konsisten** - Semua role gunakan query yang sama
✅ **Foreign Key** - Relasi data terjaga
✅ **Eager Loading** - Performa optimal
✅ **Transaction** - Data integrity terjaga
✅ **Validation** - Input data valid
✅ **Cascade Delete** - Tidak ada orphan records

Dengan mengikuti prinsip-prinsip ini, data akan selalu sinkron antar semua role tanpa ada miss/anomali.
