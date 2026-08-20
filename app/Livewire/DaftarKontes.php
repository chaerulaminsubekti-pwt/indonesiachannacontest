<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class DaftarKontes extends Component
{
    use WithFileUploads;

    public Event $event;

    public string $nama_pemilik = '';

    public string $team_sf = '';

    public string $kota_asal = '';

    public string $no_hp = '';

    public array $items = [];

    public $bukti_pembayaran = null;

    public int $step = 1;

    public bool $showSuccess = false;

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->items = [['event_class_id' => '', 'nama_ikan' => '']];
    }

    public function getClassesProperty()
    {
        return EventClass::where('event_id', $this->event->id)
            ->orderBy('nama_kelas')
            ->get();
    }

    public function getBankAccountsProperty()
    {
        return $this->event->bankAccounts()->active()->get();
    }

    public function getTotalHargaProperty()
    {
        $total = 0;

        foreach ($this->items as $item) {
            if (empty($item['event_class_id'])) {
                continue;
            }

            $kelas = EventClass::find($item['event_class_id']);
            $total += (int) ($kelas?->harga_tiket ?? 0);
        }

        return $total;
    }

    public function getPaymentItemsProperty()
    {
        return collect($this->items)->map(function (array $item): array {
            $kelas = EventClass::find($item['event_class_id'] ?? null);

            return [
                'nama_kelas' => $kelas?->nama_kelas ?? 'Kelas Tidak Diketahui',
                'nama_ikan' => $item['nama_ikan'] ?? '',
                'harga' => (int) ($kelas?->harga_tiket ?? 0),
            ];
        });
    }

    public function addItem(): void
    {
        $this->items[] = ['event_class_id' => '', 'nama_ikan' => ''];
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }

        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submitStep1(): void
    {
        $this->validate($this->rules());

        $this->step = 2;
    }

    public function backToStep1(): void
    {
        $this->step = 1;
        $this->resetErrorBag();
    }

    public function submit(): void
    {
        $rules = $this->rules();
        $rules['bukti_pembayaran'] = 'required|image|max:4096';
        $this->validate($rules);

        $buktiPath = null;
        if ($this->bukti_pembayaran) {
            $buktiPath = $this->bukti_pembayaran->store('bukti-pembayaran', 'public');
        }

        try {
            DB::transaction(function () use ($buktiPath) {
                foreach ($this->items as $item) {
                    $kelas = EventClass::find($item['event_class_id'] ?? null);
                    if (! $kelas) {
                        continue;
                    }

                    $max = Participant::where('event_id', $this->event->id)
                        ->where('event_class_id', $kelas->id)
                        ->lockForUpdate()
                        ->max(DB::raw('no_urut + 0'));

                    Participant::create([
                        'event_id' => $this->event->id,
                        'event_class_id' => $kelas->id,
                        'user_id' => auth()->id(),
                        'nama_pemilik' => $this->nama_pemilik,
                        'nama_peserta' => $this->nama_pemilik,
                        'team_sf' => $this->team_sf,
                        'nama_ikan' => $item['nama_ikan'],
                        'kota_asal' => $this->kota_asal,
                        'no_hp' => $this->no_hp,
                        'status' => Participant::STATUS_MENUNGGU_VERIFIKASI,
                        'bukti_pembayaran' => $buktiPath,
                        'biaya' => $kelas->harga_tiket,
                        'no_urut' => (int) $max + 1,
                    ]);
                }
            });

            $this->showSuccess = true;
        } catch (\Throwable $e) {
            Log::error('Pendaftaran gagal: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'event_id' => $this->event->id,
                    'user_id' => auth()->id(),
                    'nama_pemilik' => $this->nama_pemilik,
                ],
            ]);
            $this->addError('submit', 'Gagal menyimpan pendaftaran: '.$e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->showSuccess = false;
        $this->resetForm();
    }

    private function rules(): array
    {
        return [
            'nama_pemilik' => 'required|string|max:255',
            'team_sf' => 'nullable|string|max:255',
            'kota_asal' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.event_class_id' => 'required|exists:event_classes,id',
            'items.*.nama_ikan' => 'required|string|max:255',
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'nama_pemilik', 'team_sf', 'kota_asal',
            'no_hp', 'bukti_pembayaran', 'step',
        ]);
        $this->items = [['event_class_id' => '', 'nama_ikan' => '']];
        $this->step = 1;
    }

    #[Layout('layouts.public')]
    public function render()
    {
        return view('livewire.daftar-kontes', [
            'classes' => $this->classes,
            'bankAccounts' => $this->bankAccounts,
            'totalHarga' => $this->totalHarga,
            'paymentItems' => $this->paymentItems,
        ]);
    }
}
