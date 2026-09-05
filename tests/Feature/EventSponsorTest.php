<?php

namespace Tests\Feature;

use App\Filament\Organizer\Resources\EventResource;
use App\Models\Event;
use App\Models\EventSponsor;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventSponsorTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'nama_event' => 'Kontes Sponsor',
            'slug' => 'kontes-sponsor-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ], $overrides));
    }

    public function test_event_has_sponsors_relation_ordered(): void
    {
        $event = $this->makeEvent();
        EventSponsor::create(['event_id' => $event->id, 'logo_path' => 'event-sponsors/b.png', 'urutan' => 2]);
        EventSponsor::create(['event_id' => $event->id, 'logo_path' => 'event-sponsors/a.png', 'urutan' => 1]);

        $this->assertCount(2, $event->sponsors()->get());
        $this->assertSame('event-sponsors/a.png', $event->fresh()->sponsors->first()->logo_path);
    }

    public function test_public_event_page_shows_sponsor_strip(): void
    {
        $event = $this->makeEvent();
        EventSponsor::create(['event_id' => $event->id, 'logo_path' => 'event-sponsors/logo.png']);

        $response = $this->get(route('event.show', $event->slug));

        $response->assertOk();
        $response->assertSee('Sponsor', false);
        $response->assertSee('event-sponsors/logo.png', false);
    }

    public function test_public_event_page_hides_section_without_sponsors(): void
    {
        $event = $this->makeEvent();

        $response = $this->get(route('event.show', $event->slug));

        $response->assertOk();
        $response->assertDontSee('event-sponsors/', false);
    }

    public function test_organizer_scoping_blocks_other_event(): void
    {
        $owner = User::create([
            'name' => 'Owner', 'email' => 'owner@icc.test', 'username' => 'owner',
            'password' => 'password123', 'role' => 'penyelenggara', 'status' => 'active',
        ]);
        $other = User::create([
            'name' => 'Other', 'email' => 'other@icc.test', 'username' => 'other',
            'password' => 'password123', 'role' => 'penyelenggara', 'status' => 'active',
        ]);
        $ownerOrg = Organizer::create(['user_id' => $owner->id, 'nama_organisasi' => 'Org Owner', 'no_wa' => '0851000001']);
        $otherOrg = Organizer::create(['user_id' => $other->id, 'nama_organisasi' => 'Org Other', 'no_wa' => '0851000002']);
        $ownEvent = $this->makeEvent(['organizer_id' => $ownerOrg->id, 'slug' => 'own-'.uniqid()]);
        $otherEvent = $this->makeEvent(['organizer_id' => $otherOrg->id, 'slug' => 'other-'.uniqid()]);

        $this->actingAs($owner);

        $query = EventResource::getEloquentQuery();

        $this->assertTrue((clone $query)->whereKey($ownEvent->id)->exists());
        $this->assertFalse((clone $query)->whereKey($otherEvent->id)->exists());
    }
}
