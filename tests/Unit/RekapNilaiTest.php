<?php

namespace Tests\Unit;

use App\Services\RekapNilai\RekapCalculator;
use App\Services\RekapNilai\SheetScoreParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RekapNilaiTest extends TestCase
{
    protected function sampleCsv(): string
    {
        return implode("\n", [
            ',,REKAP PENILAIAN NUSATIC INDONESIA CHANNA CONTEST PT. ZEEX AQUATIC 2026,,,,,,,,,,,,,,,',
            ',,KELAS YELLOW PROGRES 15 - 20 CM,,,,,,,,,,,,,,,',
            ',,,,,,',
            'No Tank,JURI,SESI 1,,,,,,Sesi 2,,,,,,,,,,,,,,SUB TOTAL,GRAND TOTAL,RANKING POINT,HELPER HEAD TO HEAD,RANKING JUARA',
            ',,Penguasaan Tank,Mental,Warna Badan,Warna Fin,Kepekatan Bar,,Proporsi Bunga,Ekstra Bunga,Presisi Bar,Proporsi Badan,Anatomi,,,,,,,,,,,,,',
            ',,,,,,,,,,,,Mata,Mulut,Sungut,Badan,Dorsal,Dayung,Dasi,Anal,Ekor,,,,,',
            ',,,,,,',
            '1,Ilham,10,2,4,2,2,,3,1,3,1,1,1,1,2,1,1,1,1,1,,38,79,11,79.20627565,12',
            ',Rahul,10,5,3,3,2,,3,1,3,1,1,1,1,2,1,1,1,1,1,,41,,,,,',
            ',,,,,,',
            '2,Ilham,10,4,4,3,1,,3,2,3,1,1,1,1,2,1,1,1,1,1,,41,84,4,84.20647764,4',
            ',Rahul,10,5,3,4,2,,3,2,3,1,1,1,1,2,1,1,1,1,1,,43,,,,,',
            ',,,,,,',
            '3,Ilham,10,5,3,4,2,,4,2,3,1,1,1,1,2,1,1,1,1,1,,44,87,1,87.20845865,1',
            ',Rahul,10,5,2,4,2,,4,2,3,1,1,1,1,2,1,1,1,1,1,,43,,,,,',
            ',,,,,,',
            '4,Ilham,10,5,3,4,1,,4,2,3,1,1,1,1,2,1,1,1,1,1,,43,86,2,86.208462,3',
            ',Rahul,10,5,3,4,1,,4,2,3,1,1,1,1,2,1,1,1,1,1,,43,,,,,',
            ',,,,,,',
            '5,Ilham,10,5,3,4,1,,4,2,3,1,1,1,1,2,1,1,1,1,1,,43,86,2,86.208468,2',
            ',Rahul,10,5,3,4,1,,4,2,3,1,1,1,1,2,1,1,1,1,1,,43,,,,,',
            ',,,,,,',
            '6,Ilham,,,,,,,,,,,,,,,,,,,,,,0,0,28,0,28',
            ',Rahul,,,,,,,,,,,,,,,,,,,,,,0,,,,,',
        ]);
    }

    #[Test]
    public function parses_criteria_and_tanks(): void
    {
        $parser = new SheetScoreParser;
        $parsed = $parser->parse($this->sampleCsv());

        $this->assertCount(18, $parsed['criteria']);
        $this->assertSame('Penguasaan Tank', $parsed['criteria'][0]);
        $this->assertSame('Ekor', $parsed['criteria'][17]);
        $this->assertCount(6, $parsed['tanks']);
    }

    #[Test]
    public function parses_sessions_from_sheet_header(): void
    {
        $parser = new SheetScoreParser;
        $parsed = $parser->parse($this->sampleCsv());

        $this->assertCount(2, $parsed['sessions']);
        $this->assertSame('SESI 1', $parsed['sessions'][0]['name']);
        $this->assertSame([0, 1, 2, 3, 4], $parsed['sessions'][0]['indices']);
        $this->assertSame('Sesi 2', $parsed['sessions'][1]['name']);
        $this->assertSame(range(5, 17), $parsed['sessions'][1]['indices']);
    }

    #[Test]
    public function computes_grand_total_ranking_and_juara(): void
    {
        $parser = new SheetScoreParser;
        $calc = new RekapCalculator;

        $result = $calc->calculate($parser->parse($this->sampleCsv()));
        $tanks = collect($result['tanks']);

        // Tank tanpa data (6) dikeluarkan dari tampilan
        $this->assertCount(5, $tanks);

        $this->assertSame(87, $tanks->where('no_tank', 3)->first()['grand_total']);
        $this->assertSame(1, $tanks->where('no_tank', 3)->first()['ranking_point']);
        $this->assertSame(1, $tanks->where('no_tank', 3)->first()['ranking_juara']);

        $this->assertSame(79, $tanks->where('no_tank', 1)->first()['grand_total']);
        $this->assertSame(5, $tanks->where('no_tank', 1)->first()['ranking_point']);
        $this->assertSame(5, $tanks->where('no_tank', 1)->first()['ranking_juara']);

        // Tie penuh pada GRAND TOTAL 86 (nilai identik) -> RANKING POINT sama (2) dan JUARA sama (2)
        $tanksTie = $tanks->where('grand_total', 86)->values();
        $this->assertCount(2, $tanksTie);
        $this->assertSame(2, $tanksTie[0]['ranking_point']);
        $this->assertSame(2, $tanksTie[1]['ranking_point']);
        $this->assertSame(2, $tanksTie[0]['ranking_juara']);
        $this->assertSame(2, $tanksTie[1]['ranking_juara']);
    }

    #[Test]
    public function normalizes_google_sheet_edit_url_to_csv_export(): void
    {
        $parser = new SheetScoreParser;

        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=5',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/edit#gid=5')
        );

        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=0',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/edit?usp=sharing')
        );

        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=2',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=2')
        );

        // Satu link utama, gid ditentukan per kelas (override)
        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=7',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/edit', '7')
        );

        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=0',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/edit')
        );
    }
}
