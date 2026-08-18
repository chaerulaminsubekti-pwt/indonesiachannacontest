<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class EventClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'classes';

    protected static ?string $title = 'Kelas Kontes';

    protected static ?string $recordTitleAttribute = 'nama_kelas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()->maxLength(100),
                TextInput::make('harga_tiket')
                    ->label('Harga Tiket')
                    ->numeric()->prefix('Rp')->step(1000)
                    ->placeholder('Contoh: 250000')
                    ->helperText('Kosongkan jika gratis'),
                TextInput::make('rekap_sheet_url')
                    ->label('Link Google Sheets (Rekap Nilai)')
                    ->placeholder('https://docs.google.com/spreadsheets/d/.../edit')
                    ->helperText('Tempel link utama sheet (satu link bisa dipakai untuk semua kelas). Sheet harus dibagikan sebagai "Anyone with the link" (Viewer).'),
                TextInput::make('rekap_sheet_gid')
                    ->label('Tab Kelas (gid)')
                    ->numeric()
                    ->placeholder('Contoh: 2062756512')
                    ->helperText('Klik tab kelas pada sheet (mis. "Yellow Progres"), salin angka setelah "#gid=" di URL browser lalu isi di sini. Kosongkan untuk memakai tab pertama (gid 0).'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                TextColumn::make('harga_tiket')
                    ->label('Harga Tiket')
                    ->money('IDR')
                    ->sortable()
                    ->placeholder('Gratis'),
                TextColumn::make('participants_count')
                    ->label('Jumlah Peserta')
                    ->counts('participants')
                    ->sortable(),
                TextColumn::make('rekap_sheet_url')
                    ->label('Rekap Online')
                    ->formatStateUsing(function ($record): string {
                        if (! filled($record->rekap_sheet_url)) {
                            return 'Belum';
                        }

                        return filled($record->rekap_sheet_gid)
                            ? 'Terpasang (gid '.$record->rekap_sheet_gid.')'
                            : 'Terpasang (tab 1)';
                    })
                    ->badge()
                    ->color(fn ($record): string => filled($record->rekap_sheet_url) ? 'success' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('delete-all')
                    ->label('Hapus Semua')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $count = $records->count();
                        $records->each->delete();
                        Notification::make()
                            ->title("{$count} kelas berhasil dihapus.")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
