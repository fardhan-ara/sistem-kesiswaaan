<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_wali_kelas')->default(false)->after('role');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null')->after('is_wali_kelas');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['is_wali_kelas', 'kelas_id']);
        });
    }
};
