<?php

namespace App\Http\Controllers\Backoffice\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Store\StoreUnitRequest;
use App\Http\Requests\Catalog\Update\UpdateUnitRequest;
use App\Models\Catalog\Unit;
use App\Services\Tenancy\TenantContext;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount('products')
            ->latest()
            ->paginate(15);

        return view('backoffice.catalog.units.index', compact('units'));
    }

    public function store(StoreUnitRequest $request)
    {
        Unit::create($request->validated());

        return redirect()->back()->with('success', 'Unité ajoutée avec succès.');
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $this->assertSameTenant($unit);

        $unit->update($request->validated());

        return redirect()->back()->with('success', 'Unité mise à jour avec succès.');
    }

    public function destroy(Unit $unit)
    {
        $this->assertSameTenant($unit);

        abort_if(
            $unit->products()->exists(),
            422,
            'Impossible de supprimer une unité utilisée par des produits.'
        );

        $unit->delete();

        return redirect()->back()->with('success', 'Unité supprimée avec succès.');
    }

    private function assertSameTenant(Unit $unit): void
    {
        abort_unless($unit->tenant_id === TenantContext::id(), 403);
    }
}
