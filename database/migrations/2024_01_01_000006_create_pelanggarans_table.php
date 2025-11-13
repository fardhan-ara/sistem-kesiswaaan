<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->onDelete('cascade');
            $table->integer('poin');
            $table->text('keterangan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};
