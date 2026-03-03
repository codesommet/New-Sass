<?php

namespace App\Http\Controllers\Backoffice\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Store\StoreCustomerRequest;
use App\Http\Requests\CRM\Update\UpdateCustomerRequest;
use App\Models\CRM\Customer;
use App\Models\Finance\Currency;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()
            ->withCount(['invoices', 'quotes']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('backoffice.crm.customers.index', compact('customers'));
    }

    public function create()
    {
        $currencies = Currency::orderBy('code')->get();

        return view('backoffice.crm.customers.create', compact('currencies'));
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('bo.crm.customers.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function show(Customer $customer)
    {
        $this->assertSameTenant($customer);

        $customer->load([
            'addresses',
            'contacts',
            'invoices' => fn ($q) => $q->latest()->take(10),
            'quotes' => fn ($q) => $q->latest()->take(5),
        ]);

        return view('backoffice.crm.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->assertSameTenant($customer);

        $currencies = Currency::orderBy('code')->get();

        return view('backoffice.crm.customers.edit', compact('customer', 'currencies'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->assertSameTenant($customer);

        $customer->update($request->validated());

        return redirect()->route('bo.crm.customers.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(Customer $customer)
    {
        $this->assertSameTenant($customer);

        $customer->delete();

        return redirect()->route('bo.crm.customers.index')
            ->with('success', 'Client supprimé avec succès.');
    }

    private function assertSameTenant(Customer $customer): void
    {
        abort_unless($customer->tenant_id === TenantContext::id(), 403);
    }
}
