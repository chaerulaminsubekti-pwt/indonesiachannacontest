<?php

namespace App\Filament\Resources\Events\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JudgesRelationManager extends RelationManager
{
    protected static string $relationship = 'judges';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $title = 'Juri Event';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pivot.urutan')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Juri')
                    ->searchable(),
                TextColumn::make('kota')
                    ->label('Kota'),
            ])
            ->defaultSort('event_judge.urutan')
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah Juri')
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()->label('Pilih Juri')->required(),
                        TextInput::make('urutan')
                            ->label('Urutan')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(1),
                    ]),
            ])
            ->recordActions([
                DetachAction::make()->label('Lepas'),
            ]);
    }
}
