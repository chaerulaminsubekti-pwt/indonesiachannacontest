<?php

namespace App\Filament\Organizer\Resources\EventResource\RelationManagers;

use App\Jobs\GenerateCertificateJob;
use App\Models\EventClass;
use App\Models\WinnerPredikat;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class WinnersRelationManager extends RelationManager
{
    protected static string $relationship = 'winners';

    protected static ?string $recordTitleAttribute = 'nama_pemenang';

    protected static ?string $title = 'Sertifikat Juara';

    public function form(Schema $schema): Schema
    {
        $event = $this->ownerRecord;

        // Ensure default classes exist for this event
        if ($event) {
            EventClass::ensureDefaultClassesExist($event->id);
        }

        return $schema
            ->columns(1)
            ->components([
                Select::make('event_class_id')
                    ->label('Kelas')
                    ->options(fn () => EventClass::where('event_id', $event?->id)->pluck('nama_kelas', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('winner_predikat_id', null)),

                Select::make('winner_predikat_id')
                    ->label('Predikat / Juara (opsional)')
                    ->options(function ($get) {
                        $classId = $get('event_class_id');
                        if (! $classId) {
                            return [];
                        }

                        $count = WinnerPredikat::where('event_class_id', $classId)->count();
                        if ($count === 0) {
                            $class = EventClass::find($classId);
                            if ($class) {
                                foreach (EventClass::getDefaultPredikats() as $predikat) {
                                    WinnerPredikat::create([
                                        'event_class_id' => $classId,
                                        'nama_predikat' => $predikat['nama_predikat'],
                                        'urutan' => $predikat['urutan'],
                                    ]);
                                }
                            }
                        }

                        return WinnerPredikat::where('event_class_id', $classId)
                            ->orderBy('urutan')
                            ->get()
                            ->pluck('nama_predikat', 'id');
                    })
                    ->searchable()
                    ->placeholder('Kosongkan jika tidak perlu'),

                TextInput::make('nama_pemenang')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('class.nama_kelas')
                    ->label('Kelas')
                    ->description(fn ($record) => $record->predikat?->nama_predikat)
                    ->badge()->color('warning')
                    ->wrap(),
                TextColumn::make('nama_pemenang')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('certificate.nomor_sertifikat')
                    ->label('No. Sertifikat')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Juara'),
                Action::make('generateAllCertificates')
                    ->label('Generate Semua Sertifikat')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (): void {
                        $winners = $this->ownerRecord->winners()->whereDoesntHave('certificate')->get();
                        foreach ($winners as $winner) {
                            (new GenerateCertificateJob($winner))->handle();
                        }
                        Notification::make()
                            ->title(count($winners).' sertifikat berhasil digenerate')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('generate')
                    ->label('Generate Sertifikat')
                    ->iconButton()
                    ->icon('heroicon-o-document-plus')
                    ->action(function ($record): void {
                        if (! $record->certificate) {
                            (new GenerateCertificateJob($record))->handle();
                            Notification::make()
                                ->title('Sertifikat berhasil digenerate')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Sertifikat sudah ada')
                                ->warning()
                                ->send();
                        }
                    }),
                Action::make('deleteCertificate')
                    ->label('Hapus Sertifikat')
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($record) => $record->certificate !== null)
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        $certificate = $record->certificate;
                        if ($certificate) {
                            if ($certificate->file_path) {
                                Storage::disk('public')->delete($certificate->file_path);
                            }
                            $certificate->delete();
                            Notification::make()
                                ->title('Sertifikat berhasil dihapus')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at');
    }
}
