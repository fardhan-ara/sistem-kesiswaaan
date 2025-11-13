# Sistem Kesiswaan - Student Management System

Sistem Informasi Kesiswaan berbasis web menggunakan Laravel 10 untuk mengelola data siswa, pelanggaran, prestasi, sanksi, dan bimbingan konseling.

## Features

### Core Features
- **Manajemen Siswa**: CRUD data siswa dengan kelas dan tahun ajaran
- **Pelanggaran Siswa**: Pencatatan pelanggaran dengan poin otomatis dan verifikasi
- **Prestasi Siswa**: Pencatatan prestasi dengan sistem verifikasi
- **Sanksi Otomatis**: Sanksi dibuat otomatis ketika poin pelanggaran >= 100
- **Bimbingan Konseling**: Pencatatan sesi BK dengan siswa
- **Dashboard**: Statistik dan grafik pelanggaran/prestasi dengan Chart.js
- **Laporan PDF**: Export laporan siswa, pelanggaran, dan prestasi dengan filter

### Technical Features
- **Authentication**: Login/Register dengan role-based access (admin, kesiswaan, guru, siswa, ortu)
- **Authorization**: Policy dan Gate untuk kontrol akses
- **REST API**: API endpoints dengan Sanctum token authentication
- **Notifications**: Email notifications untuk pelanggaran dan sanksi (queue support)
- **Scheduled Tasks**: Backup database dan cleanup old files
- **Testing**: PHPUnit tests untuk API dan authorization
- **PDF Generation**: DomPDF untuk laporan

## Tech Stack

- **Framework**: Laravel 10
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Chart.js
- **PDF**: DomPDF
- **API Auth**: Laravel Sanctum
- **Testing**: PHPUnit

## Database Structure

13 tables dengan foreign key relationships:
- users, gurus, kelas, siswas
- tahun_ajarans, jenis_pelanggarans, pelanggarans
- sanksis, pelaksanaan_sanksis
- jenis_prestasis, prestasis
- bimbingan_konselings, monitoring_pelanggarans, verifikasi_datas

## Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- XAMPP/WAMP (recommended)

### Setup Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd sistem-kesiswaan
```

2. **Install Dependencies**
```bash
composer install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=sistem-kesiswaan
DB_USERNAME=root
DB_PASSWORD=
```

4. **Database Setup**
```bash
php artisan migrate:fresh --seed
```

5. **Storage Link**
```bash
php artisan storage:link
```

6. **Run Application**
```bash
php artisan serve
```

Access: http://localhost:8000

## Default Users (from Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@test.com | password |
| Kesiswaan | kesiswaan@test.com | password |
| Guru | guru@test.com | password |
| Siswa | siswa@test.com | password |

## API Documentation

### Authentication
```bash
POST /api/v1/register
POST /api/v1/login
POST /api/v1/logout
```

### Endpoints (Requires Bearer Token)
```bash
GET    /api/v1/siswa
POST   /api/v1/siswa
GET    /api/v1/siswa/{id}

GET    /api/v1/pelanggaran
POST   /api/v1/pelanggaran
GET    /api/v1/pelanggaran/{id}

GET    /api/v1/prestasi
POST   /api/v1/prestasi
GET    /api/v1/prestasi/{id}

GET    /api/v1/dashboard/stats
```

## Testing

Run all tests:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test --filter=PelanggaranApiTest
php artisan test --filter=PelanggaranAuthorizationTest
```

## Scheduled Tasks

Add to crontab for production:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Tasks:
- Daily database backup (storage/app/backups)
- Cleanup old logs (>30 days) and backups (>7 days)

## Project Structure

```
app/
├── Console/Commands/       # Custom artisan commands
├── Http/
│   ├── Controllers/       # Web & API controllers
│   ├── Middleware/        # Custom middleware
│   ├── Requests/          # Form request validation
│   └── Resources/         # API resources
├── Models/                # Eloquent models
├── Notifications/         # Email notifications
└── Policies/              # Authorization policies

database/
├── migrations/            # Database migrations
└── seeders/              # Database seeders

resources/
└── views/                # Blade templates

routes/
├── web.php               # Web routes
└── api.php               # API routes

tests/
└── Feature/              # Feature tests
```

## Key Features Implementation

### Auto Sanksi Creation
When total verified pelanggaran poin >= 100, sanksi is automatically created and notifications sent.

### Role-Based Authorization
- Admin & Kesiswaan: Full access, can verify pelanggaran/prestasi
- Guru: Can create pelanggaran, view data
- Siswa: View own data only
- Ortu: View child data only

### PDF Reports
Filter by:
- Kelas (class)
- Date range (tanggal_mulai - tanggal_selesai)

Export types:
- Laporan Siswa
- Laporan Pelanggaran
- Laporan Prestasi

## Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contact

Sistem Kesiswaan - kesiswaan@sman1.sch.id

Project Link: [https://github.com/yourusername/sistem-kesiswaan](https://github.com/yourusername/sistem-kesiswaan)
