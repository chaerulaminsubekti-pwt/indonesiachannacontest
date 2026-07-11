<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tab::make('Identitas Situs')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Logo & Favicon')->columns(2)->schema([
                                    FileUpload::make('logo_header')
                                        ->label('Logo Header')
                                        ->image()->directory('site')->maxSize(5120)
                                        ->disk('public'),
                                        FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->image()->directory('site')->maxSize(5120)
                                            ->disk('public'),
                                ]),
                                Section::make('Informasi Dasar')->columns(2)->schema([
                                    TextInput::make('nama_website')
                                        ->label('Nama Website')->required()->maxLength(255),
                                    TextInput::make('tagline')
                                        ->label('Tagline')->maxLength(255),
                                    Textarea::make('meta_description')
                                        ->label('Meta Description (SEO)')
                                        ->helperText('Deskripsi website untuk hasil pencarian Google & sosial media')
                                        ->columnSpanFull()
                                        ->maxLength(500),
                                ]),
                                Section::make('Kontak')->columns(2)->schema([
                                    TextInput::make('email_kontak')
                                        ->label('Email Kontak')->email()->maxLength(255),
                                    TextInput::make('no_wa_kontak')
                                        ->label('No. WhatsApp Kontak')->maxLength(20),
                                ]),
                                Section::make()->schema([
                                    TextInput::make('alamat')
                                        ->label('Alamat')->maxLength(255),
                                ]),
                            ]),

                        Tab::make('Sambutan Ketua')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Section::make('Foto & Nama')->columns(2)->schema([
                                    FileUpload::make('foto_ketua')
                                        ->label('Foto Ketua')
                                        ->image()->directory('site')->maxSize(5120)
                                        ->avatar()
                                        ->disk('public'),
                                    TextInput::make('nama_ketua')
                                        ->label('Nama Ketua')->maxLength(255),
                                ]),
                                Section::make('Isi Sambutan')->schema([
                                    RichEditor::make('sambutan_ketua')
                                        ->label('')
                                        ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'undo', 'redo'])
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Tab::make('Sambutan Pembina')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make('Foto & Nama')->columns(2)->schema([
                                    FileUpload::make('foto_pembina')
                                        ->label('Foto Pembina')
                                        ->image()->directory('site')->maxSize(5120)
                                        ->avatar()
                                        ->disk('public'),
                                    TextInput::make('nama_pembina')
                                        ->label('Nama Pembina')->maxLength(255),
                                ]),
                                Section::make('Jabatan')->schema([
                                    TextInput::make('jabatan_pembina')
                                        ->label('Jabatan Pembina')->maxLength(255)
                                        ->placeholder('Contoh: Pembina Umum / Ketua Umum Pembina'),
                                ]),
                                Section::make('Isi Sambutan')->schema([
                                    RichEditor::make('sambutan_pembina')
                                        ->label('')
                                        ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'undo', 'redo'])
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        Tab::make('Sosial Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make()->columns(2)->schema([
                                    TextInput::make('link_instagram')
                                        ->label('Instagram')->url()->maxLength(255)
                                        ->prefix('@'),
                                    TextInput::make('link_facebook')
                                        ->label('Facebook')->url()->maxLength(255),
                                ]),
                                Section::make()->columns(2)->schema([
                                    TextInput::make('link_youtube')
                                        ->label('YouTube')->url()->maxLength(255),
                                    TextInput::make('link_tiktok')
                                        ->label('TikTok')->url()->maxLength(255),
                                ]),
                            ]),

                        Tab::make('Tampilan')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Section::make('Warna')->columns(2)->schema([
                                    TextInput::make('warna_primary')
                                        ->label('Warna Primary')
                                        ->placeholder('#1d4ed8')
                                        ->maxLength(7),
                                    TextInput::make('warna_secondary')
                                        ->label('Warna Secondary')
                                        ->placeholder('#f59e0b')
                                        ->maxLength(7),
                                ]),
                            ]),

                        Tab::make('Footer & Notifikasi')
                            ->icon('heroicon-o-inbox-arrow-down')
                            ->schema([
                                Section::make('Footer')->columns(2)->schema([
                                    TextInput::make('teks_copyright')
                                        ->label('Teks Copyright')->maxLength(255),
                                    TextInput::make('teks_copyright_footer')
                                        ->label('Teks Copyright Footer')->maxLength(255),
                                ]),
                                Section::make('Notifikasi')->schema([
                                    TextInput::make('email_pengirim_notifikasi')
                                        ->label('Email Pengirim Notifikasi')
                                        ->email()->maxLength(255)
                                        ->helperText('Digunakan sebagai pengirim email otomatis sistem.'),
                                ]),
                            ]),
                    ])->activeTab(0)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_header')->circular()->disk('public')->label('Logo'),
                TextColumn::make('nama_website')->label('Nama Situs'),
                TextColumn::make('tagline')->limit(40),
                TextColumn::make('updated_at')->label('Terakhir Update')->since(),
            ])
            ->paginated(false)
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Pengaturan Situs')
                    ->modalWidth('7xl'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
