<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ManageArticles;
use App\Models\Article;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Artikel';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Artikel')
                    ->description('Tulis dan kelola postingan artikel')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Article::uniqueSlug($state ?? ''))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis dari judul, bisa diubah manual.'),
                        Select::make('kategori')
                            ->options(array_combine(Article::KATEGORI, Article::KATEGORI))
                            ->required()
                            ->default('Berita'),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->default('draft'),
                        DateTimePicker::make('published_at')
                            ->label('Terbit Pada')
                            ->default(now())
                            ->helperText('Artikel tampil publik jika status Published dan waktu terbit sudah lewat.'),
                        FileUpload::make('gambar_sampul')
                            ->label('Gambar Sampul')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('articles/covers')
                            ->disk('public')
                            ->maxSize(5120)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeUpscale(false),
                    ]),
                Section::make('Isi')
                    ->schema([
                        Textarea::make('ringkasan')
                            ->label('Ringkasan')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('1-2 kalimat pembuka, tampil di kartu daftar artikel.'),
                        RichEditor::make('isi')
                            ->label('Isi Artikel')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('articles/content')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->fileAttachmentsMaxSize(5120)
                            ->helperText('Gambar bisa disisipkan langsung dari toolbar editor (JPG/PNG/WebP, maks 5 MB per gambar).')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                ImageColumn::make('gambar_sampul')
                    ->label('Sampul')
                    ->disk('public')
                    ->square()
                    ->height(50)
                    ->toggleable(),
                TextColumn::make('judul')
                    ->searchable()
                    ->description(fn (Article $record) => $record->kategori)
                    ->wrap(),
                TextColumn::make('kategori')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('views')
                    ->label('Dilihat')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->counts('comments')
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                ]),
                SelectFilter::make('kategori')->options(
                    array_combine(Article::KATEGORI, Article::KATEGORI)
                ),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
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
            'index' => ManageArticles::route('/'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
