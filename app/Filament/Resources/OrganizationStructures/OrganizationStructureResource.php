<?php

namespace App\Filament\Resources\OrganizationStructures;

use App\Filament\Resources\OrganizationStructures\Pages\ManageOrganizationStructures;
use App\Models\OrganizationStructure;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrganizationStructureResource extends Resource
{
    protected static ?string $model = OrganizationStructure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Dokumen';

    protected static ?string $navigationLabel = 'Struktur Organisasi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('File Struktur Organisasi')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->required()
                    ->directory('documents/struktur'),
                Select::make('tipe')->options([
                    'pdf' => 'PDF',
                    'jpg' => 'JPG',
                    'png' => 'PNG',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipe')->badge(),
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
            'index' => ManageOrganizationStructures::route('/'),
        ];
    }
}
