# 📋 Panduan Sistem Verifikasi User

## 🎯 Alur Kerja Verifikasi User

### 1. Tambah User Baru
- Admin menambahkan user baru melalui menu "Manage User"
- **Status otomatis: PENDING (Menunggu)**
- User belum bisa login sampai disetujui

### 2. Verifikasi Manual oleh Admin

#### ✅ Menyetujui User
1. Buka menu "Manage User"
2. Cari user dengan status "Menunggu" (badge kuning)
3. Klik tombol **✓** (Setujui)
4. Konfirmasi persetujuan
5. Status berubah menjadi "Disetujui" (badge hijau)
6. User sekarang bisa login

#### ❌ Menolak User
1. Buka menu "Manage User"
2. Cari user dengan status "Menunggu" (badge kuning)
3. Klik tombol **✗** (Tolak)
4. **Wajib isi alasan penolakan**
5. Konfirmasi penolakan
6. Status berubah menjadi "Ditolak" (badge merah)
7. User tidak bisa login

### 3. Status User

| Status | Badge | Keterangan |
|--------|-------|------------|
| **Pending** | 🟡 Kuning | Menunggu persetujuan admin |
| **Approved** | 🟢 Hijau | Disetujui, bisa login |
| **Rejected** | 🔴 Merah | Ditolak, tidak bisa login |

### 4. Edit Status Manual
Admin juga bisa mengubah status melalui form Edit:
1. Klik tombol Edit (✏️)
2. Ubah dropdown "Status"
3. Simpan perubahan

---

## 🔒 Keamanan

### Mengapa Perlu Verifikasi Manual?

1. **Mencegah Akses Tidak Sah**
   - Admin bisa review setiap user sebelum memberikan akses
   - Menghindari registrasi spam atau fake account

2. **Kontrol Penuh**
   - Admin memutuskan siapa yang boleh akses sistem
   - Bisa menolak dengan alasan yang jelas

3. **Audit Trail**
   - Setiap persetujuan/penolakan tercatat
   - Tahu siapa yang menyetujui (verified_by)
   - Tahu kapan disetujui (verified_at)

---

## 📊 Kolom Database

```sql
status              ENUM('pending', 'approved', 'rejected')
verified_by         INT (user_id yang menyetujui)
verified_at         TIMESTAMP (waktu verifikasi)
rejection_reason    TEXT (alasan penolakan)
```

---

## 🚀 Cara Testing

### Test 1: Tambah User Baru
```
1. Login sebagai admin
2. Klik "Tambah User"
3. Isi form dan simpan
4. ✅ Status harus "Menunggu" (pending)
```

### Test 2: Setujui User
```
1. Cari user dengan status "Menunggu"
2. Klik tombol ✓ (Setujui)
3. Konfirmasi
4. ✅ Status berubah jadi "Disetujui"
5. ✅ Muncul notifikasi sukses
```

### Test 3: Tolak User
```
1. Cari user dengan status "Menunggu"
2. Klik tombol ✗ (Tolak)
3. Isi alasan: "Data tidak lengkap"
4. Konfirmasi
5. ✅ Status berubah jadi "Ditolak"
6. ✅ Alasan tersimpan (hover badge merah)
```

### Test 4: Edit Status Manual
```
1. Klik Edit user
2. Ubah dropdown Status
3. Simpan
4. ✅ Status berubah sesuai pilihan
```

---

## 💡 Tips

1. **Selalu isi alasan penolakan** - Membantu tracking dan komunikasi
2. **Review data user** - Pastikan email dan role sudah benar sebelum approve
3. **Gunakan filter** - Urutkan berdasarkan status untuk efisiensi
4. **Backup data** - Gunakan fitur backup sebelum hapus user

---

## 🐛 Troubleshooting

### User tidak bisa login setelah dibuat
**Solusi**: Pastikan status user sudah "Disetujui" (approved)

### Tombol Setujui/Tolak tidak muncul
**Solusi**: Tombol hanya muncul untuk user dengan status "Menunggu" (pending)

### Lupa alasan penolakan
**Solusi**: Hover mouse ke badge "Ditolak" untuk melihat alasan

---

**Last Updated**: 2025-01-25
**Status**: ✅ PRODUCTION READY
