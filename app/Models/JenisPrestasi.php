<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPrestasi extends Model
{
    protected $fillable = ['nama_prestasi', 'tingkat', 'kategori_penampilan', 'poin_reward'];

    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }
}
