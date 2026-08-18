<?php

namespace App\Services\Peserta;

use Illuminate\Support\Collection;

class TeamSingleCounter
{
    public const MIN_TEAM_IKAN = 20;

    public const MIN_SINGLE_FIGHTER_IKAN = 15;

    public const SINGLE_FIGHTER_LABEL = 'single fighter';

    /**
     * Hitung daftar team dan single fighter yang diakui dari daftar peserta.
     *
     * - Team: baris dengan team_sf tidak kosong dan bukan "single fighter",
     *   dikelompokkan per nama team, diakui bila jumlah ikannya >= MIN_TEAM_IKAN.
     * - Single fighter: baris dengan team_sf "single fighter",
     *   dikelompokkan per orang (nama_pemilik), diakui bila jumlah ikannya >= MIN_SINGLE_FIGHTER_IKAN.
     *
     * @param  Collection<int, Participant>  $participants
     * @return array{teams: array<int, array{name: string, count: int}>, single_fighters: array<int, array{name: string, count: int}>}
     */
    public function count(Collection $participants): array
    {
        $teams = [];
        $singleFighters = [];

        foreach ($participants as $participant) {
            $teamSf = trim((string) $participant->team_sf);

            if ($teamSf === '') {
                continue;
            }

            $name = trim((string) ($participant->nama_pemilik ?? $participant->nama_peserta));

            if (strtolower($teamSf) === self::SINGLE_FIGHTER_LABEL) {
                $key = strtolower($name);
                $singleFighters[$key] ??= ['name' => $name, 'count' => 0];
                $singleFighters[$key]['count']++;

                continue;
            }

            $key = strtolower($teamSf);
            $teams[$key] ??= ['name' => $teamSf, 'count' => 0];
            $teams[$key]['count']++;
        }

        return [
            'teams' => $this->qualifyingUnits($teams, self::MIN_TEAM_IKAN),
            'single_fighters' => $this->qualifyingUnits($singleFighters, self::MIN_SINGLE_FIGHTER_IKAN),
        ];
    }

    /**
     * @param  array<string, array{name: string, count: int}>  $units
     * @return array<int, array{name: string, count: int}>
     */
    private function qualifyingUnits(array $units, int $minimum): array
    {
        $result = array_values(array_filter($units, fn (array $unit): bool => $unit['count'] >= $minimum));

        usort($result, fn (array $a, array $b): int => $b['count'] <=> $a['count']);

        return $result;
    }
}
