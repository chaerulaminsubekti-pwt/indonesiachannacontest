<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Judge;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class PengajuanEvent extends Component
{
    public int $step = 1;

    public string $nama_event = '';

    public string $tanggal_mulai = '';

    public string $tanggal_selesai = '';

    public string $venue = '';

    public string $kategori = '';

    public string $tema = '';

    public string $wilayah_kota = '';

    public string $nama_penyelenggara = '';

    public string $deskripsi = '';

    public string $pic_nama = '';

    public string $pic_jabatan = '';

    public string $pic_no_wa = '';

    public string $pic_no_ktp = '';

    public string $pic_email = '';

    public string $pic_username = '';

    public string $pic_password = '';

    public string $pic_password_confirmation = '';

    public ?int $juri_1 = null;
    public ?int $juri_2 = null;
    public ?int $juri_3 = null;
    public ?int $juri_4 = null;
    public ?int $juri_5 = null;

    public bool $setuju = false;

    public bool $success = false;

    public function goToStep(int $step): void
    {
        if ($step === 2) {
            $this->validateStep1();
        } elseif ($step === 3) {
            $this->validateStep2();
        }
        $this->step = $step;
    }

    public function validateStep1(): void
    {
        $this->validate([
            'nama_penyelenggara' => 'required|string|max:255',
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'venue' => 'required|string|max:255',
            'kategori' => 'required|in:Latber,Mini Contest,Regional,Nasional',
            'tema' => 'nullable|string|max:255',
            'wilayah_kota' => 'required|string|max:255',
            'juri_1' => 'nullable|exists:judges,id',
            'juri_2' => 'nullable|exists:judges,id',
            'juri_3' => 'nullable|exists:judges,id',
            'juri_4' => 'nullable|exists:judges,id',
            'juri_5' => 'nullable|exists:judges,id',
        ]);

        $selected = array_filter([$this->juri_1, $this->juri_2, $this->juri_3, $this->juri_4, $this->juri_5]);
        if (count($selected) < 2) {
            $this->addError('juri_1', 'Minimal pilih 2 juri.');
        }
    }

    public function validateStep2(): void
    {
        $this->validate([
            'pic_nama' => 'required|string|max:255',
            'pic_jabatan' => 'nullable|string|max:255',
            'pic_no_wa' => 'required|string|max:20',
            'pic_no_ktp' => 'nullable|string|max:30',
            'pic_email' => 'required|email|max:255|unique:users,email',
            'pic_username' => 'required|string|max:255|min:3|unique:users,username',
            'pic_password' => 'required|string|min:8|confirmed',
        ]);
    }

    public function submit(): void
    {
        $this->validateStep1();
        $this->validateStep2();
        $this->validate(['setuju' => 'accepted']);

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->pic_nama,
                'email' => $this->pic_email,
                'username' => $this->pic_username,
                'password' => Hash::make($this->pic_password),
                'role' => 'penyelenggara',
                'status' => 'inactive',
            ]);

            $user->assignRole('penyelenggara');

            $organizer = Organizer::create([
                'user_id' => $user->id,
                'nama_organisasi' => $this->nama_penyelenggara,
                'jabatan_pic' => $this->pic_jabatan,
                'no_wa' => $this->pic_no_wa,
                'no_ktp' => $this->pic_no_ktp,
            ]);

            $event = Event::create([
                'organizer_id' => $organizer->id,
                'nama_event' => $this->nama_event,
                'slug' => Str::slug($this->nama_event).'-'.Str::random(6),
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'venue' => $this->venue,
                'kategori' => $this->kategori,
                'tema' => $this->tema,
                'wilayah_kota' => $this->wilayah_kota,
                'deskripsi' => $this->deskripsi,
                'status' => 'pending',
            ]);

            $urutan = 1;
            foreach ([$this->juri_1, $this->juri_2, $this->juri_3, $this->juri_4, $this->juri_5] as $judgeId) {
                if ($judgeId) {
                    $event->judges()->attach($judgeId, ['urutan' => $urutan]);
                }
                $urutan++;
            }
        });

        $this->success = true;
    }

    public function render()
    {
        $availableJudges = Judge::orderBy('nama')->get();
        return view('livewire.pengajuan-event', compact('availableJudges'));
    }
}
