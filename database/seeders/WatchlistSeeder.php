<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Watchlist;
use App\Models\WatchlistItem;
use Illuminate\Database\Seeder;

class WatchlistSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@sahambuddy.local')->first();
        if (! $user) {
            return;
        }

        $watchlist = Watchlist::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => 'Default']
        );

        $tickers = ['ANTM', 'BREN', 'BRMS', 'BUMI', 'DEWA', 'ELIT', 'INET'];

        foreach ($tickers as $index => $ticker) {
            $company = Company::where('ticker', $ticker)->first();
            if (! $company) {
                continue;
            }

            WatchlistItem::firstOrCreate(
                ['watchlist_id' => $watchlist->id, 'company_id' => $company->id],
                ['sort_order' => $index + 1]
            );
        }
    }
}
