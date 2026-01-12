<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $fillable = ['tahun_ajaran', 'tahun_mulai', 'tahun_selesai', 'semester', 'status_aktif', 'status_approval'];

    protected $appends = ['tahun_ajaran'];

    public function getTahunAjaranAttribute()
    {
        return $this->tahun_mulai . '/' . $this->tahun_selesai;
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function pelanggarans(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }
}
