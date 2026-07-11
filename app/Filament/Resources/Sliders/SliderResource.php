<?php

namespace App\Filament\Resources\Sliders;

use App\Filament\Resources\Sliders\Pages\ManageSliders;
use App\Models\Slider;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Home';

    protected static ?string $navigationLabel = 'Gambar Slider';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')->required()->maxLength(255),
                FileUpload::make('gambar')->image()->required()->directory('sliders')->disk('public')->previewable(true),
                TextInput::make('link')->url()->maxLength(255),
                TextInput::make('urutan')->numeric()->default(0),
                Toggle::make('status_aktif')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('urutan')->sortable()->toggleable(),
                ImageColumn::make('gambar')->disk('public')->square()->height(60)->label('Foto'),
                TextColumn::make('judul')
                    ->searchable()
                    ->description(fn (Slider $record) => $record->link)
                    ->wrap(),
                IconColumn::make('status_aktif')->boolean()->label('Aktif'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('urutan')
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
            'index' => ManageSliders::route('/'),
        ];
    }
}
