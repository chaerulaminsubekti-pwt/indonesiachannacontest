<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    protected $fillable = ['event_id', 'event_class_id', 'nama_peserta', 'no_urut'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(EventClass::class, 'event_class_id');
    }
}
