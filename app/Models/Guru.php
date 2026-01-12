<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guru extends Model
{
    protected $fillable = ['users_id', 'nip', 'nama_guru', 'jenis_kelamin', 'bidang_studi', 'mata_pelajaran', 'status', 'status_approval'];

    protected $casts = [
        'mata_pelajaran' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($guru) {
            $guru->user()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function kelas(): HasOne
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }
}
