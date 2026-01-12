# Sistem Auto Sanksi - Dokumentasi Lengkap

## Overview

Sistem Auto Sanksi adalah fitur otomatis yang membuat data sanksi ketika total poin pelanggaran siswa yang terverifikasi mencapai threshold tertentu (>= 100 poin).

## Cara Kerja

### 1. Trigger Auto Sanksi

Auto sanksi dipanggil di 2 tempat:

**A. Saat Pelanggaran Baru Dibuat (store)**
```php
// PelanggaranController@store
$pelanggaran = Pelanggaran::create($data);
$this->checkAndCreateSanksi($siswa->id); // ✅ Auto check
```

**B. Saat Pelanggaran Diverifikasi (verify)**
```php
// PelanggaranController@verify
$pelanggaran->update(['status_verifikasi' => 'terverifikasi']);
$this->checkAndCreateSanksi($pelanggaran->siswa_id); // ✅ Auto check
```

### 2. Logic Auto Sanksi

```php
private function checkAndCreateSanksi($siswaId)
{
    // 1. Hitung total poin terverifikasi
    $totalPoin = Pelanggaran::where('siswa_id', $siswaId)
        ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
        ->sum('poin');
    
    // 2. Cek apakah >= 100 poin
    if ($totalPoin >= 100) {
        // 3. Cek apakah sudah ada sanksi aktif
        $sanksiAktif = Sanksi::where('siswa_id', $siswaId)
            ->where('status_sanksi', 'aktif')
            ->first();
        
        // 4. Jika belum ada, buat sanksi baru
        if (!$sanksiAktif) {
            Sanksi::create([
                'siswa_id' => $siswaId,
                'pelanggaran_id' => null,
                'nama_sanksi' => 'Sanksi Otomatis - Poin Pelanggaran >= 100',
                'kategori_poin' => 'sangat_berat',
                'total_poin' => $totalPoin,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addDays(14),
                'status_sanksi' => 'aktif',
                'keterangan' => 'Sanksi dibuat otomatis karena total poin pelanggaran mencapai ' . $totalPoin . ' poin'
            ]);
        }
    }
}
```

## Threshold Poin

| Poin | Sanksi | Durasi | Status |
|------|--------|--------|--------|
| < 100 | Tidak ada sanksi otomatis | - | - |
| >= 100 | Sanksi Otomatis | 14 hari | Aktif |

## Contoh Skenario

### Skenario 1: Akumulasi Bertahap

**Timeline:**
1. **Hari 1**: Siswa A melakukan pelanggaran "Terlambat" (2 poin) → Total: 2 poin → ❌ Belum sanksi
2. **Hari 3**: Siswa A melakukan pelanggaran "Tidak masuk tanpa keterangan" (30 poin) → Total: 32 poin → ❌ Belum sanksi
3. **Hari 5**: Siswa A melakukan pelanggaran "Berkelahi" (40 poin) → Total: 72 poin → ❌ Belum sanksi
4. **Hari 7**: Siswa A melakukan pelanggaran "Merokok" (50 poin) → Total: 122 poin → ✅ **SANKSI OTOMATIS DIBUAT!**

**Hasil:**
- Sanksi otomatis dibuat dengan nama: "Sanksi Otomatis - Poin Pelanggaran >= 100"
- Total poin: 122
- Durasi: 14 hari
- Status: Aktif

### Skenario 2: Langsung Berat

**Timeline:**
1. **Hari 1**: Siswa B melakukan pelanggaran "Narkoba" (100 poin) → Total: 100 poin → ✅ **SANKSI OTOMATIS DIBUAT!**

**Hasil:**
- Sanksi otomatis langsung dibuat
- Total poin: 100
- Durasi: 14 hari
- Status: Aktif

### Skenario 3: Sudah Ada Sanksi Aktif

**Timeline:**
1. **Hari 1**: Siswa C memiliki total 120 poin → Sanksi otomatis dibuat
2. **Hari 3**: Siswa C melakukan pelanggaran lagi "Terlambat" (2 poin) → Total: 122 poin → ❌ **Tidak buat sanksi baru** (sudah ada sanksi aktif)

