<?php

namespace App\Services;

use Illuminate\Support\Collection;

class IndicatorService
{
    public function rsi(Collection $closes, int $period = 14): float
    {
        $gains = 0.0;
        $losses = 0.0;

        for ($i = 1; $i < min($closes->count(), $period + 1); $i++) {
            $diff = $closes[$i] - $closes[$i - 1];
            if ($diff >= 0) {
                $gains += $diff;
            } else {
                $losses += abs($diff);
            }
        }

        if ($period === 0) {
            return 50.0;
        }

        $avgGain = $gains / $period;
        $avgLoss = $losses / $period;

        if ($avgLoss == 0.0) {
            return 100.0;
        }

        $rs = $avgGain / $avgLoss;

        return round(100 - (100 / (1 + $rs)), 2);
    }

    public function macd(Collection $closes): array
    {
        $ema12Series = $this->emaSeries($closes, 12);
        $ema26Series = $this->emaSeries($closes, 26);
        $macdSeries = [];

        foreach ($ema12Series as $index => $ema12) {
            $macdSeries[] = $ema12 - ($ema26Series[$index] ?? $ema12);
        }

        $signalSeries = $this->emaSeries(collect($macdSeries), 9);
        $macdLine = end($macdSeries) ?: 0;
        $signalLine = end($signalSeries) ?: 0;
        $hist = $macdLine - $signalLine;

        return [
            'macd' => round($macdLine, 4),
            'signal' => round($signalLine, 4),
            'hist' => round($hist, 4),
        ];
    }

    public function volumeRatio(Collection $volumes, int $lookback): float
    {
        $latest = $volumes->last() ?? 0;
        $avg = $volumes->take(-$lookback)->average() ?: 1;

        return round($latest / $avg, 2);
    }

    private function emaSeries(Collection $series, int $period): array
    {
        $k = 2 / ($period + 1);
        $ema = $series->first() ?? 0.0;
        $output = [];

        foreach ($series as $value) {
            $ema = ($value * $k) + ($ema * (1 - $k));
            $output[] = $ema;
        }

        return $output;
    }
}
