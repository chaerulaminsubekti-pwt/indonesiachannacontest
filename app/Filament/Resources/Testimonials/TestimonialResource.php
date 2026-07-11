<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Home';

    protected static ?string $navigationLabel = 'Testimoni';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')->relationship('event', 'nama_event')->required(),
                Select::make('organizer_id')->relationship('organizer', 'nama_organisasi')->required(),
                Textarea::make('isi_testimoni')->required(),
                TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->default(5),
                Select::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('event.nama_event')
                    ->searchable()
                    ->description(fn (Testimonial $record) => $record->organizer?->nama_organisasi)
                    ->wrap(),
                TextColumn::make('organizer.nama_organisasi')
                    ->label('Penyelenggara')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('isi_testimoni')
                    ->limit(80)
                    ->wrap(),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int) $state >= 5 => 'success',
                        (int) $state >= 4 => 'info',
                        (int) $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
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
            'index' => ManageTestimonials::route('/'),
        ];
    }
}
