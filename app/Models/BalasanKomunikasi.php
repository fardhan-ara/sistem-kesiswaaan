<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalasanKomunikasi extends Model
{
    use HasFactory;

    protected $fillable = ['komunikasi_id', 'pengirim_id', 'isi_balasan', 'lampiran'];

    public function komunikasi()
    {
        return $this->belongsTo(KomunikasiOrtu::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}
