<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventClass extends Model
{
    protected $fillable = ['event_id', 'nama_kelas', 'harga_tiket', 'rekap_sheet_url'];

    protected static function booted(): void
    {
        static::created(function (EventClass $class) {
            foreach (self::getDefaultPredikats() as $predikat) {
                WinnerPredikat::create([
                    'event_class_id' => $class->id,
                    'nama_predikat' => $predikat['nama_predikat'],
                    'urutan' => $predikat['urutan'],
                ]);
            }
        });
    }

    public static function getDefaultKelasNames(): array
    {
        return [
            'Grand Champion Maruliodes',
            'Grand Champion Mini Dwarf',
            'Grand Champion Medium Dwarf',
            'Best Single Fighter',
            'Best Team',
            'Best Team Support',
            'Best Single Fighter Support',
            'Most Entry',
        ];
    }

    public static function getDefaultPredikats(): array
    {
        return [
            ['nama_predikat' => 'Juara 1', 'urutan' => 1],
            ['nama_predikat' => 'Juara 2', 'urutan' => 2],
            ['nama_predikat' => 'Juara 3', 'urutan' => 3],
            ['nama_predikat' => 'Juara 4', 'urutan' => 4],
            ['nama_predikat' => 'Juara 5', 'urutan' => 5],
        ];
    }

    public static function ensureDefaultClassesExist(int $eventId): void
    {
        $existing = self::where('event_id', $eventId)->pluck('nama_kelas')->toArray();
        foreach (self::getDefaultKelasNames() as $nama) {
            if (! in_array($nama, $existing)) {
                self::create(['event_id' => $eventId, 'nama_kelas' => $nama]);
            }
        }
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }

    public function predikats(): HasMany
    {
        return $this->hasMany(WinnerPredikat::class, 'event_class_id');
    }
}
