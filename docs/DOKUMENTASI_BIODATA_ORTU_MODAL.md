# Sistem Biodata Orang Tua - Modal & Approval

## Overview

Sistem biodata orang tua dengan modal otomatis yang muncul saat orang tua login pertama kali. Sistem ini memvalidasi nama dan NIS siswa, kemudian mengirim biodata ke menu approval untuk diverifikasi admin.

## Fitur Utama

### 1. Modal Otomatis
- ✅ Modal muncul otomatis saat orang tua belum lengkapi biodata
- ✅ Modal tidak bisa ditutup (backdrop: static, keyboard: false)
- ✅ Form lengkap dengan validasi

### 2. Validasi Data Siswa
- ✅ Input: Nama Siswa + NIS
- ✅ Sistem cari siswa dengan LIKE nama dan exact NIS
- ✅ Jika tidak ditemukan → Error
- ✅ Jika sudah terhubung dengan ortu lain → Error

### 3. Dokumen Wajib
- ✅ Foto Kartu Keluarga (KK) - Required
- ✅ Foto KTP Orang Tua - Required
- ✅ Format: JPG, PNG, PDF
- ✅ Max size: 2MB per file

### 4. Approval System
- ✅ Status: pending → approved/rejected
- ✅ Admin review di menu "Biodata Orang Tua"
- ✅ Setelah approved, ortu bisa akses data anak

## Alur Sistem

### A. Orang Tua Login Pertama Kali

```
1. Ortu login dengan email
2. Dashboard cek: Apakah ada biodata?
   - Tidak ada → Status: no_biodata
   - Ada tapi pending → Status: pending
   - Ada tapi rejected → Status: rejected
   - Ada dan approved → Tampilkan data anak
3. Jika no_biodata/rejected → Modal muncul otomatis
```

### B. Lengkapi Biodata

```
1. Ortu isi form di modal:
   - Nama Siswa (anak)
   - NIS
   - Data Ayah (opsional)
   - Data Ibu (opsional)
   - Data Wali (opsional)
   - Alamat
   - Upload KK
   - Upload KTP
2. Submit form
3. Sistem validasi:
   - Cari siswa by nama (LIKE) + NIS (exact)
   - Cek apakah siswa sudah terhubung ortu lain
4. Jika valid:
   - Upload files ke storage/app/public/biodata_ortu/
   - Create record biodata_ortus dengan status: pending
   - Redirect ke dashboard dengan pesan success
5. Jika tidak valid:
   - Redirect back dengan error message
```

### C. Admin Approval

```
1. Admin akses menu "Biodata Orang Tua"
2. List biodata dengan status pending
3. Admin klik "Lihat Detail"
4. Admin review:
   - Data orang tua
   - Foto KK
   - Foto KTP
   - Data siswa yang terhubung
5. Admin action:
   - Approve → Status: approved
   - Reject → Status: rejected + alasan
6. Ortu dapat notifikasi (email/dashboard)
```

### D. Setelah Approved

```
1. Ortu login
2. Dashboard tampilkan data anak:
   - Profil siswa
   - Total pelanggaran
   - Total prestasi
   - Total poin
   - Sanksi aktif
   - Riwayat pelanggaran
   - Riwayat prestasi
```

## Struktur Database

### Tabel: biodata_ortus

