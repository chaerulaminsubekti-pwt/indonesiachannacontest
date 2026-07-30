<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'nama_event', 'slug', 'tanggal_mulai', 'tanggal_selesai',
        'venue', 'kategori', 'tema', 'wilayah_kota', 'flyer', 'deskripsi', 'status', 'google_sheet_url', 'no_wa_cp',
    ];

    protected static function booted(): void
    {
        static::updated(function (Event $event) {
            $user = $event->organizer?->user;
            if (! $user) {
                return;
            }

            $originalStatus = $event->getOriginal('status');

            if ($originalStatus === 'pending' && $event->status === 'approved') {
                // Aktivasi user otomatis saat event disetujui
                $user->update([
                    'status' => 'active',
                    'activation_token' => null,
                    'activated_at' => now(),
                ]);
            }
        });
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(EventClass::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(EventGallery::class);
    }

    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(Judge::class, 'event_judge')
            ->withPivot('urutan');
    }

    public function testimonial(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function flyers(): HasMany
    {
        return $this->hasMany(EventFlyer::class);
    }

    public function cps(): HasMany
    {
        return $this->hasMany(EventCp::class);
    }
}
