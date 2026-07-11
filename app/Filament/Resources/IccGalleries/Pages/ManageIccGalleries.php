<?php

namespace App\Filament\Resources\IccGalleries\Pages;

use App\Filament\Resources\IccGalleries\IccGalleryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageIccGalleries extends ManageRecords
{
    protected static string $resource = IccGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
