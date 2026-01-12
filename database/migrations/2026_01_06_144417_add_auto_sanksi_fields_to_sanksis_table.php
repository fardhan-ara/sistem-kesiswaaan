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
        Schema::table('sanksis', function (Blueprint $table) {
            $table->foreignId('siswa_id')->nullable()->after('pelanggaran_id')->constrained('siswas')->onDelete('cascade');
            $table->string('kategori_poin')->nullable()->after('status_sanksi');
            $table->integer('total_poin')->default(0)->after('kategori_poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanksis', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn(['siswa_id', 'kategori_poin', 'total_poin']);
        });
    }
};
