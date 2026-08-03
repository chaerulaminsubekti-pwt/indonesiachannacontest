<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\EventClass;
use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ParticipantStatsOverview extends StatsOverviewWidget
{
    public ?int $event_id = null;

    protected function getStats(): array
    {
        if (! $this->event_id) {
            return [];
        }

        $query = fn () => Participant::where('event_id', $this->event_id);

        $totalIkan = $query()->count();
        $totalPeserta = $query()->select('nama_pemilik')->distinct()->count();
        $totalIkanTeam = $query()->whereNotNull('team_sf')->where('team_sf', '!=', '')->count();

        $stats = [
            Stat::make('Total Peserta', $totalPeserta)
                ->icon('heroicon-o-user-group')
                ->color('gray')
                ->description('Pemilik unik yang terdaftar'),
            Stat::make('Total Ikan', $totalIkan)
                ->icon('heroicon-o-squares-2x2')
                ->color('success')
                ->description('Seluruh pendaftaran ikan'),
            Stat::make('Team / Single Fighter', $totalIkanTeam)
                ->icon('heroicon-o-trophy')
                ->color('warning')
                ->description('Ikan dari team / single fighter'),
        ];

        $perClass = $query()
            ->selectRaw('event_class_id, count(*) as jumlah')
            ->groupBy('event_class_id')
            ->pluck('jumlah', 'event_class_id');

        EventClass::where('event_id', $this->event_id)
            ->orderBy('nama_kelas')
            ->get()
            ->each(function (EventClass $kelas) use (&$stats, $perClass): void {
                $stats[] = Stat::make($kelas->nama_kelas, (int) ($perClass[$kelas->id] ?? 0))
                    ->icon('heroicon-o-rectangle-stack')
                    ->color('gray');
            });

        return $stats;
    }
}
