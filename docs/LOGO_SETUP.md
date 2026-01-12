# Setup Logo SIKAP

## Langkah-langkah:

1. **Simpan file logo** yang Anda upload (`Gemini_Generated_Image_ntkz8ontkz8ontkz.png`) ke folder:
   ```
   public/images/Gemini_Generated_Image_ntkz8ontkz8ontkz.png
   ```

2. **Logo telah diintegrasikan** ke posisi-posisi berikut:
   - ✅ **Halaman Login** - Logo di atas form login dengan nama aplikasi "SIKAP"
   - ✅ **Sidebar Dashboard** - Logo di brand link sidebar
   - ✅ **Footer** - Nama aplikasi SIKAP di footer
   - ✅ **Title Page** - Title browser diubah menjadi "SIKAP"

3. **Ukuran Logo:**
   - Login page: 120px width
   - Sidebar: 60px width
   - Auto height untuk menjaga proporsi

## Catatan:
- Logo menggunakan nama file asli untuk memudahkan identifikasi
- Jika ingin mengganti nama file, update path di 3 file:
  - `resources/views/auth/login.blade.php`
  - `resources/views/layouts/app.blade.php`
  - `resources/views/welcome.blade.php`
