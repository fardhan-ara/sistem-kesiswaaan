@echo off
echo ========================================
echo CHECKING LARAVEL LOG
echo ========================================
echo.
powershell -Command "if (Test-Path 'storage\logs\laravel.log') { Get-Content storage\logs\laravel.log -Tail 50 } else { Write-Host 'No log file found - Controller not called!' }"
echo.
echo ========================================
echo CHECKING DATABASE
echo ========================================
php artisan tinker --execute="echo 'Pelanggaran: ' . App\Models\Pelanggaran::count() . PHP_EOL; echo 'Prestasi: ' . App\Models\Prestasi::count() . PHP_EOL;"
pause
