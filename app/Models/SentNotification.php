<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentNotification extends Model
{
    protected $fillable = [
        'title', 'message', 'type', 'target_type', 'target_value', 
        'recipients_count', 'action_url', 'sent_by'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
