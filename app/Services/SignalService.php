<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Signal;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Cache;

class SignalService
{
    public function __construct(
        private MarketDataService $marketDataService,
        private IndicatorService $indicatorService,
        private RecommendationService $recommendationService,
        private PriceTargetService $priceTargetService,
    ) {
    }

    public function signalsForUser($user): array
    {
        $tickers = $this->watchlistTickers($user);

        return collect($tickers)
            ->map(fn ($ticker) => $this->signalForTicker($ticker, '1W'))
            ->values()
            ->all();
    }

    public function signalForTicker(string $ticker, string $timeframe): array
    {
        $company = Company::where('ticker', $ticker)->firstOrFail();
        $cacheKey = "signal:{$company->id}:{$timeframe}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($company, $timeframe) {
            $series = $this->marketDataService->getSeries($company, $timeframe);
            $closes = $series->pluck('close');
            $volumes = $series->pluck('volume');

            $rsi = $this->indicatorService->rsi($closes);
            $macd = $this->indicatorService->macd($closes);
            $volRatio = $this->indicatorService->volumeRatio($volumes, $this->volumeLookback($timeframe));
            $scoring = $this->recommendationService->score($rsi, $macd['macd'], $macd['signal'], $macd['hist'], $volRatio);
            $direction = $scoring['score'] >= 0 ? 1 : -1;
            $targets = $this->priceTargetService->targets($closes, $direction);

            $last = $closes->last() ?? 0;
            $previous = $closes->slice(-2, 1)->first() ?? $last;
            $pctChange = $previous ? (($last - $previous) / $previous) * 100 : 0;

            $signal = Signal::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'timeframe' => $timeframe,
                ],
                [
                    'as_of_date' => $series->last()?->date,
                    'last_close' => $last,
                    'pct_change' => $pctChange,
                    'rsi14' => $rsi,
                    'macd' => $macd['macd'],
                    'macd_signal' => $macd['signal'],
                    'macd_hist' => $macd['hist'],
                    'vol_ratio' => $volRatio,
                    'score' => $scoring['score'],
                    'recommendation' => $scoring['recommendation'],
                    'reasons' => $scoring['reasons'],
                    'targets' => $targets,
                    'computed_at' => now(),
                ]
            );

            return $this->formatSignal($company, $signal, $targets);
        });
    }

    private function formatSignal(Company $company, Signal $signal, array $targets): array
    {
        $freshThreshold = now()->subMinutes(10);
        $recommendationLabel = match ($signal->recommendation) {
            'STRONG_BUY' => 'STRONG BUY',
            'BUY_ACCUMULATE' => 'BUY / ACCUMULATE',
            'HOLD' => 'HOLD',
            'SELL' => 'SELL',
            'STRONG_SELL' => 'STRONG SELL',
            default => 'HOLD',
        };

        return [
            'ticker' => $company->ticker,
            'company' => $company->name,
            'sector' => $company->sector,
            'timeframe' => $signal->timeframe,
            'last_close_formatted' => $this->formatIdr($signal->last_close),
            'pct_change' => $signal->pct_change,
            'pct_change_formatted' => sprintf('%+.2f%%', $signal->pct_change),
            'recommendation' => $signal->recommendation,
            'recommendation_label' => $recommendationLabel,
            'rsi' => number_format($signal->rsi14, 2),
            'macd' => number_format($signal->macd, 4),
            'vol_ratio' => number_format($signal->vol_ratio, 2),
            'macd_trend' => $signal->macd > $signal->macd_signal ? 'up' : 'down',
            'rsi_trend' => $signal->rsi14 >= 50 ? 'up' : 'down',
            'vol_trend' => $signal->vol_ratio >= 1 ? 'up' : 'down',
            'targets' => $this->formatTargets($targets),
            'reasons' => $signal->reasons ?? [],
            'fresh' => $signal->computed_at?->greaterThanOrEqualTo($freshThreshold) ?? false,
        ];
    }

    private function formatTargets(array $targets): array
    {
        return collect($targets)->map(function ($target) {
            $priceFormatted = $this->formatIdr($target['price']);
            $pctFormatted = sprintf('%+.2f%%', $target['pct']);

            return [
                'price_formatted' => $priceFormatted,
                'pct_formatted' => $pctFormatted,
            ];
        })->all();
    }

    private function formatIdr(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    private function watchlistTickers($user): array
    {
        $watchlist = Watchlist::firstOrCreate(['user_id' => $user->id], ['name' => 'Default']);

        return $watchlist->items()->with('company')->orderBy('sort_order')->get()
            ->pluck('company.ticker')
            ->values()
            ->all();
    }

    private function volumeLookback(string $timeframe): int
    {
        return match ($timeframe) {
            '1W' => 5,
            '1M' => 20,
            default => 60,
        };
    }
}
