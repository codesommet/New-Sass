<?php

namespace App\Http\Controllers\Backoffice\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Store\StoreTaxCategoryRequest;
use App\Http\Requests\Catalog\Update\UpdateTaxCategoryRequest;
use App\Models\Catalog\TaxCategory;
use App\Services\Tenancy\TenantContext;

class TaxCategoryController extends Controller
{
    public function store(StoreTaxCategoryRequest $request)
    {
        TaxCategory::create($request->validated());

        return redirect()->back()->with('success', 'Taux de taxe ajouté avec succès.');
    }

    public function update(UpdateTaxCategoryRequest $request, TaxCategory $taxCategory)
    {
        $this->assertSameTenant($taxCategory);

        $taxCategory->update($request->validated());

        return redirect()->back()->with('success', 'Taux de taxe mis à jour avec succès.');
    }

    public function destroy(TaxCategory $taxCategory)
    {
        $this->assertSameTenant($taxCategory);

        abort_if(
            $taxCategory->products()->exists(),
            422,
            'Impossible de supprimer une taxe utilisée par des produits.'
        );

        $taxCategory->delete();

        return redirect()->back()->with('success', 'Taux de taxe supprimé avec succès.');
    }

    private function assertSameTenant(TaxCategory $taxCategory): void
    {
        abort_unless($taxCategory->tenant_id === TenantContext::id(), 403);
    }
}
