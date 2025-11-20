<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPelanggaran extends Model
{
    protected $fillable = ['kelompok', 'nama_pelanggaran', 'poin', 'kategori', 'sanksi_rekomendasi'];

    public function pelanggarans(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }
}
