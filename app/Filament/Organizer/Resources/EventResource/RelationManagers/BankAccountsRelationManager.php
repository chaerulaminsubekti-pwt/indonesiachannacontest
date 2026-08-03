<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BankAccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'bankAccounts';

    protected static ?string $title = 'Rekening Pembayaran';

    protected static ?string $recordTitleAttribute = 'nama_bank';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_bank')
                    ->label('Nama Bank')
                    ->required()->maxLength(100)
                    ->placeholder('Contoh: BCA, BRI, Mandiri'),
                TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->required()->maxLength(50)
                    ->placeholder('Contoh: 1234567890'),
                TextInput::make('atas_nama')
                    ->label('Atas Nama')
                    ->required()->maxLength(100),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bank')
                    ->label('Bank')
                    ->searchable(),
                TextColumn::make('nomor_rekening')
                    ->label('No. Rekening')
                    ->searchable(),
                TextColumn::make('atas_nama')
                    ->label('Atas Nama')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
