<?php

namespace App\Filament\Resources\Regulations;

use App\Filament\Resources\Regulations\Pages\ManageRegulations;
use App\Models\Regulation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegulationResource extends Resource
{
    protected static ?string $model = Regulation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Dokumen';

    protected static ?string $navigationLabel = 'Regulasi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama File')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_path')
                    ->label('File PDF')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required()
                    ->directory('documents/regulasi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama File')->searchable()->sortable(),
                TextColumn::make('file_path')->label('File')->limit(40),
                TextColumn::make('updated_at')->dateTime('d M Y')->label('Terakhir Update'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ManageRegulations::route('/'),
        ];
    }
}
