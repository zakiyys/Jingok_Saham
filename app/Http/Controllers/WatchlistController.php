<?php

namespace App\Http\Controllers;

use App\Http\Requests\WatchlistRequest;
use App\Services\WatchlistService;
use Illuminate\Http\RedirectResponse;

class WatchlistController extends Controller
{
    public function store(WatchlistRequest $request, WatchlistService $watchlistService): RedirectResponse
    {
        $watchlistService->addTicker($request->user(), $request->validated()['ticker']);

        return redirect()->route('dashboard');
    }

    public function destroy(WatchlistRequest $request, WatchlistService $watchlistService): RedirectResponse
    {
        $watchlistService->removeTicker($request->user(), $request->validated()['ticker']);

        return redirect()->route('dashboard');
    }
}
