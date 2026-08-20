<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Participant;
use App\Services\Peserta\TeamSingleCounter;
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
            ->orderByRaw('no_urut + 0')
            ->orderBy('id')
            ->get()
            ->groupBy('event_class_id');
    }

    public function getParticipantStatsProperty(): array
    {
        $all = $this->participants->flatten();

        return [
            'total' => $all->count(),
            'lunas' => $all->where('status', Participant::STATUS_LUNAS)->count(),
            'belum_lunas' => $all->where('status', '!=', Participant::STATUS_LUNAS)->count(),
        ];
    }

    public function getTeamSfStatsProperty(): array
    {
        return app(TeamSingleCounter::class)->count($this->participants->flatten());
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.daftar-peserta-publik', [
            'participantsByClass' => $this->participants,
            'participantStats' => $this->participantStats,
            'teamSfStats' => $this->teamSfStats,
        ]);
    }
}
