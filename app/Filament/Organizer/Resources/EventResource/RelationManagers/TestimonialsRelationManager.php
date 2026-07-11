<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TestimonialsRelationManager extends RelationManager
{
    protected static string $relationship = 'testimonial';

    protected static ?string $recordTitleAttribute = 'isi_testimoni';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $event = $ownerRecord;

        if ($event->status !== 'selesai') {
            return false;
        }

        if ($event->winners()->count() === 0) {
            return false;
        }

        if ($event->galleries()->count() === 0) {
            return false;
        }

        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Textarea::make('isi_testimoni')
                    ->label('Testimoni')
                    ->required()
                    ->maxLength(1000),
                Select::make('rating')
                    ->label('Rating')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('isi_testimoni')->limit(80)->label('Testimoni'),
                TextColumn::make('rating'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
