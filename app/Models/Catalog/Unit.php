<?php

namespace App\Models\Catalog;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'name',
        'short_name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
