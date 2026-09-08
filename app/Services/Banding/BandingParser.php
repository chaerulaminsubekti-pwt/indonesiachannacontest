<?php

namespace App\Services\Banding;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BandingParser
{
    public const CACHE_TTL_SECONDS = 30;

    /**
     * Ubah link Sheets menjadi URL export CSV.
     */
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

    /**
     * URL fallback via gviz (untuk sheet yang export-nya ditolak tapi bisa dibaca).
     */
    public function gvizUrl(string $url, ?string $gid = null): ?string
    {
        $url = trim($url);

        if (! preg_match('#/spreadsheets/d/([a-zA-Z0-9_-]+)#', $url, $matches)) {
            return null;
        }

        $resolvedGid = $gid !== null && trim($gid) !== '' ? (int) $gid : 0;

        return sprintf(
            'https://docs.google.com/spreadsheets/d/%s/gviz/tq?tqx=out:csv&gid=%d',
            $matches[1],
            $resolvedGid
        );
    }

    /**
     * Ambil CSV: coba export dulu, gagal -> fallback gviz.
     */
    public function fetch(string $url, ?string $gid = null): ?string
    {
        foreach (array_filter([$this->normalizeCsvUrl($url, $gid), $this->gvizUrl($url, $gid)]) as $csvUrl) {
            $body = $this->fetchUrl($csvUrl);
            if ($body !== null) {
                return $body;
            }
        }

        return null;
    }

    protected function fetchUrl(string $csvUrl): ?string
    {
        $cacheKey = 'banding_sheet:'.md5($csvUrl);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($csvUrl) {
            try {
                $response = Http::timeout(20)
                    ->withOptions(['allow_redirects' => true])
                    ->get($csvUrl);

                if (! $response->successful()) {
                    return null;
                }

                $body = $response->body();

                if (trim($body) === '' || str_contains($body, '<HTML>')) {
                    return null;
                }

                return $body;
            } catch (\Throwable) {
                return null;
            }
        });
    }

    /**
     * @return array{headers: array<int, string>, rows: array<int, array<int, string>>, timeIndex: ?int, kelasIndex: ?int, keputusanIndex: ?int}
     */
    public function parse(string $csv): array
    {
        $rows = $this->csvToRows($csv);

        $headerIndex = null;
        foreach ($rows as $index => $row) {
            if (count(array_filter($row, fn ($c) => trim((string) $c) !== '')) >= 2) {
                $headerIndex = $index;
                break;
            }
        }

        if ($headerIndex === null) {
            return ['headers' => [], 'rows' => [], 'timeIndex' => null, 'kelasIndex' => null, 'keputusanIndex' => null, 'namaIndex' => null];
        }

        $headers = array_map(fn ($c) => trim((string) $c), $rows[$headerIndex]);
        $data = array_values(array_filter(
            array_slice($rows, $headerIndex + 1),
            fn ($row) => count(array_filter($row, fn ($c) => trim((string) $c) !== '')) > 0
        ));

        return [
            'headers' => $headers,
            'rows' => array_map(fn ($row) => array_map(fn ($c) => trim((string) $c), $row), $data),
            'timeIndex' => $this->findHeaderIndex($headers, ['timestamp', 'waktu', 'tanggal']),
            'kelasIndex' => $this->findHeaderIndex($headers, ['kelas', 'class', 'kategori']),
            'keputusanIndex' => $this->findHeaderIndex($headers, ['keputusan', 'status', 'hasil', 'verdict']),
            'namaIndex' => $this->findHeaderIndex($headers, ['nama peserta', 'nama', 'name', 'peserta']),
        ];
    }

    /**
     * @param  array<int, string>  $headers
     */
    protected function findHeaderIndex(array $headers, array $keywords): ?int
    {
        foreach ($headers as $index => $header) {
            $lower = mb_strtolower($header);
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return $index;
                }
            }
        }

        return null;
    }

    /**
     * Parse timestamp fleksibel: dukung format Indonesia (d/m/Y) dan US (m/d/Y).
     */
    public function parseTimestamp(?string $value): ?Carbon
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        foreach (['j/n/Y H:i:s', 'j/n/Y H:i', 'j/n/Y', 'n/j/Y H:i:s', 'n/j/Y H:i', 'n/j/Y'] as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);
                if ($parsed !== false) {
                    // Format d/m vs m/d ambigu bila keduanya <= 12: prioritaskan d/m (lokal Indonesia).
                    // Bila hari > 12, hanya format d/m yang valid — createFromFormat m/d akan gagal/melempar.
                    return $parsed->startOfSecond();
                }
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->startOfSecond();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Urutkan baris: kelas A-Z dulu, lalu timestamp (desc = terbaru dulu).
     *
     * @param  array<int, array<int, string>>  $rows
     * @return array<int, array<int, string>>
     */
    public function sortRows(array $rows, ?int $kelasIndex, ?int $timeIndex, string $direction = 'desc'): array
    {
        $mult = strtolower($direction) === 'asc' ? 1 : -1;

        usort($rows, function ($a, $b) use ($kelasIndex, $timeIndex, $mult) {
            if ($kelasIndex !== null) {
                $cmp = strcmp(
                    mb_strtolower(trim((string) ($a[$kelasIndex] ?? ''))),
                    mb_strtolower(trim((string) ($b[$kelasIndex] ?? '')))
                );
                if ($cmp !== 0) {
                    return $cmp;
                }
            }

            if ($timeIndex !== null) {
                $ta = $this->parseTimestamp($a[$timeIndex] ?? null);
                $tb = $this->parseTimestamp($b[$timeIndex] ?? null);

                if ($ta && $tb) {
                    if (! $ta->equalTo($tb)) {
                        return ($ta->lessThan($tb) ? -1 : 1) * $mult;
                    }
                } elseif ($ta || $tb) {
                    return $ta ? -1 : 1;
                }
            }

            return 0;
        });

        return $rows;
    }

    /**
     * Kolom email disembunyikan dari tampilan publik (privasi).
     *
     * @param  array<int, string>  $headers
     */
    public function isEmailColumn(string $header): bool
    {
        $lower = mb_strtolower($header);

        return str_contains($lower, 'email') || str_contains($lower, 'e-mail');
    }

    /**
     * @return array<int, array<int, string>>
     */
    protected function csvToRows(string $csv): array
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
