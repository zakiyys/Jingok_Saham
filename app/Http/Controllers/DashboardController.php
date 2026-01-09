<?php

namespace App\Http\Controllers;

use App\Services\SignalService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, SignalService $signalService)
    {
        $user = $request->user();
        $signals = $signalService->signalsForUser($user);

        return view('dashboard', [
            'signals' => $signals,
            'signalsByTicker' => collect($signals)->keyBy('ticker'),
            'tickers' => collect($signals)->pluck('ticker')->values(),
            'activeTicker' => $signals[0]['ticker'] ?? null,
            'lastUpdate' => now()->format('d M Y H:i'),
        ]);
    }
}
