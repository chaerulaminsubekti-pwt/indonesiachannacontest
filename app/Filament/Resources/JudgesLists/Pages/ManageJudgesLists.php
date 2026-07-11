<?php

namespace App\Filament\Resources\JudgesLists\Pages;

use App\Filament\Resources\JudgesLists\JudgesListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJudgesLists extends ManageRecords
{
    protected static string $resource = JudgesListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
