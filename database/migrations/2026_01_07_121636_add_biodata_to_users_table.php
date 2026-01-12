<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('nama');
            $table->string('no_hp')->nullable()->after('nik');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->string('pekerjaan')->nullable()->after('alamat');
            $table->boolean('biodata_completed')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'no_hp', 'alamat', 'pekerjaan', 'biodata_completed']);
        });
    }
};
