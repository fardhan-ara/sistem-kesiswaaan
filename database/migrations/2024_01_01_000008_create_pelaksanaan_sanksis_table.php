<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaksanaan_sanksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanksi_id')->constrained('sanksis')->onDelete('cascade');
            $table->date('tanggal_pelaksanaan');
            $table->text('keterangan')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaksanaan_sanksis');
    }
};
