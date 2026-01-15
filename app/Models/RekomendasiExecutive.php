<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiExecutive extends Model
{
    protected $fillable = ['rekomendasi', 'periode', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
