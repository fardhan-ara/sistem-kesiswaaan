<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggaran extends Model
{
    protected $fillable = ['siswa_id', 'guru_pencatat', 'jenis_pelanggaran_id', 'tahun_ajaran_id', 'poin', 'keterangan', 'tanggal_pelanggaran', 'status_verifikasi', 'guru_verifikator', 'tanggal_verifikasi', 'alasan_penolakan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
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

    public function jenisPelanggaran(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    public function sanksi(): HasOne
    {
        return $this->hasOne(Sanksi::class);
    }

    public function sanksis()
    {
        return $this->hasMany(Sanksi::class);
    }
}
