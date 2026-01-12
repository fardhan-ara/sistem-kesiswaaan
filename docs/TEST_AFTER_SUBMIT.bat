@echo off
cls
echo ==========================================
echo CHECKING AFTER FORM SUBMIT
echo ==========================================
echo.
echo [1] Checking if controller was called...
echo.
powershell -Command "if (Test-Path 'storage\logs\laravel.log') { Write-Host 'LOG FILE EXISTS - Controller was called!' -ForegroundColor Green; Write-Host ''; Write-Host 'Last 30 lines:' -ForegroundColor Yellow; Get-Content storage\logs\laravel.log -Tail 30 } else { Write-Host 'NO LOG FILE - Controller NOT called!' -ForegroundColor Red }"
echo.
echo ==========================================
echo [2] Checking database...
echo ==========================================
php artisan tinker --execute="echo 'Total Pelanggaran: ' . App\Models\Pelanggaran::count() . PHP_EOL; if(App\Models\Pelanggaran::count() > 0) { $last = App\Models\Pelanggaran::latest()->first(); echo 'Last entry:' . PHP_EOL; echo '  ID: ' . $last->id . PHP_EOL; echo '  Siswa: ' . $last->siswa->nama_siswa . PHP_EOL; echo '  Guru: ' . $last->guru->nama_guru . PHP_EOL; echo '  Jenis: ' . $last->jenisPelanggaran->nama_pelanggaran . PHP_EOL; }"
echo.
pause
