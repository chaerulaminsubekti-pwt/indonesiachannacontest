<?php

namespace App\Services\RekapNilai;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SheetScoreParser
{
    /**
     * Kolom kriteria pada export CSV (0-based), mengikuti template sheet:
     * C..G (Penguasaan Tank..Kepekatan Bar), H kosong, I..U (Proporsi Bunga..Ekor).
     */
    public const CRITERIA_COLUMNS = [2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

    public const DEFAULT_CRITERIA = [
        'Penguasaan Tank', 'Mental', 'Warna Badan', 'Warna Fin', 'Kepekatan Bar',
        'Proporsi Bunga', 'Ekstra Bunga', 'Presisi Bar', 'Proporsi Badan', 'Mata',
        'Mulut', 'Sungut', 'Badan', 'Dorsal', 'Dayung', 'Dasi', 'Anal', 'Ekor',
    ];

    public const CACHE_TTL_SECONDS = 30;

    public function normalizeCsvUrl(string $url, ?string $gid = null): string
    {
        $url = trim($url);

        $spreadsheetId = null;
        $urlGid = null;

        if (preg_match('#/spreadsheets/d/([a-zA-Z0-9_-]+)#', $url, $matches)) {
            $spreadsheetId = $matches[1];
        }

        if (preg_match('/[?&#]gid=(\d+)/', $url, $gidMatch)) {
            $urlGid = $gidMatch[1];
        }

        if ($spreadsheetId === null) {
            // Bukan link Google Sheets — biarkan apa adanya (mis. sudah URL CSV / gviz)
            return $url;
        }

        $resolvedGid = $gid !== null && trim($gid) !== ''
            ? (int) $gid
            : (int) ($urlGid ?? 0);

        return sprintf(
            'https://docs.google.com/spreadsheets/d/%s/export?format=csv&gid=%d',
            $spreadsheetId,
            $resolvedGid
        );
    }

    public function fetch(string $url, ?string $gid = null): ?string
    {
        $csvUrl = $this->normalizeCsvUrl($url, $gid);
        $cacheKey = 'rekap_sheet:'.md5($csvUrl);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($csvUrl) {
            try {
                $response = Http::timeout(20)
                    ->withOptions(['allow_redirects' => true])
                    ->get($csvUrl);

                if (! $response->successful()) {
                    return null;
                }

                $body = $response->body();

                return trim($body) !== '' ? $body : null;
            } catch (\Throwable) {
                return null;
            }
        });
    }

    /**
     * @return array{criteria: array<int, string>, sessions: array<int, array{name: string, indices: array<int, int>}>, tanks: array<int, array{no_tank: int, rows: array<int, array{juri: string, values: array<int, float>}>}>}
     */
    public function parse(string $csv): array
    {
        $rows = $this->csvToRows($csv);
        $headerIndex = $this->findHeaderRow($rows);

        if ($headerIndex === null) {
            return [
                'criteria' => self::DEFAULT_CRITERIA,
                'sessions' => $this->defaultSessions(count(self::DEFAULT_CRITERIA)),
                'tanks' => [],
            ];
        }

        $criteria = $this->readCriteriaLabels($rows, $headerIndex);
        $sessions = $this->readSessions($rows, $headerIndex, count($criteria));
        $tanks = $this->groupTanks(array_slice($rows, $headerIndex + 1));

        return ['criteria' => $criteria, 'sessions' => $sessions, 'tanks' => $tanks];
    }

