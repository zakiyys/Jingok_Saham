<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Watchlist;
use App\Models\WatchlistItem;

class WatchlistService
{
    public function listTickers($user): array
    {
        $watchlist = Watchlist::firstOrCreate(['user_id' => $user->id], ['name' => 'Default']);

        return $watchlist->items()->with('company')->orderBy('sort_order')->get()
            ->pluck('company.ticker')
            ->values()
            ->all();
    }

    public function addTicker($user, string $ticker): void
    {
        $watchlist = Watchlist::firstOrCreate(['user_id' => $user->id], ['name' => 'Default']);
        $company = Company::where('ticker', $ticker)->firstOrFail();
        $sort = ($watchlist->items()->max('sort_order') ?? 0) + 1;

        WatchlistItem::firstOrCreate(
            ['watchlist_id' => $watchlist->id, 'company_id' => $company->id],
            ['sort_order' => $sort]
        );
    }

    public function removeTicker($user, string $ticker): void
    {
        $watchlist = Watchlist::firstOrCreate(['user_id' => $user->id], ['name' => 'Default']);
        $company = Company::where('ticker', $ticker)->first();

        if ($company) {
            WatchlistItem::where('watchlist_id', $watchlist->id)
                ->where('company_id', $company->id)
                ->delete();
        }
    }
}
