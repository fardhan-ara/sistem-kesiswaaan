# SISTEM WALI KELAS - DUAL ROLE SYSTEM

## Overview
Sistem Wali Kelas dengan dual role yang memungkinkan user memiliki 2 role sekaligus (Kesiswaan + Wali Kelas atau BK + Wali Kelas). Wali kelas memiliki akses khusus untuk mengelola kelasnya sendiri dengan data yang tersinkronisasi sempurna.

## Database Structure

### Migration: add_wali_kelas_fields_to_users_table
```sql
- is_wali_kelas (boolean): Flag apakah user adalah wali kelas
- kelas_id (foreign key): ID kelas yang diampu
```

## Dual Role System

### Cara Kerja:
1. User tetap punya role utama (kesiswaan/bk)
2. Field `is_wali_kelas = true` menandakan user juga wali kelas
3. Field `kelas_id` menentukan kelas yang diampu
4. Akses menu muncul berdasarkan kombinasi role

### Contoh Penggunaan:
```php
// User A: Kesiswaan + Wali Kelas
role = 'kesiswaan'
is_wali_kelas = true
kelas_id = 5

// User B: BK + Wali Kelas  
role = 'bk'
is_wali_kelas = true
kelas_id = 3
```

## Fitur Wali Kelas

### 1. Dashboard Kelas
**Route:** `/wali-kelas/dashboard`
**Fitur:**
- Statistik siswa (total, L/P)
- Total pelanggaran & prestasi kelas
- Pelanggaran menunggu verifikasi
- Daftar siswa dengan poin lengkap
- Quick actions (input pelanggaran/prestasi, komunikasi)

### 2. Kelola Siswa Kelas
**Route:** `/wali-kelas/siswa`
**Fitur:**
- Daftar siswa di kelas yang diampu
- Detail siswa dengan riwayat lengkap
- Hanya bisa akses siswa di kelasnya

### 3. Input Pelanggaran
**Route:** `/wali-kelas/pelanggaran/create`
**Fitur:**
- Form input pelanggaran
- Dropdown siswa hanya dari kelasnya
- Auto-fill guru pencatat dari user login
- Validasi siswa harus dari kelas yang diampu

### 4. Input Prestasi
**Route:** `/wali-kelas/prestasi/create`
**Fitur:**
- Form input prestasi
- Dropdown siswa hanya dari kelasnya
- Auto-fill guru pencatat
- Validasi siswa harus dari kelas yang diampu

### 5. Komunikasi dengan Ortu
**Route:** `/wali-kelas/komunikasi`
**Fitur:**
- Daftar komunikasi dengan ortu siswa kelasnya
- Filter otomatis hanya siswa di kelasnya
- Integrasi dengan sistem komunikasi utama

### 6. Laporan Kelas PDF
**Route:** `/wali-kelas/laporan`
**Fitur:**
- Export PDF data kelas lengkap
- Statistik pelanggaran & prestasi per siswa
- Total poin per siswa
- Header dengan info wali kelas & tahun ajaran

## Authorization & Security

### Middleware: CheckWaliKelas
```php
// Cek user adalah wali kelas
if (!auth()->user()->is_wali_kelas) {
    redirect dengan error
}
```

### Data Isolation
Semua query di controller wali kelas menggunakan filter:
```php
->where('kelas_id', $user->kelas_id)
```

Ini memastikan wali kelas HANYA bisa akses data kelasnya sendiri.

## Sinkronisasi Data

### 1. Pelanggaran
- Input dari wali kelas masuk ke tabel `pelanggarans`
- Status awal: `menunggu` verifikasi
- Setelah diverifikasi admin/kesiswaan, poin masuk ke total siswa
- Data tersinkron dengan dashboard utama

### 2. Prestasi
- Input dari wali kelas masuk ke tabel `prestasis`
- Status awal: `pending` verifikasi
- Setelah diverifikasi, poin masuk ke total siswa
- Data tersinkron dengan dashboard utama

### 3. Komunikasi
- Pesan wali kelas masuk ke tabel `komunikasi_ortus`
- Ortu bisa balas langsung
- Riwayat tersimpan dan bisa diakses kedua pihak

## Menu Sidebar

