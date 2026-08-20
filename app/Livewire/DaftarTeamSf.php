<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\TeamSfRegistration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DaftarTeamSf extends Component
{
    public Event $event;

    public string $tipe = TeamSfRegistration::TIPE_TEAM;

    public string $nama = '';

    public string $pic_name = '';

    public string $pic_wa = '';

    public bool $sanggup = false;

    public string $signature = '';

    public bool $showSuccess = false;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function submit(): void
    {
        $this->validate($this->rules());

        $path = $this->storeSignature();
        if (! $path) {
            $this->addError('signature', 'Tanda tangan tidak valid, silakan ulangi.');

            return;
        }

        TeamSfRegistration::create([
            'event_id' => $this->event->id,
            'user_id' => auth()->id(),
            'tipe' => $this->tipe,
            'nama' => $this->nama,
            'pic_name' => $this->pic_name,
            'pic_wa' => $this->pic_wa,
            'pernyataan_sanggup' => true,
            'signature_path' => $path,
            'status' => TeamSfRegistration::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $this->showSuccess = true;
    }

    public function closeModal(): void
    {
        $this->showSuccess = false;
        $this->reset(['tipe', 'nama', 'pic_name', 'pic_wa', 'sanggup', 'signature']);
    }

    private function storeSignature(): ?string
    {
        if (! Str::startsWith($this->signature, 'data:image/png;base64,')) {
            return null;
        }

        $png = base64_decode(substr($this->signature, strlen('data:image/png;base64,')));
        if ($png === false || $png === '') {
            return null;
        }

        $name = 'ttd/'.Str::random(20).'.png';
        Storage::disk('public')->put($name, $png);

        return $name;
    }

    private function rules(): array
    {
        return [
            'tipe' => 'required|in:'.TeamSfRegistration::TIPE_TEAM.','.TeamSfRegistration::TIPE_SINGLE_FIGHTER,
            'nama' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'pic_wa' => 'required|string|max:20',
            'sanggup' => 'accepted',
            'signature' => 'required|string',
        ];
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.daftar-team-sf', [
            'pernyataan' => TeamSfRegistration::pernyataan(),
        ]);
    }
}
