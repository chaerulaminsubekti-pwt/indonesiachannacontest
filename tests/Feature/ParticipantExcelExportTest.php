<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\Participant;
use App\Services\Export\ParticipantExcelExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantExcelExportTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'nama_event' => 'Test Contest',
            'slug' => 'test-contest',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-02',
            'venue' => 'Venue',
            'kategori' => 'regional',
            'status' => 'berjalan',
        ]);
    }

    public function test_build_creates_one_sheet_per_class_with_data(): void
    {
        $event = $this->makeEvent();
        $classA = EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Andrao', 'harga_tiket' => '200000.00']);
        $classB = EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Limbata Junior', 'harga_tiket' => '225000.00']);

        Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $classA->id,
            'nama_pemilik' => 'Fadil',
            'kota_asal' => 'Kediri',
            'nama_ikan' => 'Zeus',
            'team_sf' => 'Single Fighter',
            'no_hp' => '085608717174',
            'no_urut' => 1,
            'status' => Participant::STATUS_LUNAS,
            'fishin' => true,
            'fishout' => false,
        ]);
        Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $classA->id,
            'nama_pemilik' => 'Budi',
            'kota_asal' => 'Jakarta',
            'team_sf' => 'Gogo Team',
            'no_hp' => '0812-7625-0269',
            'no_urut' => 2,
            'status' => Participant::STATUS_MENUNGGU_VERIFIKASI,
            'fishin' => false,
            'fishout' => false,
        ]);
        Participant::create([
            'event_id' => $event->id,
            'event_class_id' => $classA->id,
            'nama_pemilik' => 'Ditolak',
            'status' => Participant::STATUS_REJECTED,
        ]);

        $spreadsheet = app(ParticipantExcelExport::class)->build($event);

        $this->assertCount(2, $spreadsheet->getSheetNames());
        $this->assertContains('Andrao', $spreadsheet->getSheetNames());
        $this->assertContains('Limbata Junior', $spreadsheet->getSheetNames());

        $sheet = $spreadsheet->getSheetByName('Andrao');
        $this->assertSame('DAFTAR PESERTA TEST CONTEST', $sheet->getCell('A1')->getValue());
        $this->assertSame('KELAS ANDRAO', $sheet->getCell('A3')->getValue());
        $this->assertSame('No', $sheet->getCell('A5')->getValue());
        $this->assertSame('Nama', $sheet->getCell('B5')->getValue());
        $this->assertSame('Fishout', $sheet->getCell('J5')->getValue());
        $this->assertSame(1, $sheet->getCell('A6')->getValue());
        $this->assertSame('Fadil', $sheet->getCell('B6')->getValue());
        $this->assertSame('Sudah', $sheet->getCell('I6')->getValue());
        $this->assertSame('Belum', $sheet->getCell('J6')->getValue());
        $this->assertSame('Lunas', $sheet->getCell('G6')->getValue());
        $this->assertSame('Gogo Team', $sheet->getCell('E7')->getValue());
        $this->assertSame('Menunggu Verifikasi', $sheet->getCell('G7')->getValue());

        $this->assertNull($sheet->getCell('A8')->getValue());

        $emptySheet = $spreadsheet->getSheetByName('Limbata Junior');
        $this->assertSame('KELAS LIMBATA JUNIOR', $emptySheet->getCell('A3')->getValue());
        $this->assertSame('No', $emptySheet->getCell('A5')->getValue());
        $this->assertNull($emptySheet->getCell('A6')->getValue());
    }

    public function test_sheet_name_sanitized_and_unique(): void
    {
        $event = $this->makeEvent();
        $class = EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Bad / Name ? * [x]', 'harga_tiket' => '100000.00']);
        EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Bad / Name ? * [x]', 'harga_tiket' => '100000.00']);

        $spreadsheet = app(ParticipantExcelExport::class)->build($event);

        $names = $spreadsheet->getSheetNames();
        $this->assertCount(2, $names);
        $this->assertStringNotContainsString('/', $names[0]);
        $this->assertStringNotContainsString('?', $names[0]);
        $this->assertStringNotContainsString('*', $names[0]);
        $this->assertStringNotContainsString('[', $names[0]);
        $this->assertStringNotContainsString(']', $names[0]);
        $this->assertStringEndsWith('(2)', $names[1]);
    }

    public function test_download_returns_xlsx_file(): void
    {
        $event = $this->makeEvent();
        EventClass::create(['event_id' => $event->id, 'nama_kelas' => 'Andrao', 'harga_tiket' => '200000.00']);

        $response = app(ParticipantExcelExport::class)->download($event);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('daftar-peserta-test-contest-', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition'));
    }
}