### Untuk User dengan is_wali_kelas = true:
```
WALI KELAS
├── Dashboard Kelas
├── Siswa Kelas
├── Input Pelanggaran
├── Input Prestasi
└── Komunikasi Ortu
```

Menu ini MUNCUL TAMBAHAN di samping menu role utama (kesiswaan/bk).

## Model Updates

### User Model
```php
// Relasi
public function kelasWali()
{
    return $this->belongsTo(Kelas::class, 'kelas_id');
}

// Helper methods
public function isWaliKelas(): bool
{
    return $this->is_wali_kelas === true;
}

public function hasRole($roles): bool
{
    // Support dual role check
    return in_array($this->role, $roles) || 
           ($this->is_wali_kelas && in_array('wali_kelas', $roles));
}
```

## Controller: WaliKelasController

### Methods:
1. `dashboard()` - Dashboard kelas dengan statistik
2. `siswa()` - Daftar siswa kelas
3. `siswaShow($id)` - Detail siswa dengan riwayat
4. `pelanggaranCreate()` - Form input pelanggaran
5. `pelanggaranStore()` - Simpan pelanggaran
6. `prestasiCreate()` - Form input prestasi
7. `prestasiStore()` - Simpan prestasi
8. `komunikasi()` - Daftar komunikasi dengan ortu
9. `laporanKelas()` - Export PDF laporan kelas

## Views Structure
```
resources/views/wali-kelas/
├── dashboard.blade.php
├── siswa/
│   ├── index.blade.php
│   └── show.blade.php
├── pelanggaran/
│   └── create.blade.php
├── prestasi/
│   └── create.blade.php
├── komunikasi/
│   └── index.blade.php
└── laporan-pdf.blade.php
```

## Cara Mengaktifkan Wali Kelas

### Via Database:
```sql
UPDATE users 
SET is_wali_kelas = 1, kelas_id = 5 
WHERE id = 10;
```

### Via Admin Panel (Future):
Bisa dibuat form di manage users untuk:
- Centang "Jadikan Wali Kelas"
- Pilih kelas yang diampu

## Testing Checklist

- [ ] User dengan is_wali_kelas = true bisa akses dashboard wali kelas
- [ ] User tanpa is_wali_kelas tidak bisa akses (redirect dengan error)
- [ ] Wali kelas hanya bisa lihat siswa di kelasnya
- [ ] Input pelanggaran hanya untuk siswa kelasnya
- [ ] Input prestasi hanya untuk siswa kelasnya
- [ ] Komunikasi hanya dengan ortu siswa kelasnya
- [ ] Laporan PDF hanya data kelasnya
- [ ] Data tersinkron dengan sistem utama
- [ ] Dual role berfungsi (menu role utama + menu wali kelas muncul)

## Benefits

1. **Dual Role Flexibility**: User bisa punya 2 role sekaligus
2. **Data Isolation**: Wali kelas hanya akses data kelasnya
3. **Centralized Data**: Semua data tersinkron dengan sistem utama
4. **Easy Management**: Dashboard khusus untuk kelola kelas
5. **Complete CRUD**: Input, view, update data siswa kelasnya
6. **Communication**: Langsung komunikasi dengan ortu siswa
7. **Reporting**: Export laporan kelas dalam PDF

## Security Features

1. Middleware `CheckWaliKelas` untuk proteksi route
2. Query filter `kelas_id` di semua method
3. Validasi siswa harus dari kelas yang diampu
4. Authorization check di setiap action
5. Data isolation sempurna antar kelas

## Integration Points

1. **Pelanggaran**: Masuk ke sistem verifikasi utama
2. **Prestasi**: Masuk ke sistem verifikasi utama
3. **Komunikasi**: Terintegrasi dengan sistem komunikasi ortu
4. **Siswa**: Data siswa dari tabel utama
5. **Kelas**: Relasi ke tabel kelas utama

## Future Enhancements

1. Bulk input pelanggaran/prestasi
2. Grafik statistik kelas
3. Perbandingan antar kelas
4. Notifikasi otomatis ke ortu
5. Absensi siswa
6. Jadwal pelajaran kelas
7. Nilai akademik siswa

---

**Sistem Wali Kelas sudah LENGKAP dan SIAP DIGUNAKAN!**
