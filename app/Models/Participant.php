<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    public const STATUS_MENUNGGU_BAYAR = 'menunggu_bayar';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_LUNAS = 'lunas';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'event_id',
        'event_class_id',
        'user_id',
        'nama_pemilik',
        'team_sf',
        'nama_ikan',
        'jenis_ikan',
        'kota_asal',
        'alamat',
        'no_hp',
        'status',
        'bukti_pembayaran',
        'biaya',
        'dp_amount',
        'no_urut',
        'fishin',
        'fishout',
        'nama_peserta',
        'keterangan',
    ];

    protected $casts = [
        'event_class_id' => 'integer',
        'no_urut' => 'integer',
        'biaya' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'fishin' => 'boolean',
        'fishout' => 'boolean',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_MENUNGGU_BAYAR => 'Menunggu Bayar',
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::STATUS_LUNAS => 'Lunas',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(EventClass::class, 'event_class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function getNamaTampilAttribute(): string
    {
        return $this->nama_pemilik ?: $this->nama_peserta;
    }

    public function getKeteranganTampilAttribute(): string
    {
        if ($this->keterangan) {
            return $this->keterangan;
        }

        return self::statuses()[$this->status] ?? ucfirst((string) $this->status);
    }
}
