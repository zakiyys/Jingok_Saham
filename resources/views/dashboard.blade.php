<x-layouts.app>
    <div class="px-6 py-8" x-data="dashboard({ activeTicker: '{{ $activeTicker }}', cards: @js($signalsByTicker) })">
        <header class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="text-xs tracking-[0.3em] text-muted">SAHAM BUDDY</div>
                <h1 class="text-2xl font-semibold">Indo Equities Dashboard</h1>
            </div>
            <div class="flex items-center gap-4 text-sm text-muted">
                <span>Last update: {{ $lastUpdate }}</span>
                <div class="rounded-full bg-panelLight px-3 py-1 text-white">Profile / Logout</div>
            </div>
        </header>

        <div class="mt-6">
            <x-ticker-tabs :tickers="$tickers" :activeTicker="$activeTicker" />
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach ($signals as $signal)
                <x-stock-card :signal="$signal" />
            @endforeach
        </div>
    </div>
</x-layouts.app>
