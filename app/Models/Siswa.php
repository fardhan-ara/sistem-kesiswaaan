<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $fillable = ['users_id', 'nis', 'nama_siswa', 'kelas_id', 'jenis_kelamin', 'tahun_ajaran_id', 'status_approval'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($siswa) {
            $siswa->user()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function prestasis()
    {
        return $this->hasMany(Prestasi::class);
    }

    public function sanksis()
    {
        return $this->hasMany(Sanksi::class);
    }

    public function biodataOrtu()
    {
        return $this->hasOne(BiodataOrtu::class);
    }
}
