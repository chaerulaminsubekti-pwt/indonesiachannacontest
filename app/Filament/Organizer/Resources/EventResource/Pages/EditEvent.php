<?php

namespace App\Filament\Organizer\Resources\EventResource\Pages;

use App\Filament\Organizer\Resources\EventResource;
use App\Services\Export\ParticipantExcelExport;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Export Data Peserta (Excel)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn (ParticipantExcelExport $exporter) => $exporter->download($this->record)),
            Actions\DeleteAction::make(),
        ];
    }
}
