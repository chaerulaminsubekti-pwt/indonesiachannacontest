<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCp extends Model
{
    protected $fillable = [
        'event_id', 'nama', 'no_wa',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
