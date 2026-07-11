<?php

namespace App\Filament\Organizer\Pages;

use App\Filament\Organizer\Resources\EventResource;
use App\Filament\Organizer\Widgets\EventStatsOverview;
use App\Models\Event;
use App\Models\Organizer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Dashboard extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(EventStatsOverview::class),
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();

        return $table
            ->striped()
            ->heading('Event Saya')
            ->description(Event::where('organizer_id', $organizer?->id)->count().' total event')
            ->query(Event::where('organizer_id', $organizer?->id))
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
                    ->description(fn (Event $record) =>
                        $record->tanggal_mulai != $record->tanggal_selesai
                            ? 's.d. '.\Carbon\Carbon::parse($record->tanggal_selesai)->isoFormat('D MMM Y')
                            : ''
                    )
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('wilayah_kota')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->actions([
                Action::make('kelola')
                    ->label('Kelola')
                    ->iconButton()
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
