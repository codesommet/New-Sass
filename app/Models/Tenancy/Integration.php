<?php

namespace App\Models\Tenancy;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'provider',
        'status',
        'credentials',
        'settings',
        'last_synced_at',
    ];

    protected $casts = [
        'credentials' => 'json',
        'settings' => 'json',
        'last_synced_at' => 'datetime',
    ];
}
