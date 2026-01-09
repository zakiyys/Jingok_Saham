@props(['label' => 'HOLD', 'variant' => 'hold'])
@php
    $classes = match ($variant) {
        'strong_buy' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/40',
        'buy' => 'bg-blue-500/20 text-blue-300 border-blue-400/40',
        'hold' => 'bg-amber-500/20 text-amber-200 border-amber-400/40',
        'sell' => 'bg-red-500/20 text-red-300 border-red-400/40',
        'strong_sell' => 'bg-rose-500/20 text-rose-300 border-rose-400/40',
        default => 'bg-panelLight text-white border-line',
    };
@endphp
<span class="px-3 py-1 text-xs font-bold tracking-wide border rounded-full {{ $classes }}">
    {{ $label }}
</span>
