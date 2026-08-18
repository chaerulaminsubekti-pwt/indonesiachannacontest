<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventClass;
use App\Services\RekapNilai\RekapCalculator;
use App\Services\RekapNilai\SheetScoreParser;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RekapNilaiPublik extends Component
{
    public Event $event;

    public ?int $selectedClassId = null;

    public ?string $error = null;

    public ?string $lastUpdated = null;

    public function mount(Event $event): void
    {
        $this->event = $event;

        $first = $this->classesWithUrl->first();
        if ($first) {
            $this->selectedClassId = $first->id;
        }
    }

    public function getClassesWithUrlProperty()
    {
        return $this->event->classes
            ->filter(fn (EventClass $class): bool => filled($class->rekap_sheet_url))
            ->values();
    }

    public function getSelectedClassProperty(): ?EventClass
    {
        return $this->classesWithUrl->firstWhere('id', $this->selectedClassId)
            ?? $this->classesWithUrl->first();
    }

    public function selectClass(int $classId): void
    {
        $this->selectedClassId = $classId;
        $this->error = null;
    }

    public function refresh(): void
    {
        $class = $this->selectedClass;
        if ($class) {
            $parser = app(SheetScoreParser::class);
            Cache::forget('rekap_sheet:'.md5($parser->normalizeCsvUrl($class->rekap_sheet_url, $class->rekap_sheet_gid)));
        }
        $this->error = null;
    }

    public function getRecapProperty(): ?array
    {
        $class = $this->selectedClass;
        if (! $class) {
            return null;
        }

        $parser = app(SheetScoreParser::class);
        $csv = $parser->fetch($class->rekap_sheet_url, $class->rekap_sheet_gid);

        if ($csv === null) {
            $this->error = 'Gagal mengambil data dari Google Sheets. Pastikan link sudah benar dan sheet dibagikan sebagai "Anyone with the link" (Viewer).';
            $this->lastUpdated = null;

            return null;
        }

        $this->error = null;
        $this->lastUpdated = now()->format('H:i:s');

        $parsed = $parser->parse($csv);

        return app(RekapCalculator::class)->calculate($parsed);
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.rekap-nilai-publik', [
            'classes' => $this->classesWithUrl,
            'selectedClass' => $this->selectedClass,
            'recap' => $this->recap,
            'error' => $this->error,
        ]);
    }
}
