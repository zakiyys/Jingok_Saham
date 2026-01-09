<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/watchlist/add', [WatchlistController::class, 'store'])->name('watchlist.add');
    Route::post('/watchlist/remove', [WatchlistController::class, 'destroy'])->name('watchlist.remove');
});
