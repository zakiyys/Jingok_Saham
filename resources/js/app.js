import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('dashboard', (initial) => ({
  activeTicker: initial.activeTicker || null,
  cards: initial.cards || {},
  setActive(ticker) {
    this.activeTicker = ticker;
    const el = document.getElementById(`card-${ticker}`);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  },
  badgeClass(recommendation) {
    switch (recommendation) {
      case 'STRONG_BUY':
        return 'bg-emerald-500/20 text-emerald-300 border-emerald-400/40';
      case 'BUY_ACCUMULATE':
        return 'bg-blue-500/20 text-blue-300 border-blue-400/40';
      case 'HOLD':
        return 'bg-amber-500/20 text-amber-200 border-amber-400/40';
      case 'SELL':
        return 'bg-red-500/20 text-red-300 border-red-400/40';
      case 'STRONG_SELL':
        return 'bg-rose-500/20 text-rose-300 border-rose-400/40';
      default:
        return 'bg-panelLight text-white border-line';
    }
  },
  async updateTimeframe(ticker, timeframe) {
    const response = await fetch(`/api/signal/${ticker}?timeframe=${timeframe}`);
    if (!response.ok) {
      return;
    }
    const payload = await response.json();
    this.cards[ticker] = payload;
  },
}));

Alpine.start();
