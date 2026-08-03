<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = ['winner_id', 'participant_id', 'nomor_sertifikat', 'file_path', 'kode_verifikasi', 'generated_at'];

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Winner::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function getEventAttribute()
    {
        return $this->winner?->event ?? $this->participant?->event;
    }

    public function getNamaPenerimaAttribute(): string
    {
        return $this->participant?->nama_pemilik ?? $this->winner?->nama_pemenang ?? '-';
    }
}
