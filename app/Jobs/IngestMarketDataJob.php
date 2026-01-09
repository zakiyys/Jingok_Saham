<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\MarketDataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class IngestMarketDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(MarketDataService $marketDataService): void
    {
        Company::where('is_active', true)->get()->each(function (Company $company) use ($marketDataService) {
            $marketDataService->getSeries($company, '1M');
            Log::info('Ingested synthetic data', ['ticker' => $company->ticker]);
        });
    }
}
