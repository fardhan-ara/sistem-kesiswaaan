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
        Schema::table('biodata_ortus', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('telp_wali');
            $table->string('foto_ktp')->nullable()->after('foto_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodata_ortus', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'foto_ktp']);
        });
    }
};
