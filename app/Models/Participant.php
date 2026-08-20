<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

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

    public static function nextNoUrut(?int $eventId, ?int $classId): int
    {
        if (! $eventId || ! $classId) {
            return 1;
        }

        $max = self::query()
            ->where('event_id', $eventId)
            ->where('event_class_id', $classId)
            ->where('status', '!=', self::STATUS_REJECTED)
            ->max(DB::raw('no_urut + 0'));

        return (int) $max + 1;
    }

    public static function ensureNoUrut(Participant $participant): void
    {
        if ($participant->status === self::STATUS_REJECTED) {
            return;
        }

        $duplicate = self::query()
            ->where('event_id', $participant->event_id)
            ->where('event_class_id', $participant->event_class_id)
            ->where('status', '!=', self::STATUS_REJECTED)
            ->where('no_urut', $participant->no_urut)
            ->where('id', '!=', $participant->id)
            ->exists();

        if ($participant->no_urut === null || $duplicate) {
            $participant->timestamps = false;
            $participant->no_urut = self::nextNoUrut($participant->event_id, $participant->event_class_id);
            $participant->saveQuietly();
        }
    }

    /**
     * Tetapkan nomor urut untuk peserta. Jika nomor sudah dipakai peserta lain
     * di kelas yang sama (non-rejected), terjadi swap otomatis agar nomor tetap unik.
     */
    public static function assignNoUrut(Participant $participant, int $target): array
    {
        if ($target < 1 || $participant->status === self::STATUS_REJECTED) {
            return ['swapped' => false, 'opponent' => null];
        }

        return DB::transaction(function () use ($participant, $target): array {
            $holder = self::query()
                ->where('event_id', $participant->event_id)
                ->where('event_class_id', $participant->event_class_id)
                ->where('status', '!=', self::STATUS_REJECTED)
                ->where('no_urut', $target)
                ->where('id', '!=', $participant->id)
                ->lockForUpdate()
                ->first();

            $oldNumber = $participant->no_urut;

            $participant->timestamps = false;
            $participant->no_urut = $target;
            $participant->saveQuietly();

            if ($holder) {
                $holder->timestamps = false;
                $holder->no_urut = $oldNumber ?? self::nextNoUrut($participant->event_id, $participant->event_class_id);
                $holder->saveQuietly();

                return ['swapped' => true, 'opponent' => $holder];
            }

            return ['swapped' => false, 'opponent' => null];
        });
    }

    public static function renumberSequence(?int $eventId, ?int $classId): void
    {
        if (! $eventId || ! $classId) {
            return;
        }

        $number = 1;

        self::query()
            ->where('event_id', $eventId)
            ->where('event_class_id', $classId)
            ->where('status', '!=', self::STATUS_REJECTED)
            ->orderByRaw('no_urut IS NULL')
            ->orderByRaw('no_urut + 0')
            ->orderBy('id')
            ->get()
            ->each(function (self $participant) use (&$number): void {
                if ((int) $participant->no_urut !== $number) {
                    $participant->timestamps = false;
                    $participant->no_urut = $number;
                    $participant->saveQuietly();
                }

                $number++;
            });
    }

    public static function renumberAll(): int
    {
        $updated = 0;

        $classIds = self::query()
            ->whereNotNull('event_class_id')
            ->distinct()
            ->pluck('event_class_id');

        foreach ($classIds as $classId) {
            $eventId = self::query()
                ->where('event_class_id', $classId)
                ->value('event_id');

            if (! $eventId) {
                continue;
            }

            $before = self::query()
                ->where('event_id', $eventId)
                ->where('event_class_id', $classId)
                ->where('status', '!=', self::STATUS_REJECTED)
                ->whereNotNull('no_urut')
                ->pluck('no_urut')
                ->sort()
                ->values()
                ->toArray();

            self::renumberSequence((int) $eventId, (int) $classId);

            $after = self::query()
                ->where('event_id', $eventId)
                ->where('event_class_id', $classId)
                ->where('status', '!=', self::STATUS_REJECTED)
                ->pluck('no_urut')
                ->sort()
                ->values()
                ->toArray();

            if ($before !== $after) {
                $updated++;
            }
        }

        return $updated;
    }
}
