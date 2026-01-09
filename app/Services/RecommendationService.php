<?php

namespace App\Services;

class RecommendationService
{
    public function score(float $rsi, float $macd, float $signal, float $hist, float $volRatio): array
    {
        $score = 0;
        $reasons = [];

        if ($rsi < 30) {
            $score += 35;
            $reasons[] = 'RSI oversold < 30';
        } elseif ($rsi < 55) {
            $score += 10;
            $reasons[] = 'RSI stabil 30-55';
        } elseif ($rsi < 70) {
            $score += 25;
            $reasons[] = 'RSI momentum 55-70';
        } else {
            $score -= 25;
            $reasons[] = 'RSI overbought > 70';
        }

        if ($macd > $signal) {
            $score += 20;
            $reasons[] = 'MACD di atas signal';
        } else {
            $score -= 20;
            $reasons[] = 'MACD di bawah signal';
        }

        if ($hist > 0) {
            $score += 10;
        } else {
            $score -= 10;
        }

        if ($volRatio > 1.5) {
            $score += 15;
            $reasons[] = 'Volume menguat (>1.5x)';
        } elseif ($volRatio < 0.8) {
            $score -= 10;
            $reasons[] = 'Volume melemah (<0.8x)';
        }

        $score = max(-100, min(100, $score));

        return [
            'score' => $score,
            'recommendation' => $this->mapScore($score),
            'reasons' => array_slice($reasons, 0, 5),
        ];
    }

    public function mapScore(int $score): string
    {
        return match (true) {
            $score >= 70 => 'STRONG_BUY',
            $score >= 35 => 'BUY_ACCUMULATE',
            $score <= -70 => 'STRONG_SELL',
            $score <= -35 => 'SELL',
            default => 'HOLD',
        };
    }
}
