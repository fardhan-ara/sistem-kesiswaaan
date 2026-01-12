# Dokumentasi Sistem Bimbingan Konseling (BK)

## Struktur Database

### Tabel: bimbingan_konselings
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| siswa_id | bigint | Foreign key ke tabel siswas |
| guru_id | bigint | Foreign key ke tabel gurus (Guru BK) |
| kategori | enum | pribadi, sosial, belajar, karir |
| catatan | text | Catatan hasil bimbingan |
| tanggal | date | Tanggal pelaksanaan BK |
| status | varchar | selesai, proses, terjadwal |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

## Relasi Data

### 1. Siswa → Bimbingan Konseling
- **Relasi**: One to Many
- **Model**: `Siswa hasMany BimbinganKonseling`
- **Korelasi**: Setiap siswa bisa memiliki banyak sesi BK
- **Query**: `$siswa->bimbinganKonselings`

### 2. Guru → Bimbingan Konseling
- **Relasi**: One to Many
- **Model**: `Guru hasMany BimbinganKonseling`
- **Korelasi**: Setiap guru BK bisa menangani banyak sesi
- **Query**: `$guru->bimbinganKonselings`

### 3. Bimbingan Konseling → Siswa
- **Relasi**: Belongs To
- **Model**: `BimbinganKonseling belongsTo Siswa`
- **Query**: `$bk->siswa`

### 4. Bimbingan Konseling → Guru
- **Relasi**: Belongs To
- **Model**: `BimbinganKonseling belongsTo Guru`
- **Query**: `$bk->guru`

## Korelasi dengan Sistem Lain

### 1. Korelasi dengan Pelanggaran
- **Trigger BK**: Siswa dengan poin pelanggaran tinggi (>= 50) perlu sesi BK
- **Query**:
```php
$siswaPerluBK = Siswa::whereHas('pelanggarans', function($q) {
    $q->where('status_verifikasi', 'diverifikasi');
})->withSum(['pelanggarans' => function($q) {
    $q->where('status_verifikasi', 'diverifikasi');
}], 'poin')
->having('pelanggarans_sum_poin', '>=', 50)
->get();
```

### 2. Korelasi dengan Sanksi
- **Trigger BK**: Siswa yang mendapat sanksi berat perlu konseling
- **Query**:
```php
$siswaSanksi = Siswa::whereHas('pelanggarans.sanksi', function($q) {
    $q->where('status_sanksi', 'aktif');
})->get();
```

### 3. Korelasi dengan Kelas
- **Akses Data**: Wali kelas bisa melihat siswa kelasnya yang perlu BK
- **Query**:
```php
$siswaKelasPerluBK = Siswa::where('kelas_id', $kelas->id)
    ->whereHas('bimbinganKonselings')
    ->with('bimbinganKonselings')
    ->get();
```

## Kategori Bimbingan

### 1. Pribadi
- Masalah personal siswa
- Emosi, motivasi, kepercayaan diri
- Contoh: Siswa merasa tertekan, cemas

### 2. Sosial
- Hubungan dengan teman, guru, keluarga
- Konflik interpersonal
- Contoh: Bullying, konflik dengan teman

### 3. Belajar
- Kesulitan akademik
- Metode belajar, konsentrasi
- Contoh: Nilai menurun, sulit fokus

### 4. Karir
- Perencanaan masa depan
- Pemilihan jurusan, pekerjaan
- Contoh: Bingung pilih jurusan kuliah

## Status Bimbingan

### 1. Terjadwal
- Sesi BK sudah dijadwalkan tapi belum dilaksanakan
- Siswa sudah dikonfirmasi

### 2. Proses
- Sesi BK sedang berlangsung
- Atau memerlukan follow-up

### 3. Selesai
- Sesi BK sudah selesai
- Catatan sudah lengkap

## Fitur Sistem BK

### 1. Dashboard BK
- Total sesi BK
- BK bulan ini
- Siswa bermasalah (punya pelanggaran)
- Statistik 3 bulan terakhir
- Daftar BK terbaru

### 2. CRUD Bimbingan Konseling
- **Create**: Tambah sesi BK baru
- **Read**: Lihat daftar dan detail BK
- **Update**: Edit data BK
- **Delete**: Hapus data BK

