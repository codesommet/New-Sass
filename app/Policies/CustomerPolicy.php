<?php

namespace App\Policies;

use App\Models\CRM\Customer;
use App\Models\User;
use App\Services\Tenancy\TenantContext;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('crm.customers.view');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.view')
            && $customer->tenant_id === TenantContext::id();
    }

    public function create(User $user): bool
    {
        return $user->can('crm.customers.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.edit')
            && $customer->tenant_id === TenantContext::id();
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.delete')
            && $customer->tenant_id === TenantContext::id();
    }
}
