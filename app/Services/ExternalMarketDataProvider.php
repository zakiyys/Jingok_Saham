<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Collection;

class ExternalMarketDataProvider implements MarketDataProvider
{
    public function fetchSeries(Company $company, string $timeframe): Collection
    {
        return collect();
    }
}
