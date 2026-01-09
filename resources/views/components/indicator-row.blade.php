@props(['label', 'value', 'trend' => null])
<div class="flex items-center justify-between py-1 text-sm">
    <span class="text-muted">{{ $label }}</span>
    <span class="flex items-center gap-2">
        <span class="text-white font-medium">{{ $value }}</span>
        @if ($trend)
            <span class="text-xs {{ $trend === 'up' ? 'text-emerald-400' : 'text-red-400' }}">{{ $trend === 'up' ? '▲' : '▼' }}</span>
        @endif
    </span>
</div>
