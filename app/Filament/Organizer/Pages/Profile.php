<?php

namespace App\Filament\Organizer\Pages;

use App\Models\Organizer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class Profile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static ?string $slug = 'profile';

    public ?array $data = [];

    public function mount(): void
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();
        $this->data = $organizer?->toArray() ?? [];
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Organisasi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_organisasi')
                            ->label('Nama Organisasi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('jabatan_pic')
                            ->label('Jabatan PIC')
                            ->maxLength(255),
                        TextInput::make('no_wa')
                            ->label('No. WhatsApp')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('no_ktp')
                            ->label('No. KTP')
                            ->maxLength(20),
                    ]),

                Section::make('Ganti Password')
                    ->columns(2)
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->requiredWith('new_password'),
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->minLength(8)
                            ->same('new_password_confirmation'),
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password(),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan')
                                ->submit('save'),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $organizer = Organizer::where('user_id', auth()->id())->first();
        if ($organizer) {
            $organizer->update([
                'nama_organisasi' => $this->data['nama_organisasi'] ?? $organizer->nama_organisasi,
                'jabatan_pic' => $this->data['jabatan_pic'] ?? $organizer->jabatan_pic,
                'no_wa' => $this->data['no_wa'] ?? $organizer->no_wa,
                'no_ktp' => $this->data['no_ktp'] ?? $organizer->no_ktp,
            ]);
        }

        if (! empty($this->data['new_password'])) {
            $user = auth()->user();
            if (! Hash::check($this->data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Password saat ini tidak sesuai')
                    ->danger()
                    ->send();

                return;
            }

            $user->update(['password' => Hash::make($this->data['new_password'])]);
        }

        Notification::make()
            ->title('Profil berhasil disimpan')
            ->success()
            ->send();
    }
}
