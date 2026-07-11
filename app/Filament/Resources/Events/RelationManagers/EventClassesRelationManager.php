<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Models\EventClass;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'classes';

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    protected static ?string $title = 'Kelas Ikan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kelas Ikan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                TextColumn::make('participants_count')
                    ->label('Jumlah Peserta')
                    ->counts('participants'),
                TextColumn::make('winners_count')
                    ->label('Jumlah Juara')
                    ->counts('winners'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at');
    }

    public static function getRelations(): array
    {
        return [
            EventClassPredikatsRelationManager::class,
        ];
    }
}