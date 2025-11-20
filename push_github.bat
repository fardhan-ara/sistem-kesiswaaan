@echo off
echo ========================================
echo   PUSH PROJECT KE GITHUB
echo ========================================
echo.

REM Cek status git
echo [1] Checking git status...
git status
echo.

REM Add semua perubahan
echo [2] Adding all changes...
git add .
echo.

REM Commit dengan pesan
echo [3] Committing changes...
set /p commit_message="Masukkan pesan commit (tekan Enter untuk default): "
if "%commit_message%"=="" (
    git commit -m "Update: Add verification system and documentation"
) else (
    git commit -m "%commit_message%"
)
echo.

REM Push ke GitHub
echo [4] Pushing to GitHub...
echo.
echo PILIH BRANCH:
echo 1. Push ke main
echo 2. Push ke master
echo 3. Push ke branch lain
echo.
set /p branch_choice="Pilihan (1/2/3): "

if "%branch_choice%"=="1" (
    git push origin main
) else if "%branch_choice%"=="2" (
    git push origin master
) else if "%branch_choice%"=="3" (
    set /p custom_branch="Nama branch: "
    git push origin %custom_branch%
) else (
    echo Pilihan tidak valid!
    goto end
)

echo.
echo ========================================
echo   PUSH SELESAI!
echo ========================================
echo.
echo Project sudah di-push ke GitHub.
echo Cek repository Anda di: https://github.com/username/repo-name
echo.

:end
pause
