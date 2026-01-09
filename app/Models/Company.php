<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'ticker',
        'name',
        'sector',
        'exchange',
        'is_active',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(EodPrice::class);
    }
}
