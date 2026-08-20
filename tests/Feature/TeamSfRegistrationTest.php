<?php

namespace Tests\Feature;

use App\Livewire\DaftarTeamSf;
use App\Livewire\TeamSfPublik;
use App\Models\Event;
use App\Models\TeamSfRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TeamSfRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(string $kategori = 'series_icc', string $status = 'berjalan'): Event
    {
        return Event::create([
            'nama_event' => 'ICC Series #1',
            'slug' => 'icc-series-1',
            'tanggal_mulai' => '2026-08-20',
            'tanggal_selesai' => '2026-08-21',
            'venue' => 'Venue',
            'kategori' => $kategori,
            'status' => $status,
        ]);
    }

    public function test_registration_is_stored_with_signature_file(): void
    {
        Storage::fake('public');

        $event = $this->makeEvent();

        Livewire::test(DaftarTeamSf::class, ['event' => $event])
            ->set('tipe', TeamSfRegistration::TIPE_TEAM)
            ->set('nama', 'Gogo Team')
            ->set('pic_name', 'Fadil')
            ->set('pic_wa', '085608717174')
            ->set('sanggup', true)
            ->set('signature', 'data:image/png;base64,'.base64_encode('fake-png-bytes'))
            ->call('submit');

        $this->assertDatabaseHas('team_sf_registrations', [
            'event_id' => $event->id,
            'tipe' => TeamSfRegistration::TIPE_TEAM,
            'nama' => 'Gogo Team',
            'pic_name' => 'Fadil',
            'pic_wa' => '085608717174',
            'pernyataan_sanggup' => true,
            'status' => TeamSfRegistration::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $registration = TeamSfRegistration::first();
        Storage::disk('public')->assertExists($registration->signature_path);
    }

    public function test_registration_requires_commitment_and_signature(): void
    {
        $event = $this->makeEvent();

        Livewire::test(DaftarTeamSf::class, ['event' => $event])
            ->set('tipe', TeamSfRegistration::TIPE_TEAM)
            ->set('nama', 'Gogo Team')
            ->set('pic_name', 'Fadil')
            ->set('pic_wa', '085608717174')
            ->set('sanggup', false)
            ->set('signature', '')
            ->call('submit')
            ->assertHasErrors(['sanggup', 'signature']);

        $this->assertDatabaseCount('team_sf_registrations', 0);
    }

    public function test_registration_rejects_invalid_signature_prefix(): void
    {
        Storage::fake('public');

        $event = $this->makeEvent();

        Livewire::test(DaftarTeamSf::class, ['event' => $event])
            ->set('tipe', TeamSfRegistration::TIPE_SINGLE_FIGHTER)
            ->set('nama', 'Andra')
            ->set('pic_name', 'Budi')
            ->set('pic_wa', '081276250269')
            ->set('sanggup', true)
            ->set('signature', 'not-a-png')
            ->call('submit')
            ->assertHasErrors('signature');

        $this->assertDatabaseCount('team_sf_registrations', 0);
        Storage::disk('public')->assertDirectoryEmpty('ttd');
    }

    public function test_public_list_shows_registrations_grouped_by_type(): void
    {
        Storage::fake('public');

        $event = $this->makeEvent();
        TeamSfRegistration::create([
            'event_id' => $event->id,
            'tipe' => TeamSfRegistration::TIPE_TEAM,
            'nama' => 'Gogo Team',
            'pic_name' => 'Fadil',
            'pic_wa' => '085608717174',
            'pernyataan_sanggup' => true,
            'signature_path' => 'ttd/team.png',
            'status' => TeamSfRegistration::STATUS_APPROVED,
        ]);
        TeamSfRegistration::create([
            'event_id' => $event->id,
            'tipe' => TeamSfRegistration::TIPE_SINGLE_FIGHTER,
            'nama' => 'Andra',
            'pic_name' => 'Budi',
            'pic_wa' => '081276250269',
            'pernyataan_sanggup' => true,
            'signature_path' => 'ttd/sf.png',
            'status' => TeamSfRegistration::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        Livewire::test(TeamSfPublik::class, ['event' => $event])
            ->assertSee('Gogo Team')
            ->assertSee('Andra');
    }

    public function test_series_event_page_shows_team_sf_form_and_tab(): void
    {
        $event = $this->makeEvent();

        $this->get(route('event.show', $event->slug))
            ->assertOk()
            ->assertSee('Pendaftaran Team / SF')
            ->assertSee('Team / SF');
    }

    public function test_non_series_event_hides_team_sf_form(): void
    {
        $event = $this->makeEvent('regional');

        $this->get(route('event.show', $event->slug))
            ->assertOk()
            ->assertDontSee('Pendaftaran Team / SF');
    }

    public function test_pernyataan_mentions_minimum_requirements(): void
    {
        $text = TeamSfRegistration::pernyataan();
        $this->assertStringContainsString('20 ekor ikan', $text);
        $this->assertStringContainsString('15 ekor ikan', $text);
    }
}
