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
        return Participant::where('event_id', $this->event->id)
            ->where('status', '!=', Participant::STATUS_REJECTED)
            ->with('class')
            ->orderByRaw('no_urut IS NULL')
            ->orderBy('no_urut')
            ->orderBy('id')
            ->get()
            ->groupBy('event_class_id');
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.daftar-peserta-publik', [
            'participantsByClass' => $this->participants,
        ]);
    }
}
