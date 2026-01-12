<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanggilanOrtu extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'pelanggaran_id', 'dibuat_oleh', 'judul', 'keterangan',
        'tanggal_panggilan', 'tempat', 'status', 'catatan_hasil', 'dikonfirmasi_at'
    ];

    protected $casts = [
        'tanggal_panggilan' => 'datetime',
        'dikonfirmasi_at' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function pembuatPanggilan()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
