<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biodata_ortus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nama_ayah')->nullable();
            $table->string('telp_ayah', 15)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('telp_ibu', 15)->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telp_wali', 15)->nullable();
            $table->string('foto_kk');
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biodata_ortus');
    }
};