    /**
     * Baris header sesi (baris tepat di atas label kriteria) berisi label seperti
     * "SESI 1" dan "Sesi 2". Cell yang ter-merge hanya berisi label di kolom pertama,
     * sehingga label di-propagasikan ke kolom berikutnya.
     *
     * @param  array<int, array<int, string>>  $rows
     * @return array<int, array{name: string, indices: array<int, int>}>
     */
    private function readSessions(array $rows, int $headerIndex, int $criteriaCount): array
    {
        $labels = [];
        $last = null;

        foreach (self::CRITERIA_COLUMNS as $col) {
            $cell = trim((string) ($rows[$headerIndex][$col] ?? ''));
            if ($cell !== '') {
                $last = $cell;
            }
            $labels[] = $last;
        }

        $hasLabels = count(array_filter($labels, fn (?string $label): bool => $label !== null)) > 0;

        $sessions = [];
        $current = null;

        foreach (self::CRITERIA_COLUMNS as $pos => $col) {
            if ($hasLabels) {
                $label = $labels[$pos] ?? ($current['name'] ?? 'Sesi 1');
            } else {
                // Template default: 5 kriteria sesi 1, sisanya sesi 2
                $label = $pos < 5 ? 'Sesi 1' : 'Sesi 2';
            }

            if ($current === null || $current['name'] !== $label) {
                $sessions[] = ['name' => $label, 'indices' => []];
                $current = &$sessions[count($sessions) - 1];
            }

            $current['indices'][] = $pos;
        }

        return $sessions;
    }

    /**
     * @return array<int, array{name: string, indices: array<int, int>}>
     */
    private function defaultSessions(int $criteriaCount): array
    {
        return [
            ['name' => 'Sesi 1', 'indices' => range(0, min(4, $criteriaCount - 1))],
            ['name' => 'Sesi 2', 'indices' => range(min(5, $criteriaCount), $criteriaCount - 1)],
        ];
    }

    /**
     * @param  array<int, array<int, string>>  $rows
     */
    private function findHeaderRow(array $rows): ?int
    {
        foreach ($rows as $index => $row) {
            if (trim((string) ($row[0] ?? '')) === 'No Tank') {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param  array<int, array<int, string>>  $rows
     * @return array<int, string>
     */
    private function readCriteriaLabels(array $rows, int $headerIndex): array
    {
        $result = [];

        foreach (self::CRITERIA_COLUMNS as $position => $col) {
            $label = '';
            foreach ([$headerIndex + 2, $headerIndex + 1] as $rowIndex) {
                $cell = trim((string) ($rows[$rowIndex][$col] ?? ''));
                if ($cell !== '' && ! is_numeric($cell)) {
                    $label = $cell;
                    break;
                }
            }

            $result[] = $label !== '' ? $label : (self::DEFAULT_CRITERIA[$position] ?? '');
        }

        return $result;
    }

    /**
     * @param  array<int, array<int, string>>  $rows
     * @return array<int, array{no_tank: int, rows: array<int, array{juri: string, values: array<int, float>}>}>
     */
    private function groupTanks(array $rows): array
    {
        $tanks = [];
        $currentIndex = null;

        foreach ($rows as $row) {
            $tankNo = trim((string) ($row[0] ?? ''));
            $juri = trim((string) ($row[1] ?? ''));

            $isNewTank = $tankNo !== '' && is_numeric($tankNo);

            if ($isNewTank) {
                $tanks[] = ['no_tank' => (int) $tankNo, 'rows' => []];
                $currentIndex = count($tanks) - 1;
            }

            if ($currentIndex === null) {
                continue;
            }

            $values = [];
            foreach (self::CRITERIA_COLUMNS as $col) {
                $value = trim((string) ($row[$col] ?? ''));
                $values[] = ($value === '' || ! is_numeric($value)) ? 0 : (float) $value;
            }

            $isJudgeRow = $juri !== '' && $juri !== 'Kosong' && (array_sum($values) > 0 || count(array_filter($values)) > 0);

            if ($isJudgeRow) {
                $tanks[$currentIndex]['rows'][] = ['juri' => $juri, 'values' => $values];

                continue;
            }

            // Baris kosong / penanda "Kosong" menutup tank aktif
            if (! $isNewTank) {
                $currentIndex = null;
            }
        }

        return $tanks;
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function csvToRows(string $csv): array
    {
        $rows = [];
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $csv);
        rewind($handle);

        while (($line = fgetcsv($handle)) !== false) {
            $rows[] = $line;
        }

        fclose($handle);

        return $rows;
    }
}
