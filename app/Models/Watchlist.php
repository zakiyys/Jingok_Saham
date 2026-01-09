<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Watchlist extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function items(): HasMany
    {
        return $this->hasMany(WatchlistItem::class);
    }
}
