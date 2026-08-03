<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use App\Filament\Organizer\Widgets\ParticipantStatsOverview;
use App\Jobs\GenerateParticipantCertificateJob;
use App\Models\EventClass;
use App\Models\Participant;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    protected static ?string $title = 'Peserta';

    protected static ?string $recordTitleAttribute = 'nama_pemilik';

    public function form(Schema $schema): Schema
    {
        $event = $this->ownerRecord;

        return $schema
            ->columns(2)
            ->components([
                Select::make('event_class_id')
                    ->label('Kelas')
                    ->options(fn () => EventClass::where('event_id', $event?->id)->pluck('nama_kelas', 'id'))
                    ->required(),
                TextInput::make('no_urut')
                    ->label('No. Urut')
                    ->numeric(),
                TextInput::make('nama_pemilik')->label('Nama Pemilik')->required()->maxLength(255),
                TextInput::make('team_sf')->label('Team / SF')->maxLength(255),
                TextInput::make('nama_ikan')->label('Nama Ikan')->maxLength(255),
                TextInput::make('kota_asal')->label('Kota Asal')->maxLength(255),
                TextInput::make('no_hp')->label('No. HP')->maxLength(20),
                TextInput::make('biaya')->label('Biaya')->numeric()->prefix('Rp')->step(1000),
                Select::make('status')
                    ->label('Status')
                    ->options(Participant::statuses())
                    ->default(Participant::STATUS_MENUNGGU_VERIFIKASI),
                FileUpload::make('bukti_pembayaran')
                    ->label('Bukti Transfer')
                    ->image()
                    ->disk('public')
                    ->directory('bukti-pembayaran')
                    ->openable()
                    ->previewable()
                    ->imageEditor(),
                Toggle::make('fishin')->label('Fishin'),
                Toggle::make('fishout')->label('Fishout'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('no_urut')->label('No. Urut')->sortable(),
                TextColumn::make('nama_pemilik')->label('Nama')->searchable()->sortable(),
                TextColumn::make('class.nama_kelas')->label('Kelas'),
                TextColumn::make('team_sf')->label('Team / SF'),
                TextColumn::make('nama_ikan')->label('Nama Ikan'),
                TextColumn::make('no_hp')->label('No. HP')->searchable(),
                TextColumn::make('dp_amount')
                    ->label('DP (Rp)')
                    ->money('IDR')
                    ->placeholder('-')
                    ->default(0)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Participant::STATUS_LUNAS => 'success',
                        Participant::STATUS_MENUNGGU_VERIFIKASI => 'warning',
                        Participant::STATUS_MENUNGGU_BAYAR => 'gray',
                        Participant::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                ToggleColumn::make('fishin')->label('Fishin'),
                ToggleColumn::make('fishout')->label('Fishout'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Participant::statuses()),
                SelectFilter::make('event_class_id')
                    ->label('Kelas')
                    ->options(fn () => EventClass::where('event_id', $this->ownerRecord?->id)->pluck('nama_kelas', 'id')),
            ])
            ->headerActions([
                CreateAction::make(),
                Action::make('generateAllParticipantCertificates')
                    ->label('Generate Sertifikat (LUNAS)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        $participants = $this->ownerRecord->participants()
                            ->where('status', Participant::STATUS_LUNAS)
                            ->whereDoesntHave('certificate')
                            ->get();

                        foreach ($participants as $participant) {
                            (new GenerateParticipantCertificateJob($participant))->handle();
                        }

                        Notification::make()
                            ->title(count($participants).' sertifikat peserta berhasil digenerate')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->iconButton()
                    ->modalHeading('Ubah Data Peserta'),
                Action::make('generate_certificate')
                    ->label('Generate Sertifikat')
                    ->icon('heroicon-o-document-plus')
                    ->iconButton()
                    ->visible(fn (Participant $record) => $record->status === Participant::STATUS_LUNAS)
                    ->action(function (Participant $record): void {
                        if ($record->certificate) {
                            Notification::make()->title('Sertifikat sudah ada')->warning()->send();

                            return;
                        }

                        (new GenerateParticipantCertificateJob($record))->handle();
                        Notification::make()->title('Sertifikat berhasil digenerate')->success()->send();
                    }),
                Action::make('catat_dp')
                    ->label('Catat DP')
                    ->icon('heroicon-o-banknotes')
                    ->color('warning')
                    ->iconButton()
                    ->visible(fn (Participant $record) => $record->status !== Participant::STATUS_LUNAS)
                    ->form([
                        TextInput::make('dp_amount')
                            ->label('Jumlah DP (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->default(fn (Participant $record) => (int) $record->dp_amount ?? 0),
                    ])
                    ->action(function (Participant $record, array $data): void {
                        $record->update([
                            'dp_amount' => $data['dp_amount'] ?? 0,
                        ]);

                        Notification::make()
                            ->title('DP "'.$record->nama_pemilik.'" sebesar Rp '.number_format((float) $data['dp_amount'], 0, ',', '.').' dicatat.')
                            ->success()
                            ->send();
                    }),
                Action::make('verify')
                    ->label('Terima / Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Participant $record) => $record->status !== Participant::STATUS_LUNAS)
                    ->requiresConfirmation()
                    ->action(fn (Participant $record) => $this->markAsLunas($record)),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Participant $record) => $record->status !== Participant::STATUS_REJECTED)
                    ->requiresConfirmation()
                    ->action(fn (Participant $record) => $record->update(['status' => Participant::STATUS_REJECTED])),
                Action::make('view_bukti')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->modalWidth(Width::Large)
                    ->modalContent(fn (Participant $record) => view('filament.organizer.participant-bukti', ['record' => $record])),
                DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->iconButton()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at');
    }

    private function markAsLunas(Participant $record): void
    {
        if ($record->status === Participant::STATUS_LUNAS) {
            return;
        }

        $record->update([
            'status' => Participant::STATUS_LUNAS,
        ]);

        Notification::make()
            ->title('Peserta "'.$record->nama_pemilik.'" diterima (LUNAS).')
            ->success()
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getTabsContentComponent(),
                RenderHook::make(PanelsRenderHook::RESOURCE_RELATION_MANAGER_BEFORE),
                Livewire::make(ParticipantStatsOverview::class, ['event_id' => $this->getOwnerRecord()?->id]),
                EmbeddedTable::make(),
                RenderHook::make(PanelsRenderHook::RESOURCE_RELATION_MANAGER_AFTER),
            ]);
    }
}
