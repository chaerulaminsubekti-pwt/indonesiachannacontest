<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventSponsorsRelationManager extends RelationManager
{
    protected static string $relationship = 'sponsors';

    protected static ?string $title = 'Sponsor Event';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                FileUpload::make('logo_path')
                    ->image()
                    ->directory('event-sponsors')
                    ->disk('public')
                    ->maxSize(2048)
                    ->required()
                    ->label('Logo Sponsor'),
                TextInput::make('urutan')
                    ->numeric()
                    ->default(0)
                    ->label('Urutan Tampil')
                    ->helperText('Angka kecil tampil lebih dulu.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('logo_path')
                    ->disk('public')
                    ->square()
                    ->height(70)
                    ->label('Logo'),
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Sponsor'),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->defaultSort('urutan');
    }
}
