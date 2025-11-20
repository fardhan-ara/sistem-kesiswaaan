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
        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            $table->enum('kategori', ['ringan', 'sedang', 'berat', 'sangat_berat'])->after('poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
