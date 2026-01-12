# Backup System Documentation

## Overview
Sistem backup otomatis untuk database dan file storage menggunakan Spatie Laravel Backup package.

## Package Installed
```bash
composer require spatie/laravel-backup
```

## Configuration Files

### 1. config/backup.php
- Backup database MySQL
- Backup storage/app/public
- Simpan di: storage/app/private/sistem-kesiswaan

### 2. config/database.php
- MySQL dump path: C:\xampp\mysql\bin

### 3. .env
```
DB_HOST=localhost
```

## Manual Backup Command
```bash
php artisan backup:run
```

## Backup Location
```
storage/app/private/sistem-kesiswaan/
```

## UI Access
- Menu: Admin Sidebar → "Backup System"
- Route: /admin/backup
- Controller: app/Http/Controllers/BackupController.php
- View: resources/views/backup/index.blade.php

## Features Implemented
- ✅ Create backup via UI
- ✅ List backup files
- ✅ Download backup files
- ✅ Delete backup files
- ✅ Auto-refresh after create
- ✅ Loading indicator
- ✅ SweetAlert notifications

## Known Issues
- File display synchronization issue (files created but not immediately visible in UI)
- Requires manual page refresh or cache clear

## Alternative Manual Backup
Jika UI bermasalah, gunakan command line:
```bash
# Create backup
php artisan backup:run

# List files
dir storage\app\private\sistem-kesiswaan

# Download manual dari folder
storage\app\private\sistem-kesiswaan\
```

## Notes
Package sudah terinstall dan terkonfigurasi dengan benar. Backup files berhasil dibuat di lokasi yang tepat.
