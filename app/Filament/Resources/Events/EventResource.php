<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\ManageEvents;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\EventCpsRelationManager;
use App\Filament\Resources\Events\RelationManagers\EventClassesRelationManager;
use App\Filament\Resources\Events\RelationManagers\JudgesRelationManager;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Event';

    protected static ?string $navigationLabel = 'Kontes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Event')
                    ->description('Detail utama tentang kontes')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_event')
                            ->required()->maxLength(255),
                        TextInput::make('slug')
                            ->required()->maxLength(255)->unique(ignoreRecord: true),
                        Select::make('kategori')
                            ->options([
                                'Latber' => 'Latber',
                                'Mini Contest' => 'Mini Contest',
                                'Regional' => 'Regional',
                                'Nasional' => 'Nasional',
                            ])->required(),
                        TextInput::make('tema')
                            ->maxLength(255),
                    ]),
                Section::make('Waktu & Tempat')
                    ->description('Jadwal dan lokasi penyelenggaraan')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tanggal_mulai')
                            ->required(),
                        DatePicker::make('tanggal_selesai')
                            ->required(),
                        TextInput::make('venue')
                            ->required()->maxLength(255),
                        TextInput::make('wilayah_kota')
                            ->required()->maxLength(255),
                    ]),
                Section::make('Status & Media')
                    ->description('Pengaturan status dan media promosi')
                    ->columns(2)
                    ->schema([
                        Select::make('organizer_id')
                            ->relationship('organizer', 'nama_organisasi')
                            ->required()
                            ->searchable(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'berjalan' => 'Berjalan',
                                'selesai' => 'Selesai',
                            ])->required(),
                        FileUpload::make('flyer')
                            ->image()
                            ->directory('events/flyers')
                            ->disk('public')
                            ->columnSpanFull(),
                    ]),
                Section::make('Deskripsi')
                    ->description('Informasi tambahan tentang event')
                    ->schema([
                        Textarea::make('deskripsi'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('flyer')
                    ->disk('public')
                    ->square()
                    ->height(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_event')
                    ->searchable()
                    ->description(fn (Event $record) => $record->tema)
                    ->wrap(),
                TextColumn::make('tanggal_mulai')
                    ->date('d M Y')
                    ->description(fn (Event $record) =>
                        $record->tanggal_mulai != $record->tanggal_selesai
                            ? 's.d. '.\Carbon\Carbon::parse($record->tanggal_selesai)->isoFormat('D MMM Y')
                            : ''
                    )
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('organizer.nama_organisasi')
                    ->label('Penyelenggara')
                    ->description(fn (Event $record) => $record->wilayah_kota)
                    ->toggleable(),
                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Latber' => 'gray',
                        'Mini Contest' => 'info',
                        'Regional' => 'warning',
                        'Nasional' => 'success',
                        default => 'gray',
                    }),
                BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'berjalan' => 'info',
                        'selesai' => 'gray',
                    }),
                TextColumn::make('venue')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'berjalan' => 'Berjalan',
                    'selesai' => 'Selesai',
                ]),
                SelectFilter::make('kategori')->options([
                    'Latber' => 'Latber',
                    'Mini Contest' => 'Mini Contest',
                    'Regional' => 'Regional',
                    'Nasional' => 'Nasional',
                ]),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
                Action::make('updateOrganizerCredentials')
                    ->label('Update Kredensial Penyelenggara')
                    ->iconButton()
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->form([
                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->maxLength(255)
                            ->default(fn (Event $record) => $record->organizer?->user?->username)
                            ->helperText('Username baru untuk login penyelenggara'),
                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->default(fn (Event $record) => $record->organizer?->user?->email)
                            ->helperText('Email baru untuk login & notifikasi'),
                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('Kosongkan jika tidak ingin mengubah password')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (TextInput $component) => filled($component->getState())),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->same('password')
                            ->validationMessages([
                                'same' => 'Konfirmasi password tidak cocok.',
                            ]),
                    ])
                    ->action(function (Event $record, array $data): void {
                        $user = $record->organizer?->user;
                        if (! $user) {
                            Notification::make()
                                ->title('Penyelenggara tidak memiliki user')
                                ->danger()
                                ->send();
                            return;
                        }

                        $user->fill([
                            'username' => $data['username'],
                            'email' => $data['email'],
                        ]);

                        if (isset($data['password']) && filled($data['password'])) {
                            $user->password = Hash::make($data['password']);
                        }

                        $user->save();

                        Notification::make()
                            ->title('Kredensial penyelenggara berhasil diperbarui')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('Update Kredensial Penyelenggara')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->requiresConfirmation()
                    ->modalDescription('Perubahan ini akan mempengaruhi akses login penyelenggara.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EventClassesRelationManager::class,
            EventCpsRelationManager::class,
            JudgesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEvents::route('/'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
