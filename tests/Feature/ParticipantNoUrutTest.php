<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantNoUrutTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'nama_event' => 'Test Contest',
            'slug' => 'test-contest',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
            'venue' => 'Venue',
            'kategori' => 'regional',
            'status' => 'berjalan',
        ]);
    }

    private function makeClass(Event $event, string $nama): EventClass
    {
        return EventClass::create(['event_id' => $event->id, 'nama_kelas' => $nama, 'harga_tiket' => '100000.00']);
    }

    private function makeParticipant(Event $event, EventClass $class, string $nama, ?int $noUrut = null): Participant
    {
        $participant = Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $class->id,
            'nama_pemilik' => $nama,
            'status' => Participant::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        if ($noUrut !== null) {
            $participant->timestamps = false;
            $participant->no_urut = $noUrut;
            $participant->saveQuietly();
        }

        return $participant;
    }

    public function test_assign_no_urut_sets_free_number(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 1);

        $result = Participant::assignNoUrut($a, 5);

        $this->assertFalse($result['swapped']);
        $this->assertNull($result['opponent']);
        $this->assertSame(5, $a->fresh()->no_urut);
    }

    public function test_assign_no_urut_swaps_with_holder(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 1);
        $b = $this->makeParticipant($event, $class, 'B', 2);

        $result = Participant::assignNoUrut($a, 2);

        $this->assertTrue($result['swapped']);
        $this->assertSame(2, $a->fresh()->no_urut);
        $this->assertSame(1, $b->fresh()->no_urut);
    }

    public function test_assign_no_urut_gives_holder_free_number_when_participant_has_none(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 1);
        $a->timestamps = false;
        $a->no_urut = null;
        $a->saveQuietly();
        $b = $this->makeParticipant($event, $class, 'B', 3);

        $result = Participant::assignNoUrut($a, 3);

        $this->assertTrue($result['swapped']);
        $this->assertSame(3, $a->fresh()->no_urut);
        $this->assertSame(4, $b->fresh()->no_urut);
    }

    public function test_assign_no_urut_rejects_rejected_participant(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 1);
        $a->update(['status' => Participant::STATUS_REJECTED]);

        $result = Participant::assignNoUrut($a, 5);

        $this->assertFalse($result['swapped']);
        $this->assertNull($a->fresh()->no_urut);
    }

    public function test_observer_assigns_max_plus_one_on_create_without_renumbering(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 5);

        $b = Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $class->id,
            'nama_pemilik' => 'B',
            'status' => Participant::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $this->assertSame(5, $a->fresh()->no_urut);
        $this->assertSame(6, $b->fresh()->no_urut);
    }

    public function test_status_change_does_not_renumber_others(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');
        $a = $this->makeParticipant($event, $class, 'A', 1);
        $b = $this->makeParticipant($event, $class, 'B', 2);
        $c = $this->makeParticipant($event, $class, 'C', 3);

        $b->update(['status' => Participant::STATUS_REJECTED]);

        $this->assertSame(1, $a->fresh()->no_urut);
        $this->assertNull($b->fresh()->no_urut);
        $this->assertSame(3, $c->fresh()->no_urut);
    }

    public function test_class_change_assigns_valid_number_when_taken(): void
    {
        $event = $this->makeEvent();
        $class1 = $this->makeClass($event, 'Andrao');
        $class2 = $this->makeClass($event, 'Asiatica');
        $a = $this->makeParticipant($event, $class1, 'A', 5);
        $d = $this->makeParticipant($event, $class2, 'D', 5);

        $a->update(['event_class_id' => $class2->id]);

        $this->assertSame(5, $d->fresh()->no_urut);
        $this->assertSame(6, $a->fresh()->no_urut);
    }

    public function test_rejected_on_create_gets_null_number(): void
    {
        $event = $this->makeEvent();
        $class = $this->makeClass($event, 'Andrao');

        $a = Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $class->id,
            'nama_pemilik' => 'A',
            'status' => Participant::STATUS_REJECTED,
        ]);

        $this->assertNull($a->fresh()->no_urut);
    }
}
