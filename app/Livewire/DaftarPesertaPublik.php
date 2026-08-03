<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Participant;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DaftarPesertaPublik extends Component
{
    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function getParticipantsProperty()
    {
        $pending = Participant::where('event_id', $this->event->id)
            ->where('status', '!=', Participant::STATUS_LUNAS)
            ->where('status', '!=', Participant::STATUS_REJECTED)
            ->with('class')
            ->orderBy('no_urut')
            ->get();

        $lunas = Participant::where('event_id', $this->event->id)
            ->where('status', Participant::STATUS_LUNAS)
            ->with('class')
            ->orderBy('no_urut')
            ->get();

        return $pending->concat($lunas)->groupBy('event_class_id');
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.daftar-peserta-publik', [
            'participantsByClass' => $this->participants,
        ]);
    }
}
