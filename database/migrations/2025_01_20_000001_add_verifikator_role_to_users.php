<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kesiswaan', 'guru', 'wali_kelas', 'siswa', 'ortu', 'bk', 'kepala_sekolah', 'verifikator')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kesiswaan', 'guru', 'wali_kelas', 'siswa', 'ortu', 'bk', 'kepala_sekolah')");
    }
};
