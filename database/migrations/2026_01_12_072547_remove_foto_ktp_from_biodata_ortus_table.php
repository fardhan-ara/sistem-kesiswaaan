<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biodata_ortus', function (Blueprint $table) {
            $table->dropColumn('foto_ktp');
        });
    }

    public function down(): void
    {
        Schema::table('biodata_ortus', function (Blueprint $table) {
            $table->string('foto_ktp')->nullable()->after('foto_kk');
        });
    }
};
