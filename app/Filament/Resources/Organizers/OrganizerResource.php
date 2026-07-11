<?php

namespace App\Filament\Resources\Organizers;

use App\Filament\Resources\Organizers\Pages\ManageOrganizers;
use App\Models\Organizer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizerResource extends Resource
{
    protected static ?string $model = Organizer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Event';

    protected static ?string $navigationLabel = 'Penyelenggara';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_organisasi')->required()->maxLength(255),
                TextInput::make('jabatan_pic')->maxLength(255),
                TextInput::make('no_wa')->required()->maxLength(20),
                TextInput::make('no_ktp')->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('nama_organisasi')
                    ->searchable()
                    ->description(fn (Organizer $record) => $record->user?->name)
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('PIC')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('no_wa')
                    ->label('WhatsApp')
                    ->toggleable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('events_count')
                    ->label('Event')
                    ->counts('events')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrganizers::route('/'),
        ];
    }
}
