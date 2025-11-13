<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggaran_id')->constrained('pelanggarans')->onDelete('cascade');
            $table->string('nama_sanksi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status_sanksi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanksis');
    }
};
