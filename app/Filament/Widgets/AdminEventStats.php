<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminEventStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Event', Event::count())
                ->icon('heroicon-o-calendar-days')
                ->color('info'),
            Stat::make('Disetujui / Berjalan', Event::whereIn('status', ['approved', 'berjalan'])->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pengajuan Baru', Event::where('status', 'pending')->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Selesai', Event::where('status', 'selesai')->count())
                ->icon('heroicon-o-flag')
                ->color('gray'),
            Stat::make('Ditolak', Event::where('status', 'rejected')->count())
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }

    protected function getColumns(): int
    {
        return 5;
    }
}
