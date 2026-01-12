<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomunikasiOrtu extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'pengirim_id', 'penerima_id', 'jenis', 'subjek',
        'isi_pesan', 'lampiran', 'status', 'dibaca_at'
    ];

    protected $casts = ['dibaca_at' => 'datetime'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function balasan()
    {
        return $this->hasMany(BalasanKomunikasi::class, 'komunikasi_id');
    }
}
