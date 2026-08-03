<?php

namespace App\Filament\Organizer\Pages;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Testimoni extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Testimoni';

    protected static ?string $slug = 'testimoni';

    protected string $view = 'filament.organizer.pages.testimoni';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Kirim Testimoni')
                ->submit('submit'),
        ];
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();

        return $schema
            ->components([
                Select::make('event_id')
                    ->label('Event')
                    ->options(fn () => Event::where('organizer_id', $organizer?->id)
                        ->where('status', 'selesai')
                        ->pluck('nama_event', 'id'))
                    ->required()
                    ->placeholder('Pilih event yang sudah selesai'),
                Textarea::make('isi_testimoni')
                    ->label('Testimoni')
                    ->required()
                    ->maxLength(1000),
                Select::make('rating')
                    ->label('Rating Bintang')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐',
                        4 => '⭐⭐⭐⭐',
                        3 => '⭐⭐⭐',
                        2 => '⭐⭐',
                        1 => '⭐',
                    ])
                    ->required(),
            ]);
    }

    public function submit(): void
    {
        $data = $this->data;

        $validator = validator($data, [
            'event_id' => 'required',
            'isi_testimoni' => 'required|max:1000',
            'rating' => 'required|integer|between:1,5',
        ], [
            'event_id.required' => 'Event harus dipilih.',
            'isi_testimoni.required' => 'Testimoni harus diisi.',
            'isi_testimoni.max' => 'Testimoni maksimal 1000 karakter.',
            'rating.required' => 'Rating bintang harus dipilih.',
            'rating.between' => 'Rating harus antara 1-5.',
        ]);

        $validator->validate();

        $organizer = Organizer::where('user_id', auth()->id())->first();
        if (! $organizer) {
            Notification::make()->title('Data organisasi tidak ditemukan')->danger()->send();

            return;
        }

        Testimonial::create([
            'event_id' => $data['event_id'],
            'organizer_id' => $organizer->id,
            'isi_testimoni' => $data['isi_testimoni'],
            'rating' => $data['rating'],
            'status' => 'pending',
        ]);

        Notification::make()
            ->title('Testimoni berhasil dikirim, menunggu persetujuan admin')
            ->success()
            ->send();

        $this->data = [];
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();

        return $table
            ->query(Testimonial::where('organizer_id', $organizer?->id))
            ->striped()
            ->columns([
                TextColumn::make('event.nama_event')->label('Event')->wrap(),
                TextColumn::make('isi_testimoni')->label('Testimoni')->limit(60)->wrap(),
                TextColumn::make('rating')->label('Rating'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
