<?php

namespace App\Filament\Organizer\Resources;

use App\Filament\Organizer\Resources\EventResource\Pages;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\BankAccountsRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\EventClassesRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\EventCpsRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\EventFlyersRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\EventGalleriesRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\ParticipantsRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\TeamSfRegistrationsRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\TestimonialsRelationManager;
use App\Filament\Organizer\Resources\EventResource\RelationManagers\WinnersRelationManager;
use App\Models\Event;
use App\Models\Organizer;
use BackedEnum;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Kontes';

    protected static ?string $slug = 'events';

    public static function getEloquentQuery(): Builder
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();

        return parent::getEloquentQuery()->where('organizer_id', $organizer?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Event')
                    ->description('Detail utama kontes anda')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_event')
                            ->required()->maxLength(255)->disabled(),
                        Select::make('kategori')
                            ->options([
                                'latber' => 'Latber',
                                'mini_contest' => 'Mini Contest',
                                'regional' => 'Regional',
                                'nasional' => 'Nasional',
                                'series_icc' => 'Series ICC',
                            ])
                            ->required(),
                        TextInput::make('tema')->maxLength(255),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'berjalan' => 'Berjalan',
                                'selesai' => 'Selesai',
                            ])
                            ->disabled()
                            ->label('Status'),
                    ]),
                Section::make('Waktu & Tempat')
                    ->description('Jadwal dan lokasi penyelenggaraan')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tanggal_mulai')->required(),
                        DatePicker::make('tanggal_selesai')->required(),
                        TextInput::make('venue')->required()->maxLength(255),
                        TextInput::make('wilayah_kota')->maxLength(255),
                    ]),
                Section::make('Data Peserta')
                    ->description('Link Google Sheets untuk data peserta')
                    ->columns(1)
                    ->schema([
                        TextInput::make('google_sheet_url')
                            ->label('Link Google Sheets (Data Peserta)')
                            ->placeholder('https://docs.google.com/spreadsheets/d/...')
                            ->url()
                            ->helperText('Tempel link Google Sheets yang berisi kolom: No, Nama, Nama Ikan. Sheet harus bisa diakses publik.'),
                    ]),
                Section::make('Deskripsi')
                    ->description('Informasi tambahan tentang event')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Event'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('nama_event')
                    ->searchable()
                    ->description(fn (Event $record) => $record->wilayah_kota)
                    ->wrap(),
                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Latber' => 'gray',
                        'Mini Contest' => 'info',
                        'Regional' => 'warning',
                        'Nasional' => 'success',
                        'Series ICC' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'berjalan' => 'info',
                        'selesai' => 'gray',
                    }),
                TextColumn::make('tanggal_mulai')
                    ->date('d M Y')
                    ->description(fn (Event $record) => $record->tanggal_mulai != $record->tanggal_selesai
                            ? 's.d. '.Carbon::parse($record->tanggal_selesai)->isoFormat('D MMM Y')
                            : ''
                    )
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('wilayah_kota')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            EventClassesRelationManager::class,
            BankAccountsRelationManager::class,
            ParticipantsRelationManager::class,
            TeamSfRegistrationsRelationManager::class,
            EventFlyersRelationManager::class,
            EventCpsRelationManager::class,
            WinnersRelationManager::class,
            EventGalleriesRelationManager::class,
            TestimonialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
