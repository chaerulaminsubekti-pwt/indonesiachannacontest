<?php

namespace Tests\Feature;

use App\Livewire\RekapNilaiPublik;
use App\Models\Event;
use App\Models\EventClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RekapNilaiPublikTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'nama_event' => 'Rekap Tes',
            'slug' => 'rekap-tes-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ]);
    }

    public function test_rekap_classes_are_sorted_alphabetically(): void
    {
        $event = $this->makeEvent();

        foreach (['Yellow Progres', 'Andrao', 'Red Progres'] as $nama) {
            EventClass::create([
                'event_id' => $event->id,
                'nama_kelas' => $nama,
                'rekap_sheet_url' => 'https://docs.google.com/spreadsheets/d/abc123/edit',
            ]);
        }
        EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Tanpa Link']);

        $classes = Livewire::test(RekapNilaiPublik::class, ['event' => $event])
            ->viewData('classes');

        $this->assertSame(
            ['Andrao', 'Red Progres', 'Yellow Progres'],
            $classes->pluck('nama_kelas')->values()->all()
        );
    }

    public function test_refresh_loading_overlay_is_rendered(): void
    {
        $event = $this->makeEvent();
        EventClass::create([
            'event_id' => $event->id,
            'nama_kelas' => 'Andrao',
            'rekap_sheet_url' => 'https://docs.google.com/spreadsheets/d/abc123/edit',
        ]);

        Livewire::test(RekapNilaiPublik::class, ['event' => $event])
            ->assertSee('Memperbarui rekap nilai...', false)
            ->assertSee('wire:loading', false);
    }
}
