# Perbaikan Edit Prestasi

## Masalah yang Ditemukan

### 1. File edit.blade.php Tidak Ada
**Error**: View `prestasi/edit.blade.php` tidak ditemukan

**Penyebab**: 
- File view untuk edit prestasi belum dibuat
- Controller sudah ada method edit() tapi view-nya missing

**Solusi**:
- Membuat file `resources/views/prestasi/edit.blade.php`
- Menggunakan struktur yang sama dengan edit pelanggaran
- Support filter tingkat dan kategori_penampilan

### 2. Kolom `prestasi_list` Tidak Ada di Database
**Error**: `Column not found: 1054 Unknown column 'prestasi_list' in 'field list'`

**Penyebab**:
- Model `Prestasi` memiliki `prestasi_list` di fillable
- Kolom tersebut tidak ada di tabel `prestasis`
- Sistem mencoba update kolom yang tidak exist

**Solusi**:
- Menghapus `prestasi_list` dari fillable di Model Prestasi
- Sistem edit prestasi membuat record BARU untuk prestasi tambahan (bukan JSON)

### 3. Method edit() Tidak Mengirim Variable Filter
**Error**: Variable `$tingkats` dan `$kategoriPenampilans` tidak tersedia di view

**Solusi**:
- Menambahkan variable `$tingkats` untuk filter tingkat prestasi
- Menambahkan variable `$kategoriPenampilans` untuk filter kategori penampilan
- Menambahkan variable `$totalPoin` untuk info total poin siswa

### 4. Method update() Terlalu Kompleks
**Error**: Update mengubah semua field termasuk siswa_id dan guru_pencatat

**Solusi**:
- Menyederhanakan update: hanya keterangan yang diubah
- Prestasi tambahan dibuat sebagai record BARU
- Mengikuti konsep immutable history

## Perubahan File

### 1. app/Models/Prestasi.php

**Fillable**: Menghapus `prestasi_list`
```php
protected $fillable = [
    'siswa_id', 'guru_pencatat', 'jenis_prestasi_id', 
    'tahun_ajaran_id', 'poin', 'keterangan', 
    'tanggal_prestasi', 'status_verifikasi', 
    'guru_verifikator', 'tanggal_verifikasi'
];
```

### 2. app/Http/Controllers/PrestasiController.php

**Method edit()**: Menambahkan variable filter dan total poin
```php
public function edit(Prestasi $prestasi)
{
    $siswas = Siswa::with('kelas')->orderBy('nama_siswa')->get();
    $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
    $jenisPrestasis = JenisPrestasi::orderBy('tingkat')->orderBy('nama_prestasi')->get();
    
    $tingkats = JenisPrestasi::select('tingkat')
        ->distinct()
        ->orderByRaw("FIELD(tingkat, 'sekolah', 'kecamatan', 'kota', 'provinsi', 'nasional', 'internasional')")
        ->pluck('tingkat');
    
    $kategoriPenampilans = JenisPrestasi::select('kategori_penampilan')
        ->distinct()
        ->orderBy('kategori_penampilan')
        ->pluck('kategori_penampilan');
    
    $totalPoin = Prestasi::where('siswa_id', $prestasi->siswa_id)
        ->where('status_verifikasi', 'verified')
        ->sum('poin');
    
    return view('prestasi.edit', compact(
        'prestasi', 'siswas', 'gurus', 'jenisPrestasis', 
        'tingkats', 'kategoriPenampilans', 'totalPoin'
    ));
}
```

**Method update()**: Menyederhanakan logic
```php
public function update(Request $request, Prestasi $prestasi)
{
    $request->validate([
        'keterangan' => 'nullable|string',
        'prestasi_tambahan' => 'nullable|array',
        'prestasi_tambahan.*' => 'exists:jenis_prestasis,id'
    ]);

    try {
        // Update hanya keterangan
        $prestasi->update([
            'keterangan' => $request->keterangan
        ]);
        
        // Buat record BARU untuk prestasi tambahan
        $jumlahTambahan = 0;
        if ($request->has('prestasi_tambahan') && is_array($request->prestasi_tambahan)) {
            foreach ($request->prestasi_tambahan as $jenisId) {
                $jenisPrestasi = JenisPrestasi::find($jenisId);
                
                Prestasi::create([
                    'siswa_id' => $prestasi->siswa_id,
                    'guru_pencatat' => $prestasi->guru_pencatat,
                    'jenis_prestasi_id' => $jenisId,
                    'tahun_ajaran_id' => $prestasi->tahun_ajaran_id,
                    'poin' => $jenisPrestasi->poin_reward,
                    'tanggal_prestasi' => now(),
                    'status_verifikasi' => 'pending'
                ]);
                $jumlahTambahan++;
            }
        }

        $message = 'Data prestasi berhasil diupdate';
        if ($jumlahTambahan > 0) {
            $message .= ' dan ' . $jumlahTambahan . ' prestasi baru ditambahkan';
        }
        
        return redirect()->route('prestasi.index')->with('success', $message);
    } catch (\Exception $e) {
        return redirect()->route('prestasi.index')->with('error', 'Gagal: ' . $e->getMessage());
    }
}
```

### 3. resources/views/prestasi/edit.blade.php (BARU)

