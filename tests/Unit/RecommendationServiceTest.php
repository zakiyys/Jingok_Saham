<?php

use App\Services\RecommendationService;

it('maps score to strong buy', function () {
    $service = new RecommendationService();

    expect($service->mapScore(80))->toBe('STRONG_BUY');
});

it('maps score to hold', function () {
    $service = new RecommendationService();

    expect($service->mapScore(0))->toBe('HOLD');
});

it('maps score to strong sell', function () {
    $service = new RecommendationService();

    expect($service->mapScore(-90))->toBe('STRONG_SELL');
});
