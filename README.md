# Saham Buddy

Dashboard dark-mode untuk screening cepat saham IDX. Demo ini memakai data sintetis dan rule-based scoring untuk indikator teknikal.

## Prasyarat
- PHP >= 8.2
- Composer
- Node.js + npm
- MySQL 8
- Redis

## Setup Lokal
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## Akun Demo
- Email: `demo@sahambuddy.local`
- Password: `password`

## Scheduler + Queue
```bash
php artisan schedule:run
php artisan queue:work
```

## Fitur Utama
- Dashboard watchlist dengan indikator RSI, MACD, Volume Ratio.
- Recommendation engine rule-based dengan alasan.
- Price targets (conservative/moderate/aggressive) berbasis standar deviasi return.
- AJAX update per timeframe.

## Catatan
Data synthetic dibuat otomatis oleh seeder dan `MarketDataService`. Untuk mengganti data real, implementasikan adapter pada `MarketDataService`.
