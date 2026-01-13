<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add kategori_penampilan if not exists
        if (!Schema::hasColumn('jenis_prestasis', 'kategori_penampilan')) {
            Schema::table('jenis_prestasis', function (Blueprint $table) {
                $table->string('kategori_penampilan')->nullable()->after('tingkat');
            });
        }
        
        // Populate tingkat based on nama_prestasi
        DB::table('jenis_prestasis')->update([
            'tingkat' => DB::raw("CASE 
                WHEN nama_prestasi LIKE '%Internasional%' THEN 'internasional'
                WHEN nama_prestasi LIKE '%Nasional%' THEN 'nasional'
                WHEN nama_prestasi LIKE '%Provinsi%' THEN 'provinsi'
                WHEN nama_prestasi LIKE '%Kota%' THEN 'kota'
                WHEN nama_prestasi LIKE '%Kecamatan%' THEN 'kecamatan'
                ELSE 'sekolah'
            END")
        ]);
        
        // Populate kategori_penampilan based on nama_prestasi
        DB::table('jenis_prestasis')->update([
            'kategori_penampilan' => DB::raw("CASE 
                WHEN nama_prestasi LIKE '%Olimpiade%' OR nama_prestasi LIKE '%Akademik%' THEN 'akademik'
                WHEN nama_prestasi LIKE '%Olahraga%' OR nama_prestasi LIKE '%Sepak%' OR nama_prestasi LIKE '%Basket%' OR nama_prestasi LIKE '%Voli%' THEN 'olahraga'
                WHEN nama_prestasi LIKE '%Seni%' OR nama_prestasi LIKE '%Musik%' OR nama_prestasi LIKE '%Tari%' THEN 'seni'
                ELSE 'lainnya'
            END")
        ]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('jenis_prestasis', 'kategori_penampilan')) {
            Schema::table('jenis_prestasis', function (Blueprint $table) {
                $table->dropColumn('kategori_penampilan');
            });
        }
    }
};
