<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class EventClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'classes';

    protected static ?string $title = 'Kelas Kontes';

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()->maxLength(100),
                TextInput::make('harga_tiket')
                    ->label('Harga Tiket')
                    ->numeric()->prefix('Rp')->step(1000)
                    ->placeholder('Contoh: 250000')
                    ->helperText('Kosongkan jika gratis'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                TextColumn::make('harga_tiket')
                    ->label('Harga Tiket')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Gratis'),
                TextColumn::make('participants_count')
                    ->label('Jumlah Peserta')
                    ->counts('participants')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('delete-all')
                    ->label('Hapus Semua')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $count = $records->count();
                        $records->each->delete();
                        Notification::make()
                            ->title("{$count} kelas berhasil dihapus.")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
