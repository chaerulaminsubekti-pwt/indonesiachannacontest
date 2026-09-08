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
        $rows = $parser->sortRows($parsed['rows'], $parsed['kelasIndex'], $parsed['timeIndex'], $this->sortDirection);

        $grouped = [];
        foreach ($rows as $row) {
            $kelas = $parsed['kelasIndex'] !== null ? trim((string) ($row[$parsed['kelasIndex']] ?? '')) : '';
            $grouped[$kelas !== '' ? $kelas : 'Tanpa Kelas'][] = $row;
        }

        return [
            'headers' => $parsed['headers'],
            'rows' => $rows,
            'grouped' => $grouped,
            'timeIndex' => $parsed['timeIndex'],
            'kelasIndex' => $parsed['kelasIndex'],
            'keputusanIndex' => $parsed['keputusanIndex'],
            'namaIndex' => $parsed['namaIndex'],
            'sortDirection' => $this->sortDirection,
        ];
    }

    /**
     * Ubah URL video menjadi URL embed yang bisa diputar inline.
     * Mendukung Google Drive, YouTube, dan file mp4 langsung.
     */
    public function videoEmbedUrl(string $url): ?array
    {
        $url = trim($url);

        if ($url === '' || ! str_starts_with($url, 'http')) {
            return null;
        }

        if (preg_match('#drive\.google\.com/(?:open\?id=|file/d/)([a-zA-Z0-9_-]+)#', $url, $m)) {
            return ['type' => 'iframe', 'src' => 'https://drive.google.com/file/d/'.$m[1].'/preview'];
        }

        if (preg_match('#(?:youtube\.com/(?:watch\?v=|shorts/)|youtu\.be/)([a-zA-Z0-9_-]{6,})#', $url, $m)) {
            return ['type' => 'iframe', 'src' => 'https://www.youtube.com/embed/'.$m[1]];
        }

        if (preg_match('#\.mp4(\?.*)?$#i', $url)) {
            return ['type' => 'video', 'src' => $url];
        }

        return null;
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
