<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $fillable = ['nama_tahun', 'semester', 'status'];

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }
}
