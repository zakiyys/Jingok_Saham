<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SignalService;
use Illuminate\Http\Request;

class SignalController extends Controller
{
    public function show(string $ticker, Request $request, SignalService $signalService)
    {
        $timeframe = $request->query('timeframe', '1W');
        $signal = $signalService->signalForTicker($ticker, $timeframe);

        return response()->json($signal);
    }
}
