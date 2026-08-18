<?php

namespace App\Services\RekapNilai;

class RekapCalculator
{
    /**
     * Urutan prioritas tie-break "HEAD TO HEAD" (kolom), mengikuti formula sheet:
     * (C8+C9)/10^2, (I8+I9)/10^3, (J8+J9)/10^4, (E8+E9)/10^5, (F8+F9)/10^6,
     * (K8+K9)/10^7, (G8+G9)/10^8, (D8+D9)/10^9, (L8+L9)/10^10, (M8+M9)/10^11,
     * (N8+N9)/10^12, (O8+O9)/10^13, (P8+P9)/10^14, (Q8+Q9)/10^15, (R8+R9)/10^16,
     * (S8+S9)/10^17, (T8+T9)/10^18, (U8+U9)/10^19.
     *
     * Index di sini mengacu posisi pada array values (0..17) sesuai CRITERIA_COLUMNS:
     * C=0, D=1, E=2, F=3, G=4, I=5, J=6, K=7, L=8, M=9, N=10, O=11, P=12, Q=13, R=14, S=15, T=16, U=17.
     */
    public const PRIORITY_ORDER = [0, 5, 6, 2, 3, 7, 4, 1, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];

    /**
     * @param  array{criteria: array<int, string>, tanks: array<int, array{no_tank: int, rows: array<int, array{juri: string, values: array<int, float>}>}>}  $parsed
     * @return array{criteria: array<int, string>, tanks: array<int, array<string, mixed>>}
     */
    public function calculate(array $parsed): array
    {
        $tanks = [];

        foreach ($parsed['tanks'] as $tank) {
            $judges = [];
            $grandTotal = 0;
            $hasData = false;

            foreach ($tank['rows'] as $row) {
                $subtotal = (int) array_sum($row['values']);
                $grandTotal += $subtotal;

                if ($subtotal > 0 || count(array_filter($row['values'])) > 0) {
                    $hasData = true;
                }

                $judges[] = [
                    'juri' => $row['juri'],
                    'values' => $row['values'],
                    'subtotal' => $subtotal,
                ];
            }

            [$helper, $sortKey] = $this->computeHelper($grandTotal, $judges);

            $tanks[] = [
                'no_tank' => $tank['no_tank'],
                'judges' => $judges,
                'grand_total' => $grandTotal,
                'helper' => $helper,
                'sort_key' => $sortKey,
                'has_data' => $hasData,
            ];
        }

        // RANKING POINT = RANK(grand_total, seluruh range, desc) — peringkat kompetisi, tie berbagi.
        $grandTotals = array_column($tanks, 'grand_total');
        foreach ($tanks as &$tank) {
            $tank['ranking_point'] = $this->competitionRank($tank['grand_total'], $grandTotals);
        }
        unset($tank);

        // RANKING JUARA = RANK(helper, seluruh range, desc) — helper unik sehingga tanpa tie.
        $sortKeys = array_column($tanks, 'sort_key');
        foreach ($tanks as &$tank) {
            $tank['ranking_juara'] = $this->competitionRankString($tank['sort_key'], $sortKeys);
        }
        unset($tank);

        // Hanya tampilkan tank yang memiliki nilai.
        $result = array_values(array_filter($tanks, fn (array $tank): bool => $tank['has_data']));

        usort($result, fn (array $a, array $b): int => $a['ranking_juara'] <=> $b['ranking_juara']);

        return ['criteria' => $parsed['criteria'], 'tanks' => $result];
    }

    /**
     * Hitung helper (float, untuk tampilan) dan sort_key (string, untuk peringkat eksak).
     *
     * @param  array<int, array{juri: string, values: array<int, float>, subtotal: int}>  $judges
     * @return array{0: float, 1: string}
     */
    private function computeHelper(int $grandTotal, array $judges): array
    {
        $pairSums = [];

        foreach (self::PRIORITY_ORDER as $position) {
            $sum = 0;
            foreach ($judges as $judge) {
                $sum += (int) $judge['values'][$position];
            }
            $pairSums[] = $sum;
        }

        $helper = (float) $grandTotal;
        $power = 2;
        foreach ($pairSums as $sum) {
            $helper += $sum / (10 ** $power);
            $power++;
        }

        $sortKey = str_pad((string) $grandTotal, 4, '0', STR_PAD_LEFT);
        foreach ($pairSums as $sum) {
            $sortKey .= str_pad((string) $sum, 2, '0', STR_PAD_LEFT);
        }

        return [$helper, $sortKey];
    }

    /**
     * Perintah peringkat kompetisi (desc): 1 + jumlah nilai yang lebih tinggi.
     *
     * @param  array<int, int>  $values
     */
    private function competitionRank(int $value, array $values): int
    {
        $rank = 1;
        foreach ($values as $other) {
            if ($other > $value) {
                $rank++;
            }
        }

        return $rank;
    }

    /**
     * @param  array<int, string>  $values
     */
    private function competitionRankString(string $value, array $values): int
    {
        $rank = 1;
        foreach ($values as $other) {
            if (strcmp($other, $value) > 0) {
                $rank++;
            }
        }

        return $rank;
    }
}
