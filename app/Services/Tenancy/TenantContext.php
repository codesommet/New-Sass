<?php

namespace App\Services\Tenancy;

use App\Models\Tenancy\Tenant;

/**
 * Singleton service that holds the currently resolved tenant
 * for the lifetime of the request / job.
 */
class TenantContext
{
    protected static ?Tenant $tenant = null;

    /**
     * Set the current tenant.
     */
    public static function set(?Tenant $tenant): void
    {
        static::$tenant = $tenant;
    }

    /**
     * Get the current tenant.
     */
    public static function get(): ?Tenant
    {
        return static::$tenant;
    }

    /**
     * Get the current tenant's ID.
     */
    public static function id(): ?string
    {
        return static::$tenant?->id;
    }

    /**
     * Check if a tenant is currently set.
     */
    public static function check(): bool
    {
        return static::$tenant !== null;
    }

    /**
     * Clear the current tenant context.
     */
    public static function forget(): void
    {
        static::$tenant = null;
    }
}
