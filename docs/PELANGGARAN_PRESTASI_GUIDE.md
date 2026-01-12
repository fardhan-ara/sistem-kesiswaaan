# 📋 Panduan Menu Pelanggaran & Prestasi

## ✅ Status: SUDAH DIPERBAIKI

### Menu Pelanggaran
**Route**: `/pelanggaran`

#### Fitur yang Tersedia:
1. ✅ **Halaman Index** - Menampilkan daftar pelanggaran
2. ✅ **Tombol "Tambah Pelanggaran"** - Mengarah ke form input
3. ✅ **Form Create** - Input data pelanggaran siswa
4. ✅ **Detail View** - Lihat detail pelanggaran
5. ✅ **Edit** - Edit pelanggaran (status menunggu)
6. ✅ **Verifikasi** - Admin/Kesiswaan bisa approve/reject

#### Alur Penggunaan:
```
1. Klik menu "Pelanggaran" di sidebar
   ↓
2. Muncul halaman daftar pelanggaran
   ↓
3. Klik tombol "Tambah Pelanggaran" (pojok kanan atas)
   ↓
4. Isi form:
   - Pilih Siswa
   - Pilih Guru Pencatat
   - Pilih Kategori Pelanggaran
   - Pilih Jenis Pelanggaran (poin auto-fill)
   - Isi Keterangan (opsional)
   ↓
5. Klik "Simpan"
   ↓
6. Data tersimpan dengan status "Menunggu"
   ↓
7. Admin/Kesiswaan bisa verifikasi
```

---

### Menu Prestasi
**Route**: `/prestasi`

#### Fitur yang Tersedia:
1. ✅ **Halaman Index** - Menampilkan daftar prestasi
2. ✅ **Tombol "Tambah Prestasi"** - Mengarah ke form input
3. ✅ **Form Create** - Input data prestasi siswa
4. ✅ **Detail View** - Lihat detail prestasi
5. ✅ **Edit** - Edit prestasi (status menunggu)
6. ✅ **Verifikasi** - Admin/Kesiswaan bisa approve/reject

#### Alur Penggunaan:
```
1. Klik menu "Prestasi" di sidebar
   ↓
2. Muncul halaman daftar prestasi
   ↓
3. Klik tombol "Tambah Prestasi" (pojok kanan atas)
   ↓
4. Isi form:
   - Pilih Siswa
   - Pilih Guru Pembimbing
   - Pilih Jenis Prestasi
   - Upload Bukti (opsional)
   - Isi Keterangan (opsional)
   ↓
5. Klik "Simpan"
   ↓
6. Data tersimpan dengan status "Menunggu"
   ↓
7. Admin/Kesiswaan bisa verifikasi
```

---

## 🎯 Perbedaan dengan Data Master

### Data Master (Admin/Kesiswaan Only)
- **Jenis Pelanggaran**: Kelola kategori & jenis pelanggaran
- **Jenis Prestasi**: Kelola kategori & jenis prestasi
- **Siswa**: Kelola data siswa
- **Guru**: Kelola data guru
- **Kelas**: Kelola data kelas

### Kelola (Admin/Kesiswaan/Guru/Wali Kelas)
- **Pelanggaran**: Input pelanggaran siswa (transaksi)
- **Prestasi**: Input prestasi siswa (transaksi)

---

## 🔍 Troubleshooting

### Tombol "Tambah" Tidak Muncul?
**Solusi**: 
- Pastikan sudah login
- Pastikan role: admin, kesiswaan, guru, atau wali_kelas
- Refresh halaman (Ctrl+F5)

### Form Tidak Bisa Submit?
**Solusi**:
- Pastikan semua field required terisi
- Cek console browser (F12) untuk error
- Pastikan data master sudah ada (siswa, guru, jenis pelanggaran/prestasi)

### Data Tidak Muncul di Tabel?
**Solusi**:
- Pastikan sudah ada data di database
- Cek filter role (guru hanya lihat data sendiri)
- Refresh halaman

---

## 📊 Struktur Tabel

### Tabel Pelanggaran
| Kolom | Keterangan |
|-------|------------|
| No | Nomor urut |
| Siswa | Nama siswa |
| Jenis Pelanggaran | Nama pelanggaran |
| Poin | Bobot poin (badge merah) |
| Status | Menunggu/Terverifikasi/Ditolak |
| Tanggal | Tanggal pelanggaran |
| Aksi | Detail, Edit (jika menunggu) |

### Tabel Prestasi
| Kolom | Keterangan |
|-------|------------|
| No | Nomor urut |
| Siswa | Nama siswa |
| Jenis Prestasi | Nama prestasi |
| Poin | Bobot poin (badge hijau) |
| Status | Menunggu/Terverifikasi/Ditolak |
| Tanggal | Tanggal prestasi |
| Aksi | Detail, Edit (jika menunggu) |

---

## 🎨 UI/UX

### Tombol & Badge
- **Tambah**: Biru (primary)
- **Detail**: Biru muda (info)
- **Edit**: Kuning (warning)
- **Hapus**: Merah (danger)
- **Verifikasi**: Hijau (success)
- **Tolak**: Merah (danger)

### Status Badge
- **Menunggu**: Kuning (warning)
- **Terverifikasi**: Hijau (success)
- **Ditolak**: Merah (danger)

---

## ✅ Checklist Fitur

### Pelanggaran
- [x] Index dengan tabel
- [x] Tombol Tambah
- [x] Form Create
- [x] Form Edit
- [x] Detail View
- [x] Verifikasi (Admin/Kesiswaan)
- [x] Tolak dengan alasan
- [x] Pagination
- [x] Notifikasi SweetAlert2

### Prestasi
- [x] Index dengan tabel
- [x] Tombol Tambah
- [x] Form Create
- [x] Form Edit
- [x] Detail View
- [x] Verifikasi (Admin/Kesiswaan)
- [x] Tolak dengan alasan
- [x] Pagination
- [x] Notifikasi SweetAlert2

---

**Last Updated**: 2025-01-25
**Status**: ✅ FULLY FUNCTIONAL
