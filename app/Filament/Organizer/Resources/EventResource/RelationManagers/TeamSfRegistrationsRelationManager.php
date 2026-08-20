<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use App\Models\TeamSfRegistration;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TeamSfRegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'teamSfRegistrations';

    protected static ?string $title = 'Team / SF';

    protected static ?string $recordTitleAttribute = 'nama';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nama')->label('Nama Team / SF')->required()->maxLength(255),
                TextInput::make('pic_name')->label('Nama PIC')->required()->maxLength(255),
                TextInput::make('pic_wa')->label('Nomor WA PIC')->required()->maxLength(20),
                Toggle::make('pernyataan_sanggup')->label('Sanggup')->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TeamSfRegistration::tipeLabel($state))
                    ->color(fn (string $state): string => $state === TeamSfRegistration::TIPE_TEAM ? 'danger' : 'info'),
                TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                TextColumn::make('pic_name')->label('PIC')->searchable(),
                TextColumn::make('pic_wa')->label('No. WA')->searchable(),
                TextColumn::make('pernyataan_sanggup')
                    ->label('Sanggup')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak')
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                ImageColumn::make('signature_path')
                    ->label('Tanda Tangan')
                    ->disk('public')
                    ->height(40)
                    ->width(100)
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TeamSfRegistration::statuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        TeamSfRegistration::STATUS_APPROVED => 'success',
                        TeamSfRegistration::STATUS_MENUNGGU_VERIFIKASI => 'warning',
                        TeamSfRegistration::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Daftar')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->options([
                        TeamSfRegistration::TIPE_TEAM => 'Team',
                        TeamSfRegistration::TIPE_SINGLE_FIGHTER => 'Single Fighter',
                    ]),
                SelectFilter::make('status')
                    ->options(TeamSfRegistration::statuses()),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('view_signature')
                    ->label('Lihat Tanda Tangan')
                    ->icon('heroicon-o-pencil')
                    ->modalWidth(Width::Large)
                    ->modalContent(fn (TeamSfRegistration $record) => view('filament.organizer.team-sf-signature', ['record' => $record])),
                Action::make('approve')
                    ->label('Terima')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (TeamSfRegistration $record) => $record->status !== TeamSfRegistration::STATUS_APPROVED)
                    ->requiresConfirmation()
                    ->action(function (TeamSfRegistration $record): void {
                        $record->update(['status' => TeamSfRegistration::STATUS_APPROVED]);
                        Notification::make()
                            ->title('Pendaftaran "'.$record->nama.'" diterima.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (TeamSfRegistration $record) => $record->status !== TeamSfRegistration::STATUS_REJECTED)
                    ->requiresConfirmation()
                    ->action(function (TeamSfRegistration $record): void {
                        $record->update(['status' => TeamSfRegistration::STATUS_REJECTED]);
                        Notification::make()
                            ->title('Pendaftaran "'.$record->nama.'" ditolak.')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->iconButton()
                    ->requiresConfirmation(),
            ]);
    }
}
