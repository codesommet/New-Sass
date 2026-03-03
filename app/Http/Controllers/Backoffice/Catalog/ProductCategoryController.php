<?php

namespace App\Http\Controllers\Backoffice\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\Store\StoreProductCategoryRequest;
use App\Http\Requests\Catalog\Update\UpdateProductCategoryRequest;
use App\Models\Catalog\ProductCategory;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')
            ->latest()
            ->paginate(15);

        return view('backoffice.catalog.categories.index', compact('categories'));
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        ProductCategory::create($data);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category)
    {
        $this->assertSameTenant($category);

        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return redirect()->back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(ProductCategory $category)
    {
        $this->assertSameTenant($category);

        abort_if(
            $category->products()->exists(),
            422,
            'Impossible de supprimer une catégorie contenant des produits.'
        );

        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée avec succès.');
    }

    private function assertSameTenant(ProductCategory $category): void
    {
        abort_unless($category->tenant_id === TenantContext::id(), 403);
    }
}
