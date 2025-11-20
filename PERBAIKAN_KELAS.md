# Perbaikan Menu Kelas

## Masalah yang Diperbaiki

1. **Error:** `Field 'nama_kelas' doesn't have a default value`
2. Tidak terintegrasi dengan menu Guru untuk memilih Wali Kelas
3. Tidak terintegrasi dengan Tahun Ajaran

## Perubahan yang Dilakukan

### 1. Database Migration (database/migrations/2024_01_01_000002_create_kelas_table.php)

**Struktur Tabel Baru:**
- `id` (PK)
- `nama_kelas` (string) - **REQUIRED**
- `jurusan` (string, nullable)
- `wali_kelas_id` (FK ke gurus, nullable)
- `tahun_ajaran_id` (FK ke tahun_ajarans) - **REQUIRED**
- `timestamps`

### 2. Model (app/Models/Kelas.php)

**Penambahan:**
- Fillable: `nama_kelas`, `jurusan`, `wali_kelas_id`, `tahun_ajaran_id`
- Relasi `waliKelas()` ke model Guru
- Relasi `tahunAjaran()` ke model TahunAjaran

### 3. Controller (app/Http/Controllers/KelasController.php)

**Method index():**
- Eager loading `waliKelas.user` dan `tahunAjaran`

**Method store():**
- Validasi field: `nama_kelas` (required), `jurusan` (nullable), `wali_kelas_id` (nullable), `tahun_ajaran_id` (required)

**Method update():**
- Validasi field yang sama dengan store

### 4. View Create (resources/views/kelas/create.blade.php)

**Perbaikan:**
- Field `nama_kelas` dengan placeholder yang jelas
- Field `jurusan` (optional)
- Dropdown `wali_kelas_id` menampilkan daftar guru dengan nama dan bidang studi
- Dropdown `tahun_ajaran_id` menampilkan tahun ajaran dengan status aktif
- Auto-select tahun ajaran yang aktif
- Error handling yang baik

### 5. View Edit (resources/views/kelas/edit.blade.php) - BARU

**Fitur:**
- Form edit dengan pre-filled data
- Dropdown wali kelas dan tahun ajaran
- Validasi error handling
- Styling konsisten

### 6. View Index (resources/views/kelas/index.blade.php)

**Perbaikan:**
- Kolom: No, Nama Kelas, Jurusan, Wali Kelas, Tahun Ajaran, Aksi
- Menampilkan nama wali kelas dari relasi
- Menampilkan tahun ajaran dari relasi
- Colspan disesuaikan dari 7 menjadi 6

## Struktur Database Kelas

Tabel: `kelas`
- `id` (PK)
- `nama_kelas` (string) - **REQUIRED**
- `jurusan` (string, nullable)
- `wali_kelas_id` (FK ke gurus, nullable)
- `tahun_ajaran_id` (FK ke tahun_ajarans) - **REQUIRED**
- `timestamps`

## Relasi

- **Kelas belongsTo Guru (waliKelas)**: Setiap kelas bisa memiliki 1 wali kelas
- **Kelas belongsTo TahunAjaran**: Setiap kelas terhubung dengan 1 tahun ajaran
- **Kelas hasMany Siswa**: Setiap kelas bisa memiliki banyak siswa

## Fitur yang Sudah Diperbaiki

✅ Field nama_kelas wajib diisi
✅ Integrasi dengan menu Guru untuk memilih Wali Kelas
✅ Integrasi dengan Tahun Ajaran
✅ Wali Kelas bersifat optional (bisa kosong)
✅ Jurusan bersifat optional
✅ Auto-select tahun ajaran aktif pada form create
✅ Tampilan index yang informatif
✅ Form edit yang lengkap
✅ Validasi yang ketat
✅ Error handling yang baik
✅ Styling konsisten dengan AdminLTE

## Cara Penggunaan

### Menambah Kelas Baru:
1. Buka menu Kelas > Tambah Kelas
2. Isi Nama Kelas (wajib) - Contoh: X RPL 1, XI TKJ 2
3. Isi Jurusan (optional) - Contoh: RPL, TKJ, Multimedia
4. Pilih Wali Kelas (optional) - Dari daftar guru yang ada
5. Pilih Tahun Ajaran (wajib) - Otomatis terselect yang aktif
6. Klik Simpan

### Catatan Penting:
- Nama kelas harus diisi (format bebas, contoh: X RPL 1, XI-A, XII IPA 2)
- Wali kelas bisa dikosongkan jika belum ditentukan
- Jurusan bisa dikosongkan
- Tahun ajaran wajib dipilih untuk tracking per periode
- Satu guru bisa menjadi wali kelas di beberapa kelas

## Sesuai dengan Modul

Perbaikan ini mengikuti:
- **Modul 1**: Struktur Tabel Database - KELAS (Halaman 11)
- **Modul 2**: Ruang Lingkup Sistem - Manajemen Data Master (Halaman 7)

Field `wali_kelas_id` menghubungkan kelas dengan guru sebagai wali kelas.
Field `tahun_ajaran_id` menghubungkan kelas dengan periode tahun ajaran.
