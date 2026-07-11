<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Winner extends Model
{
    protected $fillable = ['event_id', 'event_class_id', 'winner_predikat_id', 'nama_pemenang', 'peringkat'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(EventClass::class, 'event_class_id');
    }

    public function predikat(): BelongsTo
    {
        return $this->belongsTo(WinnerPredikat::class, 'winner_predikat_id');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }
}
