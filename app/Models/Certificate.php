<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = ['winner_id', 'nomor_sertifikat', 'file_path', 'kode_verifikasi', 'generated_at'];

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Winner::class);
    }
}
