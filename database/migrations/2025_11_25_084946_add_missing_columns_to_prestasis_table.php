<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            $table->foreignId('guru_pencatat')->nullable()->after('siswa_id')->constrained('gurus')->onDelete('set null');
            $table->foreignId('tahun_ajaran_id')->nullable()->after('jenis_prestasi_id')->constrained('tahun_ajarans')->onDelete('set null');
            $table->date('tanggal_prestasi')->nullable()->after('poin');
            $table->foreignId('guru_verifikator')->nullable()->after('status_verifikasi')->constrained('gurus')->onDelete('set null');
            $table->timestamp('tanggal_verifikasi')->nullable()->after('guru_verifikator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            $table->dropForeign(['guru_pencatat']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['guru_verifikator']);
            $table->dropColumn(['guru_pencatat', 'tahun_ajaran_id', 'tanggal_prestasi', 'guru_verifikator', 'tanggal_verifikasi']);
        });
    }
};
