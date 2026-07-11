<?php

namespace App\Filament\Resources\IccGalleries;

use App\Filament\Resources\IccGalleries\Pages\ManageIccGalleries;
use App\Models\IccGallery;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IccGalleryResource extends Resource
{
    protected static ?string $model = IccGallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Home';

    protected static ?string $navigationLabel = 'Dokumentasi Event';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul_album')->required()->maxLength(255),
                FileUpload::make('file_path')->image()->required()->directory('icc-galleries')->disk('public'),
                Textarea::make('caption'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('file_path')->square()->height(50)->label('Foto'),
                TextColumn::make('judul_album')
                    ->searchable()
                    ->description(fn (IccGallery $record) => $record->caption)
                    ->wrap(),
                TextColumn::make('caption')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
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
            'index' => ManageIccGalleries::route('/'),
        ];
    }
}