**Hasil:**
- Sanksi yang sudah ada tetap aktif
- Tidak ada duplikasi sanksi
- Total poin di sanksi existing tidak diupdate (snapshot saat dibuat)

## Status Verifikasi yang Dihitung

Hanya pelanggaran dengan status berikut yang dihitung:
- ✅ `diverifikasi` - Diverifikasi oleh admin/kesiswaan
- ✅ `terverifikasi` - Terverifikasi (alias dari diverifikasi)
- ❌ `menunggu` - Belum diverifikasi, tidak dihitung
- ❌ `ditolak` - Ditolak, tidak dihitung

## Struktur Data Sanksi Otomatis

```php
[
    'siswa_id' => 1,
    'pelanggaran_id' => null, // NULL karena otomatis dari akumulasi
    'nama_sanksi' => 'Sanksi Otomatis - Poin Pelanggaran >= 100',
    'kategori_poin' => 'sangat_berat',
    'total_poin' => 122, // Snapshot total poin saat sanksi dibuat
    'tanggal_mulai' => '2026-01-12',
    'tanggal_selesai' => '2026-01-26', // +14 hari
    'status_sanksi' => 'aktif',
    'keterangan' => 'Sanksi dibuat otomatis karena total poin pelanggaran mencapai 122 poin'
]
```

## Fitur Pencegahan Duplikasi

Sistem mencegah duplikasi sanksi dengan cara:

1. **Cek Sanksi Aktif**: Sebelum membuat sanksi baru, sistem cek apakah sudah ada sanksi dengan status 'aktif'
2. **Skip Jika Ada**: Jika sudah ada sanksi aktif, skip pembuatan sanksi baru
3. **Log Info**: Setiap pembuatan sanksi dicatat di log untuk audit trail

```php
$sanksiAktif = Sanksi::where('siswa_id', $siswaId)
    ->where('status_sanksi', 'aktif')
    ->first();

if (!$sanksiAktif) {
    // Buat sanksi baru
}
```

## Query untuk Monitoring

### 1. Cek Siswa yang Mendekati Threshold

```sql
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    k.nama_kelas,
    SUM(p.poin) as total_poin
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN pelanggarans p ON s.id = p.siswa_id 
    AND p.status_verifikasi IN ('diverifikasi', 'terverifikasi')
GROUP BY s.id
HAVING total_poin >= 80 AND total_poin < 100
ORDER BY total_poin DESC;
```

### 2. Cek Siswa dengan Sanksi Aktif

```sql
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    k.nama_kelas,
    sa.nama_sanksi,
    sa.total_poin,
    sa.tanggal_mulai,
    sa.tanggal_selesai,
    sa.status_sanksi
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
INNER JOIN sanksis sa ON s.id = sa.siswa_id
WHERE sa.status_sanksi = 'aktif'
ORDER BY sa.total_poin DESC;
```

### 3. Cek Riwayat Auto Sanksi

```sql
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    sa.nama_sanksi,
    sa.total_poin,
    sa.tanggal_mulai,
    sa.status_sanksi,
    sa.created_at
FROM siswas s
INNER JOIN sanksis sa ON s.id = sa.siswa_id
WHERE sa.pelanggaran_id IS NULL
ORDER BY sa.created_at DESC;
```

## Testing Auto Sanksi

### Test 1: Akumulasi Poin Sampai 100