```sql
CREATE TABLE biodata_ortus (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,           -- FK ke users (ortu)
    siswa_id BIGINT NOT NULL,          -- FK ke siswas (anak)
    nama_ayah VARCHAR(255),
    telp_ayah VARCHAR(15),
    nama_ibu VARCHAR(255),
    telp_ibu VARCHAR(15),
    nama_wali VARCHAR(255),
    telp_wali VARCHAR(15),
    alamat TEXT,
    foto_kk VARCHAR(255) NOT NULL,     -- Path file KK
    foto_ktp VARCHAR(255) NOT NULL,    -- Path file KTP
    status_approval ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT,
    approved_by BIGINT,                -- FK ke users (admin)
    approved_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## File yang Dimodifikasi

### 1. resources/views/biodata_ortu/modal.blade.php

**Perubahan:**
- Modal muncul otomatis jika status: no_biodata/rejected
- Form input nama siswa + NIS (bukan dropdown)
- Tambah field alamat
- Tambah upload foto KTP
- Validasi client-side
- Loading state saat submit

### 2. app/Http/Controllers/BiodataOrtuController.php

**Method store():**
```php
public function store(Request $request)
{
    // 1. Validasi input
    $validated = $request->validate([
        'nama_siswa' => 'required|string|max:255',
        'nis' => 'required|string|max:20',
        'nama_ayah' => 'nullable|string|max:255',
        // ... dst
        'foto_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'foto_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // 2. Cari siswa by nama + NIS
    $siswa = Siswa::where('nama_siswa', 'LIKE', '%' . $validated['nama_siswa'] . '%')
        ->where('nis', $validated['nis'])
        ->first();

    if (!$siswa) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Data siswa tidak ditemukan...');
    }

    // 3. Cek duplikasi
    $existingBiodata = BiodataOrtu::where('siswa_id', $siswa->id)
        ->whereIn('status_approval', ['pending', 'approved'])
        ->first();

    if ($existingBiodata) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Siswa ini sudah terhubung...');
    }

    // 4. Upload files
    $fotoKK = $request->file('foto_kk')->store('biodata_ortu/kk', 'public');
    $fotoKTP = $request->file('foto_ktp')->store('biodata_ortu/ktp', 'public');

    // 5. Create biodata
    BiodataOrtu::create([
        'user_id' => Auth::id(),
        'siswa_id' => $siswa->id,
        // ... data lainnya
        'foto_kk' => $fotoKK,
        'foto_ktp' => $fotoKTP,
        'status_approval' => 'pending',
    ]);

    return redirect()->route('dashboard')
        ->with('success', 'Biodata berhasil dikirim. Menunggu persetujuan admin.');
}
```

### 3. app/Http/Controllers/DashboardController.php

**Method ortuDashboard():**
```php
private function ortuDashboard()
{
    $user = Auth::user();
    
    // Cek biodata
    $biodata = BiodataOrtu::where('user_id', $user->id)->first();
    
    if (!$biodata) {
        return view('dashboard.ortu', [
            'siswa' => null,
            'status' => 'no_biodata',  // ← Trigger modal
            'message' => 'Silakan lengkapi biodata untuk verifikasi.',
            // ... stats kosong
        ]);
    }
    
    if ($biodata->status_approval === 'pending') {
        return view('dashboard.ortu', [
            'siswa' => null,
            'status' => 'pending',
            'message' => 'Biodata sedang ditinjau admin.',
            // ... stats kosong
        ]);
    }
    
    if ($biodata->status_approval === 'rejected') {
        return view('dashboard.ortu', [
            'siswa' => null,
            'status' => 'rejected',  // ← Trigger modal
            'message' => 'Biodata ditolak: ' . $biodata->rejection_reason,
            // ... stats kosong
        ]);
    }
    
    // Approved → Tampilkan data anak
    $siswa = Siswa::find($biodata->siswa_id);
    // ... query stats
    
    return view('dashboard.ortu', compact('siswa', ...));
}
```

### 4. app/Models/BiodataOrtu.php

**Fillable:**
```php
protected $fillable = [
    'user_id', 'siswa_id', 
    'nama_ayah', 'telp_ayah',
    'nama_ibu', 'telp_ibu', 
    'nama_wali', 'telp_wali', 
    'alamat',              // ← Baru
    'foto_kk', 
    'foto_ktp',            // ← Baru
    'status_approval', 'rejection_reason', 
    'approved_by', 'approved_at'
];
```

### 5. database/migrations/2026_01_12_071909_add_alamat_and_foto_ktp_to_biodata_ortus_table.php

**Migration:**
```php
public function up(): void
{
    Schema::table('biodata_ortus', function (Blueprint $table) {
        $table->text('alamat')->nullable()->after('telp_wali');
        $table->string('foto_ktp')->nullable()->after('foto_kk');
    });
}
```

## Testing

### Test 1: Modal Muncul Otomatis

```bash
# 1. Register akun ortu baru
# 2. Login
# 3. Expected: Modal muncul otomatis
# 4. Coba close modal → Tidak bisa (backdrop static)
```

### Test 2: Validasi Nama + NIS

```bash
# 1. Isi form dengan nama: "Ahmad" dan NIS: "23240001"
# 2. Submit
# 3. Expected: 
#    - Jika data cocok → Success, redirect ke dashboard
#    - Jika tidak cocok → Error "Data siswa tidak ditemukan"
```

### Test 3: Duplikasi

```bash
# 1. Ortu A lengkapi biodata untuk siswa X
# 2. Ortu B coba lengkapi biodata untuk siswa X yang sama
# 3. Expected: Error "Siswa ini sudah terhubung dengan akun orang tua lain"
```

### Test 4: Upload Files

```bash
# 1. Upload KK (JPG, 1MB)
# 2. Upload KTP (PNG, 1.5MB)
# 3. Expected: Files tersimpan di storage/app/public/biodata_ortu/
```

### Test 5: Approval Admin

```bash
# 1. Admin login
# 2. Akses menu "Biodata Orang Tua"
# 3. Klik "Lihat Detail" pada biodata pending
# 4. Klik "Approve"
# 5. Expected: Status berubah jadi approved
# 6. Ortu login → Bisa lihat data anak
```

## Query Monitoring

### 1. Cek Biodata Pending

```sql
SELECT 
    bo.id,
    u.email as email_ortu,
    s.nama_siswa,
    s.nis,
    bo.status_approval,
    bo.created_at
