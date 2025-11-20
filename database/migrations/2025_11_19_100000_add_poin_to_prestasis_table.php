<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            if (!Schema::hasColumn('prestasis', 'poin')) {
                $table->integer('poin')->default(0)->after('jenis_prestasi_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            if (Schema::hasColumn('prestasis', 'poin')) {
                $table->dropColumn('poin');
            }
        });
    }
};