### 3. Filter & Search
- Filter by status (selesai, proses, terjadwal)
- Filter by kategori (pribadi, sosial, belajar, karir)
- Search by nama siswa
- Search by nama guru
- Filter by tanggal

### 4. Integrasi dengan Role
- **Admin/Kesiswaan**: Full access
- **Guru BK**: Bisa CRUD semua data BK
- **Wali Kelas**: Bisa lihat BK siswa kelasnya
- **Guru**: Bisa lihat BK (read only)
- **Siswa**: Bisa lihat BK dirinya sendiri
- **Ortu**: Bisa lihat BK anaknya

## Rekomendasi Penggunaan

### 1. Siswa Perlu BK Jika:
- Total poin pelanggaran >= 50
- Mendapat sanksi berat
- Sering membolos (>3 hari)
- Nilai akademik menurun drastis
- Laporan dari guru/wali kelas

### 2. Frekuensi BK:
- **Ringan**: 1x per semester
- **Sedang**: 1x per bulan
- **Berat**: 2x per bulan atau lebih

### 3. Follow-up:
- Setiap sesi BK harus ada catatan
- Status "proses" untuk kasus yang perlu follow-up
- Update status menjadi "selesai" setelah masalah teratasi

## Query Penting

### 1. Siswa Belum Pernah BK
```php
$siswaBelumBK = Siswa::doesntHave('bimbinganKonselings')->get();
```

### 2. Siswa Sering BK (>3x)
```php
$siswaSeringBK = Siswa::withCount('bimbinganKonselings')
    ->having('bimbingan_konselings_count', '>', 3)
    ->get();
```

### 3. BK Bulan Ini by Kategori
```php
$bkBulanIni = BimbinganKonseling::whereMonth('tanggal', now()->month)
    ->whereYear('tanggal', now()->year)
    ->select('kategori', DB::raw('count(*) as total'))
    ->groupBy('kategori')
    ->get();
```

### 4. Siswa Perlu BK Urgent
```php
$siswaUrgent = Siswa::whereHas('pelanggarans', function($q) {
    $q->where('status_verifikasi', 'diverifikasi');
})
->withSum(['pelanggarans' => function($q) {
    $q->where('status_verifikasi', 'diverifikasi');
}], 'poin')
->having('pelanggarans_sum_poin', '>=', 75)
->doesntHave('bimbinganKonselings')
->get();
```

## Validasi Data

### 1. Saat Create/Update:
- siswa_id: required, exists:siswas,id
- guru_id: required, exists:gurus,id (harus guru BK)
- kategori: required, in:pribadi,sosial,belajar,karir
- tanggal: required, date
- catatan: required, string
- status: required, in:selesai,proses,terjadwal

### 2. Business Logic:
- Guru BK: bidang_studi LIKE '%BK%'
- Tanggal tidak boleh masa depan untuk status "selesai"
- Catatan minimal 20 karakter

## Laporan BK

### 1. Laporan Per Siswa
- Total sesi BK
- Kategori yang paling sering
- Progress (status selesai vs proses)

### 2. Laporan Per Periode
- Total sesi per bulan
- Kategori terbanyak
- Guru BK paling aktif

### 3. Laporan Per Kelas
- Kelas dengan siswa paling banyak BK
- Kategori masalah dominan per kelas

## Sinkronisasi Data

### 1. Dengan Pelanggaran:
- Auto-suggest siswa untuk BK jika poin >= 50
- Notifikasi ke guru BK

### 2. Dengan Sanksi:
- Siswa dengan sanksi aktif muncul di dashboard BK
- Rekomendasi kategori "pribadi" atau "sosial"

### 3. Dengan Wali Kelas:
- Wali kelas bisa request BK untuk siswanya
- Notifikasi ke guru BK

## Status Sistem: ✅ SINKRON

Semua korelasi data sudah benar:
- ✅ Struktur database lengkap
- ✅ Relasi model sudah tepat
- ✅ Controller menggunakan kolom yang benar
- ✅ View menampilkan data sesuai struktur
- ✅ Dashboard BK sudah sinkron
- ✅ Filter dan search berfungsi
- ✅ Integrasi dengan sistem lain (pelanggaran, sanksi, kelas)
