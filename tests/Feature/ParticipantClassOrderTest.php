<?php

namespace Tests\Feature;

use App\Livewire\DaftarPesertaPublik;
use App\Models\Event;
use App\Models\EventClass;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ParticipantClassOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_classes_are_sorted_alphabetically(): void
    {
        $event = Event::create([
            'nama_event' => 'Urut Abjad',
            'slug' => 'urut-abjad-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ]);

        foreach (['Yellow Progres', 'Andrao', 'Limbata Beginner'] as $nama) {
            $class = EventClass::create(['event_id' => $event->id, 'nama_kelas' => $nama]);
            Participant::create([
                'event_id' => $event->id,
                'event_class_id' => $class->id,
                'nama_peserta' => 'Peserta '.$nama,
                'status' => 'booking',
            ]);
        }

        $grouped = Livewire::test(DaftarPesertaPublik::class, ['event' => $event])
            ->viewData('participantsByClass');

        $names = $grouped->map(fn ($list) => $list->first()->class->nama_kelas)->values()->all();

        $this->assertSame(['Andrao', 'Limbata Beginner', 'Yellow Progres'], $names);
    }

    public function test_participant_order_within_class_is_unchanged(): void
    {
        $event = Event::create([
            'nama_event' => 'Urut Dalam Kelas',
            'slug' => 'urut-dalam-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ]);
        $class = EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Andrao']);

        foreach ([3, 1, 2] as $no) {
            Participant::create([
                'event_id' => $event->id,
                'event_class_id' => $class->id,
                'nama_peserta' => 'P'.$no,
                'no_urut' => (string) $no,
                'status' => 'booking',
            ]);
        }

        $grouped = Livewire::test(DaftarPesertaPublik::class, ['event' => $event])
            ->viewData('participantsByClass');

        $names = $grouped->first()->pluck('nama_peserta')->all();

        $this->assertSame(['P1', 'P2', 'P3'], $names);
    }
}
