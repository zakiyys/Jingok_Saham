<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PriceTargetService
{
    public function targets(Collection $closes, int $direction): array
    {
        $sigma = $this->sigma($closes);
        $last = $closes->last() ?? 0;

        $multiplier = $direction >= 0 ? 1 : -1;

        return [
            'conservative' => $this->buildTarget($last, 0.5, $sigma, $multiplier),
            'moderate' => $this->buildTarget($last, 1.0, $sigma, $multiplier),
            'aggressive' => $this->buildTarget($last, 1.6, $sigma, $multiplier),
        ];
    }

    private function sigma(Collection $closes): float
    {
        $returns = [];
        for ($i = 1; $i < $closes->count(); $i++) {
            if ($closes[$i - 1] == 0) {
                continue;
            }
            $returns[] = ($closes[$i] - $closes[$i - 1]) / $closes[$i - 1];
        }

        if (count($returns) < 2) {
            return 0.02;
        }

        $mean = array_sum($returns) / count($returns);
        $variance = array_sum(array_map(fn ($r) => ($r - $mean) ** 2, $returns)) / (count($returns) - 1);

        return sqrt($variance);
    }

    private function buildTarget(float $last, float $factor, float $sigma, int $direction): array
    {
        $targetPrice = $last * (1 + ($sigma * $factor * $direction));
        $pct = $last > 0 ? (($targetPrice - $last) / $last) * 100 : 0;

        return [
            'price' => round($targetPrice, 0),
            'pct' => round($pct, 2),
        ];
    }
}
