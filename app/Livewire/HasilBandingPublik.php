<?php

namespace App\Livewire;

use App\Models\Event;
use App\Services\Banding\BandingParser;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class HasilBandingPublik extends Component
{
    public Event $event;

    public string $sortDirection = 'desc';

    public ?string $error = null;

    public ?string $lastUpdated = null;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function toggleSort(): void
    {
        $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
    }

    public function refresh(): void
    {
        if (filled($this->event->banding_sheet_url)) {
            $parser = app(BandingParser::class);
            Cache::forget('banding_sheet:'.md5($parser->normalizeCsvUrl($this->event->banding_sheet_url, $this->event->banding_sheet_gid)));
            if ($gviz = $parser->gvizUrl($this->event->banding_sheet_url, $this->event->banding_sheet_gid)) {
                Cache::forget('banding_sheet:'.md5($gviz));
            }
        }
        $this->error = null;
    }

    public function getAppealsProperty(): ?array
    {
        if (! filled($this->event->banding_sheet_url)) {
            return null;
        }

        $parser = app(BandingParser::class);
        $csv = $parser->fetch($this->event->banding_sheet_url, $this->event->banding_sheet_gid);

        if ($csv === null) {
            $this->error = 'Gagal mengambil data dari Google Sheets. Pastikan link sudah benar dan sheet dibagikan sebagai "Anyone with the link" (Viewer) dengan izin download.';
            $this->lastUpdated = null;

            return null;
        }

        $this->error = null;
        $this->lastUpdated = now('Asia/Jakarta')->format('H:i:s');

        $parsed = $parser->parse($csv);

        return [
            'headers' => $parsed['headers'],
            'rows' => $parser->sortRows($parsed['rows'], $parsed['kelasIndex'], $parsed['timeIndex'], $this->sortDirection),
            'timeIndex' => $parsed['timeIndex'],
            'kelasIndex' => $parsed['kelasIndex'],
            'keputusanIndex' => $parsed['keputusanIndex'],
            'namaIndex' => $parsed['namaIndex'],
            'sortDirection' => $this->sortDirection,
        ];
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.hasil-banding-publik', [
            'appeals' => $this->appeals,
            'error' => $this->error,
        ]);
    }
}
