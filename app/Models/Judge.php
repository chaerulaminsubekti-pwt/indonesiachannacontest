<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Judge extends Model
{
    protected $fillable = ['nama', 'kota'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_judge')
            ->withPivot('urutan')
            ->withTimestamps();
    }
}
