<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventCpsRelationManager extends RelationManager
{
    protected static string $relationship = 'cps';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $title = 'Contact Person';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nama')
                    ->label('Nama CP')
                    ->required()
                    ->maxLength(255),
                TextInput::make('no_wa')
                    ->label('Nomor WhatsApp')
                    ->placeholder('62812xxxxxxx')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->helperText('Gunakan format kode negara tanpa +/0, contoh: 628123456789'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->label('Nama CP'),
                TextColumn::make('no_wa')
                    ->label('Nomor WhatsApp')
                    ->copyable()
                    ->copyMessage('Nomor WA tersalin'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->label('Ditambahkan')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah CP'),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->defaultSort('created_at');
    }
}
