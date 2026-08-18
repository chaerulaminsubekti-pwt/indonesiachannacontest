<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;

class RenumberParticipants extends Command
{
    protected $signature = 'participants:renumber';

    protected $description = 'Perbaiki nomor urut peserta (no_urut) agar berurutan per kelas & event';

    public function handle(): int
    {
        $changed = Participant::renumberAll();

        $this->info('Nomor urut peserta berhasil diperbaiki. '.$changed.' urutan berubah.');

        return self::SUCCESS;
    }
}
