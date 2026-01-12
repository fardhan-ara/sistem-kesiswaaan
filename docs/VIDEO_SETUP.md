# Setup Background Video

## 📹 Langkah-langkah:

1. **Download video background sekolah** (gratis dari situs seperti Pexels, Pixabay, atau Videvo)
   - Keyword: "school", "education", "students", "classroom"
   - Format: MP4
   - Durasi: 10-30 detik (akan loop otomatis)
   - Resolusi: 1920x1080 atau lebih tinggi

2. **Simpan video** dengan nama `school-bg.mp4` ke folder:
   ```
   public/videos/school-bg.mp4
   ```

3. **Rekomendasi Video:**
   - https://www.pexels.com/search/videos/school/
   - https://pixabay.com/videos/search/education/
   - https://www.videvo.net/free-stock-video-footage/school/

## ✨ Fitur yang Ditambahkan:

### **Panel Kiri (Video Background)**
- ✅ Video background full-screen dengan overlay gradient
- ✅ Logo dengan drop-shadow (background transparan)
- ✅ Animasi fade-in untuk logo, judul, dan deskripsi
- ✅ Video auto-play, muted, dan loop

### **Panel Kanan (Form dengan Animasi)**
- ✅ Slide-in animation untuk container
- ✅ Fade-in staggered untuk setiap input field
- ✅ Hover effect dengan transform translateY
- ✅ Focus effect dengan scale dan shadow
- ✅ Button hover dengan gradient reverse dan shadow

## 🎨 Animasi yang Diterapkan:

1. **fadeInDown** - Logo turun dari atas
2. **fadeInUp** - Teks dan form naik dari bawah
3. **slideInRight** - Form container slide dari kanan
4. **Hover Effects** - Transform dan shadow pada input & button

## 📝 Catatan:

- Video akan otomatis loop tanpa suara
- Overlay gradient memastikan teks tetap terbaca
- Logo menggunakan drop-shadow untuk kontras dengan video
- Semua animasi smooth dengan timing yang natural
- Responsive untuk mobile devices

## 🔧 Alternatif (Jika Tidak Ada Video):

Jika tidak ingin menggunakan video, hapus tag `<video>` dan uncomment gradient background:

```css
.left-panel { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
}
```
