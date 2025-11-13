<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanksi extends Model
{
    use SoftDeletes;

    protected $fillable = ['pelanggaran_id', 'nama_sanksi', 'tanggal_mulai', 'tanggal_selesai', 'status_sanksi'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function pelaksanaanSanksis(): HasMany
    {
        return $this->hasMany(PelaksanaanSanksi::class);
    }
}
