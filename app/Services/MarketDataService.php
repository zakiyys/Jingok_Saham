<?php

namespace App\Services;

use App\Models\Company;
use App\Models\EodPrice;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MarketDataService
{
    public function getSeries(Company $company, string $timeframe): Collection
    {
        $days = $this->daysForTimeframe($timeframe);
        $existing = $company->prices()->orderByDesc('date')->take($days)->get()->sortBy('date');

        if ($existing->count() >= $days) {
            return $existing->values();
        }

        $this->generateSyntheticSeries($company, max($days, 250));

        return $company->prices()->orderByDesc('date')->take($days)->get()->sortBy('date')->values();
    }

    private function generateSyntheticSeries(Company $company, int $days): void
    {
        $start = Carbon::now()->subDays($days);
        $price = random_int(500, 5000);
        $volatility = random_int(15, 40) / 1000;

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $drift = (random_int(-15, 18) / 1000);
            $change = $price * ($drift + $volatility * (random_int(-10, 10) / 100));
            $close = max(100, $price + $change);
            $high = max($close, $price) * (1 + random_int(0, 5) / 100);
            $low = min($close, $price) * (1 - random_int(0, 5) / 100);
            $volume = random_int(800000, 3500000);

            EodPrice::updateOrCreate(
                ['company_id' => $company->id, 'date' => $date->toDateString()],
                [
                    'open' => $price,
                    'high' => $high,
                    'low' => $low,
                    'close' => $close,
                    'volume' => $volume,
                ]
            );

            $price = $close;
        }
    }

    private function daysForTimeframe(string $timeframe): int
    {
        return match ($timeframe) {
            '1W' => 20,
            '1M' => 90,
            default => 180,
        };
    }
}
