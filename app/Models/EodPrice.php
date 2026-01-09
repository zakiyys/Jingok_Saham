<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EodPrice extends Model
{
    protected $table = 'eod_prices';

    protected $fillable = [
        'company_id',
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