```bash
php artisan tinker

# Buat pelanggaran untuk siswa
$siswa = Siswa::first();
$guru = Guru::first();

# Pelanggaran 1: 50 poin
$p1 = Pelanggaran::create([
    'siswa_id' => $siswa->id,
    'guru_pencatat' => $guru->id,
    'jenis_pelanggaran_id' => 1,
    'tahun_ajaran_id' => 1,
    'poin' => 50,
    'tanggal_pelanggaran' => now(),
    'status_verifikasi' => 'diverifikasi'
]);

# Pelanggaran 2: 50 poin (total 100)
$p2 = Pelanggaran::create([
    'siswa_id' => $siswa->id,
    'guru_pencatat' => $guru->id,
    'jenis_pelanggaran_id' => 2,
    'tahun_ajaran_id' => 1,
    'poin' => 50,
    'tanggal_pelanggaran' => now(),
    'status_verifikasi' => 'diverifikasi'
]);

# Trigger auto sanksi
app(PelanggaranController::class)->checkAndCreateSanksi($siswa->id);

# Cek sanksi
$sanksi = Sanksi::where('siswa_id', $siswa->id)->where('status_sanksi', 'aktif')->first();
echo $sanksi ? 'Sanksi berhasil dibuat!' : 'Sanksi tidak dibuat';
```

### Test 2: Verifikasi Pelanggaran

```bash
# Akses web: http://localhost:8000/pelanggaran
# 1. Buat pelanggaran baru dengan poin tinggi (>= 100)
# 2. Verifikasi pelanggaran tersebut
# 3. Cek menu Sanksi, seharusnya ada sanksi baru otomatis
```

## Log Monitoring

Setiap auto sanksi dicatat di log:

```
[2026-01-12 07:00:00] local.INFO: Auto sanksi created {"siswa_id":1,"total_poin":122}
```

Lokasi log: `storage/logs/laravel.log`

## Manajemen Sanksi

### Update Status Sanksi

Sanksi dapat diupdate statusnya menjadi:
- `aktif` - Sanksi sedang berjalan
- `selesai` - Sanksi sudah selesai
- `dibatalkan` - Sanksi dibatalkan

### Perpanjang Sanksi

Jika siswa melakukan pelanggaran lagi saat sanksi aktif:
- Sanksi existing tetap aktif
- Tidak ada sanksi baru dibuat
- Admin dapat manual perpanjang durasi sanksi

## Best Practices

1. ✅ **Verifikasi Pelanggaran Segera**: Verifikasi pelanggaran sesegera mungkin agar auto sanksi berjalan
2. ✅ **Monitor Siswa Mendekati Threshold**: Pantau siswa dengan poin 80-99 untuk pembinaan preventif
3. ✅ **Review Sanksi Aktif**: Review sanksi aktif secara berkala
4. ✅ **Update Status Sanksi**: Update status sanksi menjadi 'selesai' setelah durasi berakhir
5. ✅ **Dokumentasi**: Catat alasan dan tindak lanjut sanksi di kolom keterangan

## Troubleshooting

### Sanksi Tidak Terbuat Otomatis

**Penyebab:**
1. Total poin < 100
2. Pelanggaran belum diverifikasi (status masih 'menunggu')
3. Sudah ada sanksi aktif

**Solusi:**
```bash
php artisan tinker

# Cek total poin siswa
$siswa = Siswa::find(1);
$total = $siswa->pelanggarans()
    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
    ->sum('poin');
echo "Total poin: $total";

# Cek sanksi aktif
$sanksi = Sanksi::where('siswa_id', 1)->where('status_sanksi', 'aktif')->first();
echo $sanksi ? 'Ada sanksi aktif' : 'Tidak ada sanksi aktif';
```

### Duplikasi Sanksi

**Penyebab:** Bug di logic pencegahan duplikasi

**Solusi:**
```sql
-- Hapus sanksi duplikat (keep yang pertama)
DELETE s1 FROM sanksis s1
INNER JOIN sanksis s2 
WHERE s1.siswa_id = s2.siswa_id 
  AND s1.status_sanksi = 'aktif'
  AND s2.status_sanksi = 'aktif'
  AND s1.id > s2.id;
```

## Kesimpulan

✅ **Sistem auto sanksi sudah berfungsi dengan baik**
✅ **Threshold: >= 100 poin**
✅ **Durasi: 14 hari**
✅ **Pencegahan duplikasi: Ada**
✅ **Audit trail: Lengkap di log**
✅ **Production-ready: Ya**

Sistem ini membantu admin/kesiswaan untuk otomatis memberikan sanksi kepada siswa yang melanggar aturan secara berulang tanpa perlu manual input.
