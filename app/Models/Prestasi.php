<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    protected $fillable = ['siswa_id', 'guru_pencatat', 'jenis_prestasi_id', 'poin', 'keterangan', 'status_verifikasi'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPrestasi(): BelongsTo
    {
        return $this->belongsTo(JenisPrestasi::class);
    }
}
