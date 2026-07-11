<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Models\WinnerPredikat;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventClassPredikatsRelationManager extends RelationManager
{
    protected static string $relationship = 'predikats';

    protected static ?string $recordTitleAttribute = 'nama_predikat';

    protected static ?string $title = 'Predikat / Juara';

    protected static ?string $inverseRelationship = 'predikats';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_predikat')
                    ->required()
                    ->maxLength(100)
                    ->label('Nama Predikat / Juara')
                    ->placeholder('Contoh: Juara 1, Grand Champion Maruliodes, Best Single Fighter'),
                TextInput::make('urutan')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0)
                    ->helperText('Makin kecil makin atas (0 = paling atas)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_predikat')
                    ->label('Predikat / Juara')
                    ->searchable(),
                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('urutan');
    }
}