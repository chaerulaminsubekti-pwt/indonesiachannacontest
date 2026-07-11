<?php

namespace App\Filament\Organizer\Widgets;

use App\Models\Event;
use App\Models\Organizer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();
        $events = Event::where('organizer_id', $organizer?->id);

        return [
            Stat::make('Total Event', $events->count())
                ->icon('heroicon-o-calendar')
                ->color('gray'),
            Stat::make('Disetujui', (clone $events)->whereIn('status', ['approved', 'berjalan', 'selesai'])->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pending', (clone $events)->where('status', 'pending')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Ditolak', (clone $events)->where('status', 'rejected')->count())
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
