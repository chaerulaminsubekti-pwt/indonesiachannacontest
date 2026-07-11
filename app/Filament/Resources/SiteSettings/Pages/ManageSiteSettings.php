<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class ManageSiteSettings extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected static ?string $title = 'Pengaturan Situs';

    public function mount(int|string|null $record = null): void
    {
        $record = SiteSetting::firstOrCreate()->id;
        parent::mount($record);
    }

    protected function getSaveNotification(): ?Notification
    {
        return Notification::make()->success()->title('Pengaturan berhasil disimpan');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }
}
