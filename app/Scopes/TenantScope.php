<?php

namespace App\Scopes;

use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Global scope that automatically filters queries by tenant_id.
 *
 * NO-OP when:
 *  - Running in CLI (artisan commands) unless TenantContext is explicitly set
 *  - Queue workers unless TenantContext is explicitly set
 *  - Super Admin users (tenant_id IS NULL)
 *  - No tenant is resolved (TenantContext empty AND no auth user with tenant)
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->resolveTenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }

    /**
     * Resolve the current tenant ID from TenantContext or auth fallback.
     * Returns null if no tenant context should be applied (superadmin, CLI, etc.)
     */
    protected function resolveTenantId(): ?string
    {
        // 1) If TenantContext has a tenant set, use it
        if (TenantContext::check()) {
            return TenantContext::id();
        }

        // 2) CLI / queue worker without explicit context → NO-OP
        if (app()->runningInConsole()) {
            return null;
        }

        // 3) Fallback: try the authenticated user's tenant_id
        $user = auth()->user();

        if ($user === null) {
            return null;
        }

        // Super Admin (tenant_id === null) → NO-OP
        if ($user->tenant_id === null) {
            return null;
        }

        return $user->tenant_id;
    }
}
