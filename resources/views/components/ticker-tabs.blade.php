<div class="flex gap-2 overflow-x-auto pb-2" role="tablist">
    @foreach ($tickers as $ticker)
        @php
            $active = $activeTicker === $ticker;
        @endphp
        <button
            type="button"
            class="px-4 py-2 rounded-full text-sm font-semibold border transition {{ $active ? 'border-white bg-panelLight text-white' : 'border-line bg-panel text-muted' }}"
            @click="setActive('{{ $ticker }}')"
        >
            {{ $ticker }}
        </button>
    @endforeach
</div>
