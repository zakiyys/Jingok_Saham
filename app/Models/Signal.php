<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signal extends Model
{
    protected $fillable = [
        'company_id',
        'timeframe',
        'as_of_date',
        'last_close',
        'pct_change',
        'rsi14',
        'macd',
        'macd_signal',
        'macd_hist',
        'vol_ratio',
        'score',
        'recommendation',
        'reasons',
        'targets',
        'computed_at',
    ];

    protected $casts = [
        'as_of_date' => 'date',
        'computed_at' => 'datetime',
        'reasons' => 'array',
        'targets' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
