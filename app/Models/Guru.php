<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    protected $fillable = ['users_id', 'nip', 'nama_guru'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
