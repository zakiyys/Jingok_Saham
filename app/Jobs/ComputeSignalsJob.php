<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\SignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ComputeSignalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(SignalService $signalService): void
    {
        Company::where('is_active', true)->get()->each(function (Company $company) use ($signalService) {
            foreach (['1W', '1M', '3M'] as $timeframe) {
                $signalService->signalForTicker($company->ticker, $timeframe);
            }
            Log::info('Computed signals', ['ticker' => $company->ticker]);
        });
    }
}
