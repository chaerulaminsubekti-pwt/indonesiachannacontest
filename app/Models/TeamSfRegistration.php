<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamSfRegistration extends Model
{
    public const TIPE_TEAM = 'team';

    public const TIPE_SINGLE_FIGHTER = 'single_fighter';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'event_id',
        'user_id',
        'tipe',
        'nama',
        'pic_name',
        'pic_wa',
        'pernyataan_sanggup',
        'signature_path',
        'status',
    ];

    protected $casts = [
        'pernyataan_sanggup' => 'boolean',
    ];

    public static function pernyataan(): string
    {
        return 'Kami menyatakan SANGSUP mengikuti ICC Series ini dan memenuhi syarat minimal di setiap event, '
            .'yaitu Team wajib membawa 20 ekor ikan dan Single Fighter wajib membawa 15 ekor ikan per event.';
    }

    public static function tipeLabel(?string $tipe): string
    {
        return $tipe === self::TIPE_SINGLE_FIGHTER ? 'Single Fighter' : 'Team';
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
