<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggaran extends Model
{
    protected $fillable = ['siswa_id', 'guru_id', 'jenis_pelanggaran_id', 'poin', 'keterangan', 'status_verifikasi'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function jenisPelanggaran(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    public function sanksi(): HasOne
    {
        return $this->hasOne(Sanksi::class);
    }
}
