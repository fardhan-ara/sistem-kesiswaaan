# Fitur Biodata Orang Tua - SIMPLE VERSION

## Fitur
- Orang tua signup → Modal muncul → Pilih siswa dari database → Isi biodata → Upload KK
- Admin review → Approve/Reject
- 1 Syarat: Foto KK

## Data yang Dikumpulkan
- Siswa (pilih dari database yang sudah ada)
- Nama Ayah + Telepon (opsional)
- Nama Ibu + Telepon (opsional)
- Nama Wali + Telepon (opsional - untuk yatim/piatu)
- Foto KK (wajib, max 2MB)

## Cara Kerja
1. **Orang Tua**: Signup → Modal muncul → Pilih anak dari dropdown → Isi data (minimal salah satu: ayah/ibu/wali) → Upload KK
2. **Admin**: Menu "Approval Biodata Ortu" → Lihat daftar → Detail → Approve/Reject

## Testing
1. Buka `/signup` → Pilih role "Orang Tua"
2. Isi form signup → Submit
3. Modal biodata muncul → Pilih siswa, isi data ortu/wali, upload KK
4. Login sebagai admin → Menu "Approval Biodata Ortu" → Review → Approve
5. Login lagi sebagai ortu → Lihat data anak

## Database
```sql
biodata_ortus:
- user_id (FK ke users)
- siswa_id (FK ke siswas) 
- nama_ayah, telp_ayah (nullable)
- nama_ibu, telp_ibu (nullable)
- nama_wali, telp_wali (nullable)
- foto_kk (required)
- status_approval (pending/approved/rejected)
```

## File Penting
- Migration: `database/migrations/2024_01_20_000001_create_biodata_ortus_table.php`
- Model: `app/Models/BiodataOrtu.php`
- Controller: `app/Http/Controllers/BiodataOrtuController.php`
- Views: `resources/views/biodata_ortu/`
- Modal: `resources/views/biodata_ortu/modal.blade.php`

## Status
✅ Migration done
✅ Routes registered
✅ Views created
✅ Controller ready
✅ Menu added to sidebar
✅ Integration with existing siswa data
✅ KTP dihapus - hanya KK
✅ Wali ditambahkan untuk yatim/piatu
✅ Semua field ortu opsional

**READY TO USE!**
