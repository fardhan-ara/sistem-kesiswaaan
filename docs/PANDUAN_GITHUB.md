# Panduan Upload ke GitHub

## Langkah 1: Buat Repository di GitHub

1. Buka https://github.com
2. Login ke akun Anda
3. Klik tombol **"New"** atau **"+"** di pojok kanan atas
4. Pilih **"New repository"**
5. Isi form:
   - **Repository name:** `sistem-kesiswaan` (atau nama lain)
   - **Description:** Sistem Pencatatan Poin Pelanggaran, Sanksi dan Prestasi Siswa
   - **Visibility:** Public atau Private (pilih sesuai kebutuhan)
   - ❌ **JANGAN** centang "Add a README file" (sudah ada)
   - ❌ **JANGAN** tambah .gitignore (sudah ada)
6. Klik **"Create repository"**

## Langkah 2: Connect Repository Lokal ke GitHub

Setelah repository dibuat, GitHub akan menampilkan instruksi. Copy URL repository Anda, contoh:
```
https://github.com/username/sistem-kesiswaan.git
```

## Langkah 3: Push ke GitHub

### Opsi A: Menggunakan Batch File (Mudah)

1. Double-click file `push_github.bat`
2. Ikuti instruksi di layar
3. Masukkan pesan commit (atau tekan Enter untuk default)
4. Pilih branch (main/master)
5. Selesai!

### Opsi B: Manual via Command Line

Buka **Command Prompt** atau **Git Bash** di folder project, lalu jalankan:

```bash
# 1. Cek status
git status

# 2. Add semua perubahan
git add .

# 3. Commit
git commit -m "Initial commit: Add complete system with verification features"

# 4. Set remote repository (ganti URL dengan URL repository Anda)
git remote add origin https://github.com/username/sistem-kesiswaan.git

# Atau jika sudah ada, update URL:
# git remote set-url origin https://github.com/username/sistem-kesiswaan.git

# 5. Cek branch
git branch

# 6. Rename branch ke main (jika masih master)
git branch -M main

# 7. Push ke GitHub
git push -u origin main
```

### Opsi C: Jika Repository Sudah Ada Remote

```bash
# Cek remote yang ada
git remote -v

# Jika sudah ada origin, langsung:
git add .
git commit -m "Update: Add verification system and documentation"
git push origin main
```

## Langkah 4: Verifikasi

1. Buka browser
2. Akses repository Anda: `https://github.com/username/sistem-kesiswaan`
3. Pastikan semua file sudah terupload
4. Cek file README.md tampil dengan baik

## Troubleshooting

### Error: "remote origin already exists"
```bash
git remote remove origin
git remote add origin https://github.com/username/sistem-kesiswaan.git
```

### Error: "failed to push some refs"
```bash
# Pull dulu jika ada perubahan di remote
git pull origin main --allow-unrelated-histories

# Lalu push lagi
git push origin main
```

### Error: "src refspec main does not match any"
```bash
# Branch masih bernama master, rename dulu:
git branch -M main
git push -u origin main
```

### Autentikasi GitHub

Jika diminta username & password:

**GitHub sudah tidak support password!** Gunakan **Personal Access Token**:

1. Buka GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Klik "Generate new token (classic)"
3. Beri nama token (misal: "Sistem Kesiswaan Upload")
4. Centang scope: `repo` (semua)
5. Klik "Generate token"
6. **COPY token** yang muncul (hanya muncul sekali!)
7. Saat git minta password, paste token tersebut

Atau gunakan **GitHub CLI** atau **SSH Key** untuk lebih mudah.

## File yang Diabaikan (.gitignore)

File berikut **TIDAK** akan diupload (sudah ada di .gitignore):

- `/node_modules`
- `/vendor`
- `.env` (file konfigurasi rahasia)
- `.env.backup`
- `/storage/*.key`
- `/.vscode`
- `/.idea`
- `.phpunit.result.cache`

Ini **BENAR** karena file-file tersebut:
- Berukuran besar (vendor, node_modules)
- Bersifat rahasia (.env)
- Generated otomatis

## Setelah Upload

### Update README.md

Edit file README.md, ganti:
```markdown
git clone https://github.com/username/sistem-kesiswaan.git
```

Dengan URL repository Anda yang sebenarnya.

### Tambahkan Badge

Tambahkan badge di README untuk tampilan profesional:
```markdown
![GitHub repo size](https://img.shields.io/github/repo-size/username/sistem-kesiswaan)
![GitHub stars](https://img.shields.io/github/stars/username/sistem-kesiswaan)
![GitHub forks](https://img.shields.io/github/forks/username/sistem-kesiswaan)
```

### Setup GitHub Pages (Opsional)

Jika ingin dokumentasi online:
1. Settings → Pages
2. Source: Deploy from a branch
3. Branch: main → /docs atau /root
4. Save

## Kolaborasi

Jika ingin kolaborasi dengan teman:

1. **Settings** → **Collaborators**
2. Klik **"Add people"**
3. Masukkan username/email GitHub mereka
4. Mereka akan dapat push/pull ke repository

## Clone di Komputer Lain

Di komputer lain, cukup:

```bash
git clone https://github.com/username/sistem-kesiswaan.git
cd sistem-kesiswaan
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Update Selanjutnya

Setelah coding lagi:

```bash
git add .
git commit -m "Pesan perubahan"
git push origin main
```

Atau pakai `push_github.bat` lagi.

---

**Selamat! Project Anda sudah di GitHub!** 🎉

Repository: https://github.com/username/sistem-kesiswaan
