@echo off
echo.
echo ╔════════════════════════════════════════════════════════════╗
echo ║     QUICK FIX: Masalah Submit Pelanggaran                 ║
echo ╚════════════════════════════════════════════════════════════╝
echo.

set /p email="Masukkan email user yang bermasalah: "

echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo 1️⃣  Menjalankan Perbaikan Otomatis...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

php artisan pelanggaran:fix %email%

echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo 2️⃣  Membersihkan Cache Browser...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
echo Silakan lakukan manual:
echo 1. Buka browser
echo 2. Tekan Ctrl+Shift+Del
echo 3. Hapus cache dan cookies
echo 4. Restart browser
echo.

echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo 3️⃣  Test Login...
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
echo Silakan test:
echo 1. Login dengan email: %email%
echo 2. Buka: http://localhost:8000/pelanggaran/create
echo 3. Coba submit pelanggaran
echo.

pause
