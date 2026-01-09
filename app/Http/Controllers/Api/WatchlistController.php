<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WatchlistRequest;
use App\Services\WatchlistService;

class WatchlistController extends Controller
{
    public function index(WatchlistRequest $request, WatchlistService $watchlistService)
    {
        return response()->json($watchlistService->listTickers($request->user()));
    }

    public function store(WatchlistRequest $request, WatchlistService $watchlistService)
    {
        $watchlistService->addTicker($request->user(), $request->validated()['ticker']);

        return response()->json(['status' => 'ok']);
    }

    public function destroy(WatchlistRequest $request, string $ticker, WatchlistService $watchlistService)
    {
        $watchlistService->removeTicker($request->user(), $ticker);

        return response()->json(['status' => 'ok']);
    }
}
