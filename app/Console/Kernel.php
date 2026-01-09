<?php

namespace App\Console;

use App\Jobs\ComputeSignalsJob;
use App\Jobs\IngestMarketDataJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new IngestMarketDataJob())->dailyAt('18:00');
        $schedule->job(new ComputeSignalsJob())->everyTenMinutes();
    }
}
