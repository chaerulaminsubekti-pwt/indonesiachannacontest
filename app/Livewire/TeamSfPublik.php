<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\TeamSfRegistration;
use Livewire\Component;

class TeamSfPublik extends Component
{
    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function render()
    {
        $registrations = TeamSfRegistration::query()
            ->where('event_id', $this->event->id)
            ->orderByDesc('created_at')
            ->get();

        $teams = $registrations->where('tipe', TeamSfRegistration::TIPE_TEAM)->values();
        $singleFighters = $registrations->where('tipe', TeamSfRegistration::TIPE_SINGLE_FIGHTER)->values();

        return view('livewire.team-sf-publik', [
            'teams' => $teams,
            'singleFighters' => $singleFighters,
        ]);
    }
}
