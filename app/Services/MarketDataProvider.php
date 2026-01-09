<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Collection;

interface MarketDataProvider
{
    public function fetchSeries(Company $company, string $timeframe): Collection;
}
