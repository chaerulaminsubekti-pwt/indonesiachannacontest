<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WinnerPredikat extends Model
{
    protected $fillable = ['event_class_id', 'nama_predikat', 'urutan'];

    public function eventClass(): BelongsTo
    {
        return $this->belongsTo(EventClass::class, 'event_class_id');
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class, 'winner_predikat_id');
    }
}