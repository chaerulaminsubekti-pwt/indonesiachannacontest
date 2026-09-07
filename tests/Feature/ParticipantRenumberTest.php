<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantRenumberTest extends TestCase
{
    use RefreshDatabase;

    private function makeClass(string $nama = 'Kelas A'): array
    {
        $event = Event::create([
            'nama_event' => 'Renumber '.$nama.uniqid(),
            'slug' => 'renumber-'.uniqid(),
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-11',
            'venue' => 'Venue',
            'kategori' => 'Mini Contest',
            'status' => 'approved',
        ]);
        $class = EventClass::create(['event_id' => $event->id, 'nama_kelas' => $nama]);

        return [$event, $class];
    }

    private function addParticipant(Event $event, EventClass $class, string $nama, int $no): Participant
    {
        return Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $class->id,
            'nama_peserta' => $nama,
            'no_urut' => $no,
            'status' => 'booking',
        ]);
    }

    private function numbers(Event $event, EventClass $class): array
    {
        return Participant::where('event_id', $event->id)
            ->where('event_class_id', $class->id)
            ->where('status', '!=', Participant::STATUS_REJECTED)
            ->orderByRaw('no_urut + 0')
            ->pluck('no_urut')
            ->map(fn ($n) => (int) $n)
            ->all();
    }

    public function test_delete_middle_shifts_numbers_up(): void
    {
        [$event, $class] = $this->makeClass();
        $keep1 = $this->addParticipant($event, $class, 'P1', 1);
        $mid = $this->addParticipant($event, $class, 'P2', 2);
        $keep3 = $this->addParticipant($event, $class, 'P3', 3);

        $mid->delete();

        $this->assertSame([1, 2], $this->numbers($event, $class));
        $this->assertSame(1, $keep1->fresh()->no_urut);
        $this->assertSame(2, $keep3->fresh()->no_urut);
        $this->assertSame('P3', $keep3->fresh()->nama_peserta);
    }

    public function test_delete_first_shifts_all_up(): void
    {
        [$event, $class] = $this->makeClass();
        $first = $this->addParticipant($event, $class, 'P1', 1);
        $this->addParticipant($event, $class, 'P2', 2);
        $this->addParticipant($event, $class, 'P3', 3);

        $first->delete();

        $this->assertSame([1, 2], $this->numbers($event, $class));
    }

    public function test_other_classes_untouched_and_rejected_ignored(): void
    {
        [$event, $classA] = $this->makeClass('A');
        $classB = EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'B']);
        $this->addParticipant($event, $classA, 'A1', 1);
        $del = $this->addParticipant($event, $classA, 'A2', 3);
        $this->addParticipant($event, $classB, 'B1', 1);
        $this->addParticipant($event, $classB, 'B2', 5);
        $rejected = $this->addParticipant($event, $classA, 'RX', 99);
        $rejected->update(['status' => Participant::STATUS_REJECTED]);

        $del->delete();

        $this->assertSame([1], $this->numbers($event, $classA));
        $this->assertSame([1, 5], $this->numbers($event, $classB));
    }

    public function test_reject_shifts_numbers_up(): void
    {
        [$event, $class] = $this->makeClass();
        $this->addParticipant($event, $class, 'P1', 1);
        $reject = $this->addParticipant($event, $class, 'P2', 2);
        $this->addParticipant($event, $class, 'P3', 3);

        $reject->update(['status' => Participant::STATUS_REJECTED]);

        $this->assertSame([1, 2], $this->numbers($event, $class));
    }
}
