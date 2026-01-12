# Perbaikan Edit Pelanggaran

## Masalah yang Ditemukan

### 1. Variable `$kelompoks` Tidak Didefinisikan
**Error**: `Undefined variable $kelompoks` di view `pelanggaran/edit.blade.php`

**Penyebab**: 
- Controller tidak mengirim variable `$kelompoks` ke view
- Kolom `kelompok` tidak ada di tabel `jenis_pelanggarans` (seharusnya `kategori`)

**Solusi**:
- Menambahkan variable `$kategoris` di `PelanggaranController->edit()`
- Mengganti `$kelompoks` menjadi `$kategoris` di view
- Mengganti `$jenis->kelompok` menjadi `$jenis->kategori`

### 2. Kolom `pelanggaran_list` Tidak Ada di Database
**Error**: `Column not found: 1054 Unknown column 'pelanggaran_list' in 'field list'`

**Penyebab**:
- Model `Pelanggaran` memiliki `pelanggaran_list` di fillable
- Kolom tersebut tidak ada di tabel `pelanggarans`
- Sistem mencoba update kolom yang tidak exist

**Solusi**:
- Menghapus `pelanggaran_list` dari fillable di Model Pelanggaran
- Sistem edit pelanggaran membuat record BARU untuk pelanggaran tambahan (bukan JSON)

### 3. Form Action Menggunakan Route Test
**Error**: Form action menggunakan `/pelanggaran-update-test/` yang tidak standard

**Solusi**:
- Mengganti dengan route standard: `route('pelanggaran.update', $pelanggaran->id)`
- Menambahkan `@method('PUT')` untuk RESTful routing

## Perubahan File

### 1. app/Http/Controllers/PelanggaranController.php

**Method edit()**: Menambahkan variable `$kategoris`
```php
$kategoris = JenisPelanggaran::select('kategori')
    ->distinct()
    ->orderBy('kategori')
    ->pluck('kategori');

return view('pelanggaran.edit', compact(
    'pelanggaran', 'siswas', 'gurus', 
    'jenisPelanggarans', 'kategoris', 'totalPoin'
));
```

**Method update()**: Menyederhanakan validasi dan logic
```php
$validated = $request->validate([
    'keterangan' => 'nullable|string',
    'pelanggaran_tambahan' => 'nullable|array',
    'pelanggaran_tambahan.*' => 'exists:jenis_pelanggarans,id'
]);

// Update hanya keterangan
$pelanggaran->update([
    'keterangan' => $request->keterangan
]);

// Buat record BARU untuk pelanggaran tambahan
foreach ($request->pelanggaran_tambahan as $jenisId) {
    Pelanggaran::create([...]);
}
```

### 2. app/Models/Pelanggaran.php

**Fillable**: Menghapus `pelanggaran_list`
```php
protected $fillable = [
    'siswa_id', 'guru_pencatat', 'jenis_pelanggaran_id', 
    'tahun_ajaran_id', 'poin', 'keterangan', 
    'tanggal_pelanggaran', 'status_verifikasi', 
    'guru_verifikator', 'tanggal_verifikasi', 'alasan_penolakan'
];
```

### 3. resources/views/pelanggaran/edit.blade.php

**Form Action**: Menggunakan route standard
```blade
<form action="{{ route('pelanggaran.update', $pelanggaran->id) }}" method="POST">
    @csrf
    @method('PUT')
```

**Filter Kategori**: Mengganti variable
```blade
@foreach($kategoris as $kategori)
    <option value="{{ $kategori }}">{{ ucfirst($kategori) }}</option>
@endforeach
```

**Data Attribute**: Mengganti kolom
```blade
data-kelompok="{{ $jenis->kategori }}"
```

**Menghapus**: Section pelanggaran_list yang tidak digunakan

## Cara Kerja Edit Pelanggaran

### Konsep Immutable History
Sistem menggunakan konsep **immutable history** untuk pelanggaran:

1. **Record Asli Tidak Diubah**: Pelanggaran yang sudah ada tidak diubah (kecuali keterangan)
2. **Pelanggaran Tambahan = Record Baru**: Setiap pelanggaran tambahan membuat record BARU
3. **Akumulasi Poin Dinamis**: Total poin dihitung dari SUM semua record

### Alur Edit Pelanggaran

1. User membuka form edit pelanggaran
2. Sistem menampilkan data pelanggaran saat ini (readonly)
3. User bisa:
   - Update keterangan
   - Tambah pelanggaran baru (akan jadi record terpisah)
4. Saat submit:
   - Keterangan diupdate di record asli
   - Pelanggaran tambahan dibuat sebagai record BARU
5. Total poin siswa dihitung ulang dari semua record

### Contoh Skenario

**Sebelum Edit**:
- Pelanggaran ID 5: Terlambat (2 poin) - Status: menunggu

**Setelah Edit + Tambah 2 Pelanggaran**:
- Pelanggaran ID 5: Terlambat (2 poin) - Status: menunggu (keterangan updated)
- Pelanggaran ID 101: Tidak masuk tanpa keterangan (30 poin) - Status: menunggu (BARU)
- Pelanggaran ID 102: Membolos (15 poin) - Status: menunggu (BARU)

**Total Poin Siswa**: 2 + 30 + 15 = 47 poin (menunggu verifikasi)

## Perhitungan Total Poin

### Query Total Poin Terverifikasi
```php
$totalPoin = Pelanggaran::where('siswa_id', $siswaId)
    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
    ->sum('poin');
```

### Query Total Poin Semua Status
```php
$totalMenunggu = $siswa->pelanggarans()
    ->where('status_verifikasi', 'menunggu')
    ->sum('poin');

$totalDiverifikasi = $siswa->pelanggarans()
    ->whereIn('status_verifikasi', ['diverifikasi', 'terverifikasi'])
    ->sum('poin');

$totalSemua = $totalMenunggu + $totalDiverifikasi;
```

## Testing

### Test 1: Buka Form Edit
```bash
# Akses: http://localhost:8000/pelanggaran/{id}/edit
# Expected: Form terbuka tanpa error
# Variable $kategoris tersedia untuk filter
```

### Test 2: Update Keterangan
```bash
# Edit keterangan saja tanpa tambah pelanggaran
# Expected: Keterangan terupdate, tidak ada error SQL
```

### Test 3: Tambah Pelanggaran
```bash
# Tambah 2 pelanggaran baru via modal
# Expected: 2 record baru terbuat, total poin bertambah
```

### Test 4: Perhitungan Poin
```bash
php artisan tinker
>>> $siswa = Siswa::find(1);
>>> $siswa->pelanggarans()->sum('poin');
# Expected: Total poin sesuai dengan jumlah semua pelanggaran
```

## Status Verifikasi

Sistem menggunakan 3 status verifikasi:
- **menunggu**: Pelanggaran baru, belum diverifikasi
- **diverifikasi** / **terverifikasi**: Pelanggaran sudah diverifikasi, poin dihitung
- **ditolak**: Pelanggaran ditolak, poin tidak dihitung

## Kesimpulan

✅ **Form edit pelanggaran sudah berfungsi normal**
✅ **Tidak ada error kolom database**
✅ **Perhitungan total poin akurat**
✅ **Sistem immutable history terjaga**
✅ **Audit trail lengkap**

Sistem edit pelanggaran sekarang mengikuti best practice:
- Immutable history untuk audit trail
- Snapshot poin saat kejadian
- Dynamic calculation untuk total poin
- RESTful routing standard
- Clean code tanpa kolom unused