**Struktur Form**:
- Form action: `route('prestasi.update', $prestasi->id)` dengan method PUT
- Info total poin siswa
- Data prestasi saat ini (readonly)
- Section tambah prestasi baru
- Field keterangan (editable)

**Modal Pilih Prestasi**:
- Filter tingkat (sekolah, kecamatan, kota, provinsi, nasional, internasional)
- Search box untuk cari prestasi
- List jenis prestasi dengan poin
- Double click untuk pilih

**JavaScript**:
- Filter prestasi by tingkat dan search
- Add/remove prestasi item
- Form submit dengan loading state
- SweetAlert untuk notifikasi

## Cara Kerja Edit Prestasi

### Konsep Immutable History
Sistem menggunakan konsep **immutable history** untuk prestasi:

1. **Record Asli Tidak Diubah**: Prestasi yang sudah ada tidak diubah (kecuali keterangan)
2. **Prestasi Tambahan = Record Baru**: Setiap prestasi tambahan membuat record BARU
3. **Akumulasi Poin Dinamis**: Total poin dihitung dari SUM semua record

### Alur Edit Prestasi

1. User membuka form edit prestasi
2. Sistem menampilkan data prestasi saat ini (readonly)
3. User bisa:
   - Update keterangan
   - Tambah prestasi baru (akan jadi record terpisah)
4. Saat submit:
   - Keterangan diupdate di record asli
   - Prestasi tambahan dibuat sebagai record BARU
5. Total poin siswa dihitung ulang dari semua record

### Contoh Skenario

**Sebelum Edit**:
- Prestasi ID 10: Juara 1 Lomba Matematika Sekolah (15 poin) - Status: pending

**Setelah Edit + Tambah 2 Prestasi**:
- Prestasi ID 10: Juara 1 Lomba Matematika Sekolah (15 poin) - Status: pending (keterangan updated)
- Prestasi ID 201: Juara 2 Lomba Fisika Kota (35 poin) - Status: pending (BARU)
- Prestasi ID 202: Juara 3 Olimpiade Sains Provinsi (50 poin) - Status: pending (BARU)

**Total Poin Siswa**: 15 + 35 + 50 = 100 poin (menunggu verifikasi)

## Perhitungan Total Poin

### Query Total Poin Terverifikasi
```php
$totalPoin = Prestasi::where('siswa_id', $siswaId)
    ->where('status_verifikasi', 'verified')
    ->sum('poin');
```

### Query Total Poin Semua Status
```php
$totalPending = $siswa->prestasis()
    ->where('status_verifikasi', 'pending')
    ->sum('poin');

$totalVerified = $siswa->prestasis()
    ->where('status_verifikasi', 'verified')
    ->sum('poin');

$totalSemua = $totalPending + $totalVerified;
```

## Filter Prestasi

### Filter Tingkat
- Sekolah
- Kecamatan
- Kota
- Provinsi
- Nasional
- Internasional

### Filter Kategori Penampilan
- Solo
- Duo
- Trio
- Grup
- Tim
- Kolektif

## Testing

### Test 1: Buka Form Edit
```bash
# Akses: http://localhost:8000/prestasi/{id}/edit
# Expected: Form terbuka tanpa error
# Variable $tingkats dan $kategoriPenampilans tersedia
```

### Test 2: Update Keterangan
```bash
# Edit keterangan saja tanpa tambah prestasi
# Expected: Keterangan terupdate, tidak ada error SQL
```

### Test 3: Tambah Prestasi
```bash
# Tambah 2 prestasi baru via modal
# Expected: 2 record baru terbuat, total poin bertambah
```

### Test 4: Perhitungan Poin
```bash
php artisan tinker
>>> $siswa = Siswa::find(1);
>>> $siswa->prestasis()->sum('poin');
# Expected: Total poin sesuai dengan jumlah semua prestasi
```

## Status Verifikasi

Sistem menggunakan 3 status verifikasi:
- **pending**: Prestasi baru, belum diverifikasi
- **verified**: Prestasi sudah diverifikasi, poin dihitung
- **rejected**: Prestasi ditolak, poin tidak dihitung

## Perbedaan dengan Pelanggaran

| Aspek | Pelanggaran | Prestasi |
|-------|-------------|----------|
| Status Pending | menunggu | pending |
| Status Approved | diverifikasi / terverifikasi | verified |
| Status Rejected | ditolak | rejected |
| Kolom Poin | poin | poin |
| Sumber Poin | jenis_pelanggarans.poin | jenis_prestasis.poin_reward |
| Filter | kategori | tingkat + kategori_penampilan |
| Warna Badge | danger (merah) | success (hijau) |

## Kesimpulan

✅ **Form edit prestasi sudah dibuat dan berfungsi**
✅ **Tidak ada error kolom database**
✅ **Perhitungan total poin akurat**
✅ **Sistem immutable history terjaga**
✅ **Audit trail lengkap**
✅ **Filter tingkat dan kategori penampilan tersedia**

Sistem edit prestasi sekarang mengikuti best practice:
- Immutable history untuk audit trail
- Snapshot poin saat kejadian
- Dynamic calculation untuk total poin
- RESTful routing standard
- Clean code tanpa kolom unused
- Konsisten dengan sistem pelanggaran
