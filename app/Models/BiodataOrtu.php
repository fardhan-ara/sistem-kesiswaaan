<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataOrtu extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'siswa_id', 'nama_ayah', 'telp_ayah',
        'nama_ibu', 'telp_ibu', 'nama_wali', 'telp_wali', 
        'alamat', 'foto_kk',
        'status_approval', 'rejection_reason', 'approved_by', 'approved_at'
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
