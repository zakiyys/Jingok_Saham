<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Services\MarketDataService;
use Illuminate\Database\Seeder;

class EodPriceSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(MarketDataService::class);

        Company::all()->each(function (Company $company) use ($service) {
            $service->getSeries($company, '3M');
        });
    }
}
