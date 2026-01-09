<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchlistItem extends Model
{
    protected $fillable = ['watchlist_id', 'company_id', 'sort_order'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
