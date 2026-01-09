<?php

use App\Services\IndicatorService;
use Illuminate\Support\Collection;

it('calculates RSI within expected range', function () {
    $service = new IndicatorService();
    $closes = new Collection([100, 102, 101, 103, 104, 106, 105, 107, 108, 107, 109, 111, 110, 112, 113]);

    $rsi = $service->rsi($closes);

    expect($rsi)->toBeGreaterThan(30)->toBeLessThan(90);
});

it('calculates MACD values', function () {
    $service = new IndicatorService();
    $closes = new Collection(range(100, 140));

    $macd = $service->macd($closes);

    expect($macd['macd'])->toBeNumeric();
    expect($macd['signal'])->toBeNumeric();
    expect($macd['hist'])->toBeNumeric();
});
