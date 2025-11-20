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
        Schema::table('jenis_prestasis', function (Blueprint $table) {
            $table->string('kelompok', 100)->after('id')->nullable();
            $table->string('kategori', 50)->after('poin_reward')->nullable();
            $table->string('tingkat', 50)->after('kategori')->nullable();
            $table->text('penghargaan')->after('tingkat')->nullable();
            $table->renameColumn('poin_reward', 'poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_prestasis', function (Blueprint $table) {
            $table->renameColumn('poin', 'poin_reward');
            $table->dropColumn(['tingkat', 'kelompok', 'kategori', 'penghargaan']);
        });
    }
};
