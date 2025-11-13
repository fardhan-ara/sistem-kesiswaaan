<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggaran_id')->constrained('pelanggarans')->onDelete('cascade');
            $table->text('progres')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_pelanggarans');
    }
};
