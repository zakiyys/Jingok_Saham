<?php

use App\Http\Controllers\Api\SignalController;
use App\Http\Controllers\Api\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/signal/{ticker}', [SignalController::class, 'show']);
    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{ticker}', [WatchlistController::class, 'destroy']);
});
