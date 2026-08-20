<?php

namespace App\Services\Export;

use App\Models\Event;
use App\Models\Participant;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ParticipantExcelExport
{
    public const HEADERS = [
        'No', 'Nama', 'Alamat', 'Nama Ikan', 'Team', 'No HP', 'Keterangan', 'Keterangan', 'Fishin', 'Fishout',
    ];

    public function build(Event $event): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->removeSheetByIndex(0);

        $title = 'DAFTAR PESERTA '.strtoupper((string) $event->nama_event);

        $classes = $event->classes()
            ->with(['participants' => fn ($query) => $query
                ->where('status', '!=', Participant::STATUS_REJECTED)
                ->orderByRaw('no_urut IS NULL')
                ->orderByRaw('no_urut + 0')
                ->orderBy('id')])
            ->orderBy('id')
            ->get();

        $usedNames = [];

        foreach ($classes as $index => $class) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($this->sheetName((string) $class->nama_kelas, $index, $usedNames));

            $sheet->setCellValue('A1', $title);
            $sheet->setCellValue('A3', 'KELAS '.strtoupper((string) $class->nama_kelas));

            foreach (self::HEADERS as $col => $header) {
                $sheet->setCellValue(self::column($col + 1).'5', $header);
            }

            $row = 6;
            foreach ($class->participants as $participant) {
                $sheet->setCellValue('A'.$row, $participant->no_urut ?? ($row - 5));
                $sheet->setCellValue('B'.$row, $participant->nama_pemilik ?: $participant->nama_peserta);
                $sheet->setCellValue('C'.$row, $participant->kota_asal);
                $sheet->setCellValue('D'.$row, $participant->nama_ikan);
                $sheet->setCellValue('E'.$row, $participant->team_sf);
                $sheet->setCellValue('F'.$row, $participant->no_hp ?: $participant->no_wa);
                $sheet->setCellValue('G'.$row, Participant::statuses()[$participant->status] ?? ucfirst((string) $participant->status));
                $sheet->setCellValue('H'.$row, $participant->keterangan);
                $sheet->setCellValue('I'.$row, $this->sudahBelum($participant->fishin));
                $sheet->setCellValue('J'.$row, $this->sudahBelum($participant->fishout));
                $row++;
            }

            $this->styleSheet($sheet, $row - 1);
        }

        if ($spreadsheet->getSheetCount() === 0) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('Peserta');
            $sheet->setCellValue('A1', $title);

            foreach (self::HEADERS as $col => $header) {
                $sheet->setCellValue(self::column($col + 1).'5', $header);
            }

            $this->styleSheet($sheet, 5);
        }

        return $spreadsheet;
    }

    public function download(Event $event): BinaryFileResponse
    {
        $writer = new Xlsx($this->build($event));
        $path = tempnam(sys_get_temp_dir(), 'peserta').'.xlsx';
        $writer->save($path);

        $filename = 'daftar-peserta-'.($event->slug ?: strtolower(str_replace(' ', '-', (string) $event->nama_event))).'-'
            .now('Asia/Jakarta')->format('Ymd-His').'.xlsx';

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    public static function column(int $index): string
    {
        return Coordinate::stringFromColumnIndex($index);
    }

    private function sheetName(string $name, int $index, array &$usedNames): string
    {
        $base = preg_replace('/[\\\\\/\?\*\[\]:]/', '', $name);
        $base = mb_substr($base, 0, 31);
        $candidate = $base;

        if (isset($usedNames[$candidate])) {
            $usedNames[$candidate]++;
            $suffix = ' ('.$usedNames[$candidate].')';
            $candidate = mb_substr($base, 0, 31 - strlen($suffix)).$suffix;
        } else {
            $usedNames[$candidate] = 1;
        }

        return $candidate !== '' ? $candidate : 'Kelas '.($index + 1);
    }

    private function sudahBelum(?bool $value): string
    {
        if ($value === null) {
            return '';
        }

        return $value ? 'Sudah' : 'Belum';
    }

    private function styleSheet($sheet, int $lastRow): void
    {
        $headerFill = [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'DDEBF7'],
        ];
        $thinBorder = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];
        $center = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]];

        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A5:J5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => $headerFill,
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        if ($lastRow >= 6) {
            $sheet->getStyle('A6:J'.$lastRow)->applyFromArray($thinBorder);
            $sheet->getStyle('A6:A'.$lastRow)->applyFromArray($center);
            $sheet->getStyle('I6:J'.$lastRow)->applyFromArray($center);
        }

        foreach ([1 => 6, 2 => 30, 3 => 18, 4 => 20, 5 => 22, 6 => 18, 7 => 20, 8 => 20, 9 => 10, 10 => 10] as $col => $width) {
            $sheet->getColumnDimension(self::column($col))->setWidth($width);
        }

        $sheet->getRowDimension(5)->setRowHeight(22);
        $sheet->freezePane('A6');
    }
}
