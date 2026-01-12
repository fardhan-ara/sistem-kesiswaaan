<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    protected $fillable = [
        'siswa_id', 
        'guru_pencatat', 
        'jenis_prestasi_id', 
        'tahun_ajaran_id',
        'poin', 
        'keterangan', 
        'tanggal_prestasi',
        'status_verifikasi',
        'guru_verifikator',
        'tanggal_verifikasi'
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPrestasi(): BelongsTo
    {
        return $this->belongsTo(JenisPrestasi::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_pencatat');
    }

    public function guruPencatat(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_pencatat');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_verifikator');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