FROM biodata_ortus bo
JOIN users u ON bo.user_id = u.id
JOIN siswas s ON bo.siswa_id = s.id
WHERE bo.status_approval = 'pending'
ORDER BY bo.created_at DESC;
```

### 2. Cek Siswa Tanpa Ortu

```sql
SELECT 
    s.id,
    s.nis,
    s.nama_siswa,
    k.nama_kelas
FROM siswas s
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN biodata_ortus bo ON s.id = bo.siswa_id 
    AND bo.status_approval = 'approved'
WHERE bo.id IS NULL
ORDER BY s.nama_siswa;
```

### 3. Cek Ortu Approved

```sql
SELECT 
    u.email,
    s.nama_siswa,
    s.nis,
    k.nama_kelas,
    bo.approved_at,
    approver.nama as approved_by
FROM biodata_ortus bo
JOIN users u ON bo.user_id = u.id
JOIN siswas s ON bo.siswa_id = s.id
LEFT JOIN kelas k ON s.kelas_id = k.id
LEFT JOIN users approver ON bo.approved_by = approver.id
WHERE bo.status_approval = 'approved'
ORDER BY bo.approved_at DESC;
```

## Troubleshooting

### Modal Tidak Muncul

**Penyebab:** Status tidak sesuai

**Solusi:**
```bash
php artisan tinker
>>> $user = User::where('email', 'ortu@test.com')->first();
>>> $biodata = BiodataOrtu::where('user_id', $user->id)->first();
>>> echo $biodata ? $biodata->status_approval : 'Belum ada biodata';
```

### Error Upload File

**Penyebab:** Storage link belum dibuat

**Solusi:**
```bash
php artisan storage:link
```

### Siswa Tidak Ditemukan

**Penyebab:** Nama atau NIS tidak cocok

**Solusi:**
```bash
php artisan tinker
>>> Siswa::where('nama_siswa', 'LIKE', '%Ahmad%')->where('nis', '23240001')->first();
# Cek apakah data ada
```

## Kesimpulan

✅ **Modal otomatis muncul** saat ortu belum lengkapi biodata
✅ **Validasi nama + NIS** untuk sinkronisasi data yang jelas
✅ **Upload 2 dokumen** (KK + KTP) untuk verifikasi
✅ **Approval system** dengan status pending/approved/rejected
✅ **Pencegahan duplikasi** siswa tidak bisa terhubung 2 ortu
✅ **Production-ready** dengan error handling lengkap

Sistem ini memastikan data orang tua terverifikasi dengan baik sebelum bisa mengakses data anak.
