@props(['label', 'price', 'pct', 'tone'])
@php
    $toneClasses = match ($tone) {
        'conservative' => 'text-info',
        'moderate' => 'text-emerald-300',
        'aggressive' => 'text-amber-300',
        default => 'text-white',
    };
@endphp
<div class="rounded-lg bg-panelLight p-3 card-border">
    <div class="text-xs text-muted uppercase">{{ $label }}</div>
    <div class="mt-1 text-lg font-semibold {{ $toneClasses }}">{{ $price }}</div>
    <div class="text-xs {{ str_contains($pct, '-') ? 'text-red-300' : 'text-emerald-300' }}">
        {{ $pct }}
    </div>
</div>
