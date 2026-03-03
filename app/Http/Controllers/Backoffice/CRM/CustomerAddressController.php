<?php

namespace App\Http\Controllers\Backoffice\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Store\StoreCustomerAddressRequest;
use App\Http\Requests\CRM\Update\UpdateCustomerAddressRequest;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerAddress;
use App\Services\Tenancy\TenantContext;

class CustomerAddressController extends Controller
{
    public function store(StoreCustomerAddressRequest $request, Customer $customer)
    {
        $this->assertSameTenant($customer);

        $customer->addresses()->create($request->safe()->except('customer_id'));

        return redirect()->route('bo.crm.customers.show', $customer)
            ->with('success', 'Adresse ajoutée avec succès.');
    }

    public function update(UpdateCustomerAddressRequest $request, CustomerAddress $address)
    {
        abort_unless($address->tenant_id === TenantContext::id(), 403);

        $address->update($request->validated());

        return redirect()->route('bo.crm.customers.show', $address->customer_id)
            ->with('success', 'Adresse mise à jour avec succès.');
    }

    public function destroy(CustomerAddress $address)
    {
        abort_unless($address->tenant_id === TenantContext::id(), 403);

        $customerId = $address->customer_id;
        $address->delete();

        return redirect()->route('bo.crm.customers.show', $customerId)
            ->with('success', 'Adresse supprimée avec succès.');
    }

    private function assertSameTenant(Customer $customer): void
    {
        abort_unless($customer->tenant_id === TenantContext::id(), 403);
    }
}
