<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPelanggaran extends Model
{
    protected $fillable = ['nama_pelanggaran', 'poin', 'sanksi_rekomendasi'];

    public function pelanggarans(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }
}
