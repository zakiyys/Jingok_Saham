@props(['signal'])
@php
    $tickerKey = $signal['ticker'];
    $frames = [
        ['value' => '1W', 'label' => '1W'],
        ['value' => '1M', 'label' => '1M'],
        ['value' => '3M', 'label' => '3M+'],
    ];
@endphp
<div id="card-{{ $tickerKey }}" class="rounded-2xl bg-panel p-6 card-border" :class="activeTicker === '{{ $tickerKey }}' ? 'ring-2 ring-white' : ''">
    <div class="flex items-center justify-between text-xs text-muted">
        <span x-text="cards['{{ $tickerKey }}'].sector ?? 'IDX'">{{ $signal['sector'] ?? 'IDX' }}</span>
        <span x-show="cards['{{ $tickerKey }}'].fresh" class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300">Fresh</span>
    </div>
    <div class="mt-4 flex items-start justify-between">
        <div>
            <div class="text-3xl font-bold tracking-wide" x-text="cards['{{ $tickerKey }}'].ticker">{{ $signal['ticker'] }}</div>
            <div class="text-sm text-muted" x-text="cards['{{ $tickerKey }}'].company">{{ $signal['company'] }}</div>
        </div>
        <span
            class="px-3 py-1 text-xs font-bold tracking-wide border rounded-full"
            :class="badgeClass(cards['{{ $tickerKey }}'].recommendation)"
            x-text="cards['{{ $tickerKey }}'].recommendation_label"
        >
            {{ $signal['recommendation_label'] }}
        </span>
    </div>
    <div class="mt-4 space-y-1 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-muted">Last close</span>
            <span class="font-semibold" x-text="cards['{{ $tickerKey }}'].last_close_formatted">{{ $signal['last_close_formatted'] }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-muted">% Change</span>
            <span class="font-semibold" :class="cards['{{ $tickerKey }}'].pct_change < 0 ? 'text-red-400' : 'text-emerald-400'" x-text="cards['{{ $tickerKey }}'].pct_change_formatted">
                {{ $signal['pct_change_formatted'] }}
            </span>
        </div>
    </div>

    <div class="mt-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs uppercase text-muted">Recommendation</div>
                <div class="text-sm text-white">Signals</div>
            </div>
            <div class="flex gap-2">
                @foreach ($frames as $frame)
                    <button
                        type="button"
                        class="px-3 py-1 text-xs rounded-full border"
                        :class="cards['{{ $tickerKey }}'].timeframe === '{{ $frame['value'] }}' ? 'border-white bg-panelLight text-white' : 'border-line bg-panel text-muted'"
                        @click="updateTimeframe('{{ $tickerKey }}', '{{ $frame['value'] }}')"
                    >
                        {{ $frame['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="text-xs uppercase text-muted">PRICE TARGETS</div>
        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-lg bg-panelLight p-3 card-border">
                <div class="text-xs text-muted uppercase">Conservative</div>
                <div class="mt-1 text-lg font-semibold text-info" x-text="cards['{{ $tickerKey }}'].targets.conservative.price_formatted">
                    {{ $signal['targets']['conservative']['price_formatted'] }}
                </div>
                <div class="text-xs" :class="cards['{{ $tickerKey }}'].targets.conservative.pct_formatted.includes('-') ? 'text-red-300' : 'text-emerald-300'" x-text="cards['{{ $tickerKey }}'].targets.conservative.pct_formatted">
                    {{ $signal['targets']['conservative']['pct_formatted'] }}
                </div>
            </div>
            <div class="rounded-lg bg-panelLight p-3 card-border">
                <div class="text-xs text-muted uppercase">Moderate</div>
                <div class="mt-1 text-lg font-semibold text-emerald-300" x-text="cards['{{ $tickerKey }}'].targets.moderate.price_formatted">
                    {{ $signal['targets']['moderate']['price_formatted'] }}
                </div>
                <div class="text-xs" :class="cards['{{ $tickerKey }}'].targets.moderate.pct_formatted.includes('-') ? 'text-red-300' : 'text-emerald-300'" x-text="cards['{{ $tickerKey }}'].targets.moderate.pct_formatted">
                    {{ $signal['targets']['moderate']['pct_formatted'] }}
                </div>
            </div>
            <div class="rounded-lg bg-panelLight p-3 card-border">
                <div class="text-xs text-muted uppercase">Aggressive</div>
                <div class="mt-1 text-lg font-semibold text-amber-300" x-text="cards['{{ $tickerKey }}'].targets.aggressive.price_formatted">
                    {{ $signal['targets']['aggressive']['price_formatted'] }}
                </div>
                <div class="text-xs" :class="cards['{{ $tickerKey }}'].targets.aggressive.pct_formatted.includes('-') ? 'text-red-300' : 'text-emerald-300'" x-text="cards['{{ $tickerKey }}'].targets.aggressive.pct_formatted">
                    {{ $signal['targets']['aggressive']['pct_formatted'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="text-xs uppercase text-muted">Key Indicators</div>
        <div class="mt-2">
            <div class="flex items-center justify-between py-1 text-sm">
                <span class="text-muted">RSI (14)</span>
                <span class="flex items-center gap-2">
                    <span class="text-white font-medium" x-text="cards['{{ $tickerKey }}'].rsi">{{ $signal['rsi'] }}</span>
                    <span class="text-xs" :class="cards['{{ $tickerKey }}'].rsi_trend === 'up' ? 'text-emerald-400' : 'text-red-400'" x-text="cards['{{ $tickerKey }}'].rsi_trend === 'up' ? '▲' : '▼'"></span>
                </span>
            </div>
            <div class="flex items-center justify-between py-1 text-sm">
                <span class="text-muted">MACD</span>
                <span class="flex items-center gap-2">
                    <span class="text-white font-medium" x-text="cards['{{ $tickerKey }}'].macd">{{ $signal['macd'] }}</span>
                    <span class="text-xs" :class="cards['{{ $tickerKey }}'].macd_trend === 'up' ? 'text-emerald-400' : 'text-red-400'" x-text="cards['{{ $tickerKey }}'].macd_trend === 'up' ? '▲' : '▼'"></span>
                </span>
            </div>
            <div class="flex items-center justify-between py-1 text-sm">
                <span class="text-muted">Vol Ratio</span>
                <span class="flex items-center gap-2">
                    <span class="text-white font-medium" x-text="cards['{{ $tickerKey }}'].vol_ratio">{{ $signal['vol_ratio'] }}</span>
                    <span class="text-xs" :class="cards['{{ $tickerKey }}'].vol_trend === 'up' ? 'text-emerald-400' : 'text-red-400'" x-text="cards['{{ $tickerKey }}'].vol_trend === 'up' ? '▲' : '▼'"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="mt-4 text-xs text-muted" x-text="cards['{{ $tickerKey }}'].reasons.join(' · ')">
        {{ implode(' · ', $signal['reasons']) }}
    </div>
</div>
