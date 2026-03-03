<?php

namespace App\Http\Controllers\Backoffice\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Store\StoreCustomerContactRequest;
use App\Http\Requests\CRM\Update\UpdateCustomerContactRequest;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerContact;
use App\Services\Tenancy\TenantContext;

class CustomerContactController extends Controller
{
    public function store(StoreCustomerContactRequest $request, Customer $customer)
    {
        $this->assertSameTenant($customer);

        $customer->contacts()->create($request->safe()->except('customer_id'));

        return redirect()->route('bo.crm.customers.show', $customer)
            ->with('success', 'Contact ajouté avec succès.');
    }

    public function update(UpdateCustomerContactRequest $request, CustomerContact $contact)
    {
        abort_unless($contact->tenant_id === TenantContext::id(), 403);

        $contact->update($request->validated());

        return redirect()->route('bo.crm.customers.show', $contact->customer_id)
            ->with('success', 'Contact mis à jour avec succès.');
    }

    public function destroy(CustomerContact $contact)
    {
        abort_unless($contact->tenant_id === TenantContext::id(), 403);

        $customerId = $contact->customer_id;
        $contact->delete();

        return redirect()->route('bo.crm.customers.show', $customerId)
            ->with('success', 'Contact supprimé avec succès.');
    }

    private function assertSameTenant(Customer $customer): void
    {
        abort_unless($customer->tenant_id === TenantContext::id(), 403);
    }
}
