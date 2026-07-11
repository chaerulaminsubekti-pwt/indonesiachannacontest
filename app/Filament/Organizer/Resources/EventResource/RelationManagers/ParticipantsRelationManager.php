<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use App\Models\EventClass;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    protected static ?string $recordTitleAttribute = 'nama_peserta';

    public function form(Schema $schema): Schema
    {
        $event = $this->ownerRecord;

        return $schema
            ->columns(1)
            ->components([
                Select::make('event_class_id')
                    ->label('Kelas')
                    ->options(fn () => EventClass::where('event_id', $event?->id)->pluck('nama_kelas', 'id'))
                    ->required(),
                TextInput::make('nama_peserta')->required()->maxLength(255),
                TextInput::make('no_urut')->numeric()->label('No. Urut'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('class.nama_kelas')->label('Kelas'),
                TextColumn::make('nama_peserta')->searchable(),
                TextColumn::make('no_urut')->label('No. Urut'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->defaultSort('created_at');
    }
}
