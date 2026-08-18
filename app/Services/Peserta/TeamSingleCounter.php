<?php

namespace App\Services\Peserta;

use App\Models\Participant;
use Illuminate\Support\Collection;

class TeamSingleCounter
{
    public const MIN_PESERTA = 10;

    public const SINGLE_FIGHTER_LABEL = 'single fighter';

    /**
     * Hitung jumlah team dan single fighter yang diakui dari daftar peserta.
     *
     * - Team: baris dengan team_sf tidak kosong dan bukan "single fighter".
     *   Kategori diakui bila total peserta team >= 10, lalu dihitung jumlah nama team unik.
     * - Single fighter: baris dengan team_sf "single fighter".
     *   Kategori diakui bila total peserta single fighter >= 10, lalu dihitung jumlah orang unik.
     *
     * @param  Collection<int, Participant>  $participants
     * @return array{teams: int, single_fighters: int}
     */
    public function count(Collection $participants): array
    {
        $teams = [];
        $singleFighterRows = [];

        foreach ($participants as $participant) {
            $teamSf = strtolower(trim((string) $participant->team_sf));

            if ($teamSf === '') {
                continue;
            }

            if ($teamSf === self::SINGLE_FIGHTER_LABEL) {
                $singleFighterRows[] = strtolower(trim((string) ($participant->nama_pemilik ?? $participant->nama_peserta)));

                continue;
            }

            $teams[] = $teamSf;
        }

        $teamsRecognized = count($teams) >= self::MIN_PESERTA
            ? count(array_unique($teams))
            : 0;

        $singleFightersRecognized = count($singleFighterRows) >= self::MIN_PESERTA
            ? count(array_unique($singleFighterRows))
            : 0;

        return [
            'teams' => $teamsRecognized,
            'single_fighters' => $singleFightersRecognized,
        ];
    }
}
