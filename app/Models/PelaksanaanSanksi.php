<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelaksanaanSanksi extends Model
{
    use SoftDeletes;

    protected $fillable = ['sanksi_id', 'tanggal_pelaksanaan', 'keterangan', 'status'];

    public function sanksi(): BelongsTo
    {
        return $this->belongsTo(Sanksi::class);
    }
}
