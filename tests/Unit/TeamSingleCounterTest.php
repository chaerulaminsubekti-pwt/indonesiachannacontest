<?php

namespace Tests\Unit;

use App\Models\Participant;
use App\Services\Peserta\TeamSingleCounter;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TeamSingleCounterTest extends TestCase
{
    protected function participant(string $name, ?string $teamSf): Participant
    {
        $participant = new Participant;
        $participant->nama_pemilik = $name;
        $participant->nama_peserta = $name;
        $participant->team_sf = $teamSf;

        return $participant;
    }

    protected function rows(array $data): Collection
    {
        return collect(array_map(fn ($row) => $this->participant($row[0], $row[1]), $data));
    }

    protected function counter(): TeamSingleCounter
    {
        return new TeamSingleCounter;
    }

    #[Test]
    public function team_recognized_when_at_least_20_participants(): void
    {
        $rows = array_map(fn ($i) => ['Peserta '.$i, 'GMC Kediri'], range(1, 20));
        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(1, $result['teams']);
        $this->assertSame('GMC Kediri', $result['teams'][0]['name']);
        $this->assertSame(20, $result['teams'][0]['count']);
        $this->assertCount(0, $result['single_fighters']);
    }

    #[Test]
    public function team_not_recognized_below_20_participants(): void
    {
        $rows = array_map(fn ($i) => ['Peserta '.$i, 'GMC Kediri'], range(1, 19));
        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(0, $result['teams']);
    }

    #[Test]
    public function each_distinct_team_counted_once(): void
    {
        $teamA = array_map(fn ($i) => ['Peserta '.$i, 'GMC Kediri'], range(1, 20));
        $teamB = array_map(fn ($i) => ['Peserta '.$i, 'Gogo Team'], range(1, 20));
        $result = $this->counter()->count($this->rows(array_merge($teamA, $teamB)));

        $this->assertCount(2, $result['teams']);
    }

    #[Test]
    public function team_grouping_is_case_and_space_insensitive(): void
    {
        $teamA = array_map(fn ($i) => ['Peserta '.$i, 'GMC Kediri'], range(1, 10));
        $teamB = array_map(fn ($i) => ['Peserta '.$i, '  gmc kediri  '], range(1, 10));
        $result = $this->counter()->count($this->rows(array_merge($teamA, $teamB)));

        $this->assertCount(1, $result['teams']);
        $this->assertSame(20, $result['teams'][0]['count']);
    }

    #[Test]
    public function single_fighter_recognized_when_at_least_15_participants(): void
    {
        $rows = array_map(fn ($i) => ['Slamet Riyadi', 'Single Fighter'], range(1, 15));
        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(0, $result['teams']);
        $this->assertCount(1, $result['single_fighters']);
        $this->assertSame('Slamet Riyadi', $result['single_fighters'][0]['name']);
        $this->assertSame(15, $result['single_fighters'][0]['count']);
    }

    #[Test]
    public function single_fighter_counts_distinct_persons_only(): void
    {
        $rows = [
            ['Slamet Riyadi', 'Single Fighter'],
            ['Slamet Riyadi', 'Single Fighter'],
            ['Budi Utomo', 'Single Fighter'],
        ];
        $rows = array_merge($rows, array_map(fn ($i) => ['Slamet Riyadi', 'Single Fighter'], range(1, 13)));

        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(1, $result['single_fighters']);
        $this->assertSame('Slamet Riyadi', $result['single_fighters'][0]['name']);
        $this->assertSame(15, $result['single_fighters'][0]['count']);
    }

    #[Test]
    public function single_fighter_not_recognized_below_15_participants(): void
    {
        $rows = array_map(fn ($i) => ['Slamet Riyadi', 'Single Fighter'], range(1, 14));
        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(0, $result['single_fighters']);
    }

    #[Test]
    public function empty_team_sf_is_ignored(): void
    {
        $rows = array_map(fn ($i) => ['Peserta '.$i, null], range(1, 10));
        $result = $this->counter()->count($this->rows($rows));

        $this->assertCount(0, $result['teams']);
        $this->assertCount(0, $result['single_fighters']);
    }
}
