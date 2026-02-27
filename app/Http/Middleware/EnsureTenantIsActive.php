<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;

class EnsureTenantIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = TenantContext::get();

        if (!$tenant) {
            // No tenant resolved — let the request continue
            // (SuperAdmin routes don't resolve a tenant)
            return $next($request);
        }

        // Check tenant status
        if ($tenant->status === 'suspended' || $tenant->status === 'cancelled') {
            return response()->view('errors.tenant-suspended', [
                'tenant' => $tenant,
                'status' => $tenant->status,
            ], 403);
        }

        // Check trial expiry
        if (
            $tenant->has_free_trial
            && $tenant->trial_ends_at !== null
            && $tenant->trial_ends_at->isPast()
        ) {
            return response()->view('errors.tenant-suspended', [
                'tenant' => $tenant,
                'status' => 'trial_expired',
            ], 403);
        }

        return $next($request);
    }
}
