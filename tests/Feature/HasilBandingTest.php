<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HasilBandingTest extends TestCase
{
    use RefreshDatabase;

    protected function csv(): string
    {
        return implode("\n", [
            '"Timestamp","Email Address","Nama Peserta","Kelas","No Tank","Alasan"',
            '"9/8/2026 14:28:27","a@example.com","Chaerul","Yellow Progress","1","Mental"',
        ]);
    }

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'nama_event' => 'Banding Tes',
            'slug' => 'banding-tes-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ], $overrides));
    }

    public function test_tab_hidden_without_sheet_url(): void
    {
        $event = $this->makeEvent();

        $response = $this->get(route('event.show', $event->slug));

        $response->assertOk();
        $response->assertDontSee('Hasil Banding', false);
    }

    public function test_tab_shows_appeals_and_hides_email(): void
    {
        Http::fake(['*' => Http::response($this->csv(), 200)]);

        $event = $this->makeEvent(['banding_sheet_url' => 'https://docs.google.com/spreadsheets/d/abc123/edit']);

        $response = $this->get(route('event.show', $event->slug));

        $response->assertOk();
        $response->assertSee('Hasil Banding', false);
        $response->assertSee('Chaerul', false);
        $response->assertSee('Yellow Progress', false);
        $response->assertDontSee('a@example.com', false);
    }
}
