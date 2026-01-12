<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komunikasi_ortus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis', ['pesan', 'laporan_pembinaan', 'panggilan_ortu', 'konsultasi']);
            $table->string('subjek');
            $table->text('isi_pesan');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['terkirim', 'dibaca', 'dibalas'])->default('terkirim');
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();
        });

        Schema::create('balasan_komunikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komunikasi_id')->constrained('komunikasi_ortus')->onDelete('cascade');
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->text('isi_balasan');
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });

        Schema::create('panggilan_ortus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('pelanggaran_id')->nullable()->constrained('pelanggarans')->onDelete('set null');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('keterangan');
            $table->dateTime('tanggal_panggilan');
            $table->string('tempat');
            $table->enum('status', ['menunggu_konfirmasi', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu_konfirmasi');
            $table->text('catatan_hasil')->nullable();
            $table->timestamp('dikonfirmasi_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balasan_komunikasis');
        Schema::dropIfExists('panggilan_ortus');
        Schema::dropIfExists('komunikasi_ortus');
    }
};
