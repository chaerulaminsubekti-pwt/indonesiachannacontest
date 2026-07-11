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

class EventFlyersRelationManager extends RelationManager
{
    protected static string $relationship = 'flyers';

    protected static ?string $recordTitleAttribute = 'caption';

    protected static ?string $title = 'Flayer Kontes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                FileUpload::make('file_path')
                    ->image()
                    ->directory('events/flyers')
                    ->disk('public')
                    ->required()
                    ->label('Gambar Flayer'),
                TextInput::make('caption')
                    ->label('Nama Flayer')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('file_path')
                    ->disk('public')
                    ->square()
                    ->height(70)
                    ->label('Gambar'),
                TextColumn::make('caption')
                    ->searchable()
                    ->label('Nama Flayer'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Flayer'),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->defaultSort('created_at');
    }
}
