<?php

namespace Tests\Unit;

use App\Services\Banding\BandingParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BandingParserTest extends TestCase
{
    protected function sampleCsv(): string
    {
        return implode("\n", [
            '"Timestamp","Email Address","Nama Peserta","Kelas","No Tank","Sesi Banding ","Juri Yang Di Banding","Alasan","Vidio 1","Vidio 1"',
            '"9/8/2026 14:28:27","a@example.com","Chaerul","Yellow Progress","1","Sesi 1","Gus Tio","Mental","https://drive.google.com/open?id=1",""',
            '"10/8/2026 09:15:00","b@example.com","Budi","Andrao","2","Sesi 2","Kang Niko","Ekor","https://drive.google.com/open?id=2",""',
            '"8/8/2026 20:00:00","c@example.com","Ani","Andrao","3","Sesi 1","Gus Tio","Mental","",""',
        ]);
    }

    #[Test]
    public function parses_headers_and_detects_key_columns(): void
    {
        $parsed = (new BandingParser)->parse($this->sampleCsv());

        $this->assertCount(10, $parsed['headers']);
        $this->assertCount(3, $parsed['rows']);
        $this->assertSame(0, $parsed['timeIndex']);
        $this->assertSame(3, $parsed['kelasIndex']);
        $this->assertSame(2, $parsed['namaIndex']);
        $this->assertNull($parsed['keputusanIndex']);
    }

    #[Test]
    public function parses_indonesian_date_format_day_first(): void
    {
        $parser = new BandingParser;

        $date = $parser->parseTimestamp('9/8/2026 14:28:27');

        $this->assertSame(9, $date->day);
        $this->assertSame(8, $date->month);
        $this->assertSame(2026, $date->year);
    }

    #[Test]
    public function sorts_by_class_then_newest_first(): void
    {
        $parser = new BandingParser;
        $parsed = $parser->parse($this->sampleCsv());
        $sorted = $parser->sortRows($parsed['rows'], $parsed['kelasIndex'], $parsed['timeIndex'], 'desc');

        $this->assertSame(['Budi', 'Ani', 'Chaerul'], array_map(fn ($r) => $r[2], $sorted));
    }

    #[Test]
    public function sorts_ascending_oldest_first_within_class(): void
    {
        $parser = new BandingParser;
        $parsed = $parser->parse($this->sampleCsv());
        $sorted = $parser->sortRows($parsed['rows'], $parsed['kelasIndex'], $parsed['timeIndex'], 'asc');

        $this->assertSame(['Ani', 'Budi', 'Chaerul'], array_map(fn ($r) => $r[2], $sorted));

        $onlyAndrao = array_values(array_filter($sorted, fn ($r) => $r[3] === 'Andrao'));
        $this->assertSame('Ani', $onlyAndrao[0][2]);
        $this->assertSame('Budi', $onlyAndrao[1][2]);
    }

    #[Test]
    public function detects_email_columns(): void
    {
        $parser = new BandingParser;

        $this->assertTrue($parser->isEmailColumn('Email Address'));
        $this->assertFalse($parser->isEmailColumn('Nama Peserta'));
    }

    #[Test]
    public function builds_export_and_gviz_urls(): void
    {
        $parser = new BandingParser;

        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/export?format=csv&gid=7',
            $parser->normalizeCsvUrl('https://docs.google.com/spreadsheets/d/abc123/edit', '7')
        );
        $this->assertSame(
            'https://docs.google.com/spreadsheets/d/abc123/gviz/tq?tqx=out:csv&gid=7',
            $parser->gvizUrl('https://docs.google.com/spreadsheets/d/abc123/edit', '7')
        );
        $this->assertNull($parser->gvizUrl('https://example.com/data.csv'));
    }
}
