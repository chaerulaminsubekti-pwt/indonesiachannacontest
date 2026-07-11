<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventFlyer extends Model
{
    protected $fillable = ['event_id', 'file_path', 'caption'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
