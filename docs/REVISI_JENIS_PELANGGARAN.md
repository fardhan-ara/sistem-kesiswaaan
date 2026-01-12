# Revisi Form Jenis Pelanggaran

## Perubahan yang Dilakukan

### 1. Form Tambah Jenis Pelanggaran (`create.blade.php`)
- **Field "Poin"** diubah menjadi **"Kategori Pelanggaran"** dengan dropdown searchable (Select2)
- **Field "Kategori"** diubah menjadi **"Jenis Pelanggaran"** dengan dropdown searchable yang terfilter
- Menambahkan field **"Kelompok Pelanggaran"** untuk kategori seperti:
  - A. KETERTIBAN
  - B. PAKAIAN
  - C. RAMBUT
  - D. BUKU, MAJALAH ATAU KASET TERLARANG
  - E. BENKATA
  - F. OBAT/MINUMAN TERLARANG
  - G. PERKELAHIAN
  - H. PELANGGARAN TERHADAP KEPALA SEKOLAH, GURU DAN KARYAWAN
  - I. KERAJINAN
  - J. KEHADIRAN

### 2. Fitur Dropdown dengan Filter Search
- Menggunakan **Select2** untuk dropdown yang dapat dicari
- Ketika memilih **Kategori Pelanggaran**, dropdown **Jenis Pelanggaran** akan terfilter otomatis
- Setiap jenis pelanggaran menampilkan **bobot poin** di samping namanya
- Contoh: "Sakit tanpa keterangan (surat) - 2 poin"

### 3. Auto-Sync Bobot Poin
- Ketika memilih jenis pelanggaran, field **Bobot Poin** akan terisi otomatis
- Field **Kategori Tingkat** (Ringan/Sedang/Berat/Sangat Berat) juga terisi otomatis berdasarkan poin

### 4. Form Edit Jenis Pelanggaran (`edit.blade.php`)
- Menggunakan struktur yang sama dengan form create
- Field dapat diedit manual untuk fleksibilitas

### 5. Form Input Pelanggaran (`pelanggaran/create.blade.php`)
- Dropdown **Kategori Pelanggaran** menampilkan kelompok (A-J)
- Dropdown **Jenis Pelanggaran** terfilter berdasarkan kategori yang dipilih
- Menampilkan bobot poin untuk setiap jenis pelanggaran

### 6. Tampilan Index (`index.blade.php`)
- Menambahkan kolom **Kategori Pelanggaran** (kelompok)
- Kolom **Nama Pelanggaran** diubah menjadi **Jenis Pelanggaran**
- Kolom **Poin** diubah menjadi **Bobot Poin**
- Menambahkan kolom **Tingkat** untuk menampilkan kategori (Ringan/Sedang/Berat/Sangat Berat)

### 7. Controller Update
- Menambahkan validasi untuk field `kelompok`
- Update method `store()` dan `update()`

### 8. Seeder Data
- Membuat `JenisPelanggaranSeeder.php` dengan 36 jenis pelanggaran sesuai dokumen modul 2
- Data sudah dikategorikan berdasarkan kelompok A-J dengan bobot poin masing-masing

## Cara Menggunakan

### 1. Jalankan Seeder (Opsional)
Jika ingin mengisi data pelanggaran otomatis:
```bash
php artisan db:seed --class=JenisPelanggaranSeeder
```

### 2. Tambah Jenis Pelanggaran Baru
1. Pilih **Kategori Pelanggaran** (A-J)
2. Pilih **Jenis Pelanggaran** dari dropdown yang sudah terfilter
3. **Bobot Poin** akan terisi otomatis
4. **Kategori Tingkat** akan terisi otomatis
5. Isi **Sanksi Rekomendasi** (opsional)
6. Klik **Simpan**

### 3. Input Pelanggaran Siswa
1. Pilih **Siswa**
2. Pilih **Guru Pencatat**
3. Pilih **Kategori Pelanggaran** (contoh: J. KEHADIRAN)
4. Pilih **Jenis Pelanggaran** (contoh: Sakit tanpa keterangan (surat) - 2 poin)
5. **Poin** akan terisi otomatis (2)
6. Isi **Keterangan**
7. Klik **Simpan**

## Klasifikasi Poin

- **Ringan**: 1-15 poin
- **Sedang**: 16-30 poin
- **Berat**: 31-75 poin
- **Sangat Berat**: 76-100 poin

## Sanksi Berdasarkan Akumulasi Poin

- **1-5 poin**: Dicatat dan konseling
- **6-10 poin**: Peringatan lisan
- **11-15 poin**: Peringatan tertulis dengan perjanjian
- **16-100 poin**: Peringatan dalam materai, skors diserahkan kepada orang tua
- **100 poin**: Dikembalikan dari sekolah (dikeluarkan)

## File yang Dimodifikasi

1. `resources/views/jenis-pelanggaran/create.blade.php`
2. `resources/views/jenis-pelanggaran/edit.blade.php`
3. `resources/views/jenis-pelanggaran/index.blade.php`
4. `resources/views/pelanggaran/create.blade.php`
5. `app/Http/Controllers/JenisPelanggaranController.php`
6. `database/seeders/JenisPelanggaranSeeder.php` (baru)

## Teknologi yang Digunakan

- **Select2**: Dropdown dengan fitur search
- **jQuery**: Untuk handling event dan filtering
- **Bootstrap 5**: Styling
- **Laravel 10**: Backend framework
