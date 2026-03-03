<?php

namespace App\Http\Requests\Catalog\Update;

use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaxCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('tax_category')?->id ?? $this->route('tax_category');

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('tax_categories', 'name')
                    ->where('tenant_id', TenantContext::id())
                    ->ignore($categoryId),
            ],
            'rate'       => ['required', 'numeric', 'min:0', 'max:100'],
            'type'       => ['required', 'in:percentage,fixed'],
            'is_default' => ['nullable', 'boolean'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la taxe est obligatoire.',
            'name.max'      => 'Le nom ne doit pas dépasser 255 caractères.',
            'name.unique'   => 'Ce nom de taxe est déjà utilisé.',
            'rate.required' => 'Le taux est obligatoire.',
            'rate.numeric'  => 'Le taux doit être un nombre.',
            'rate.min'      => 'Le taux ne peut pas être négatif.',
            'rate.max'      => 'Le taux ne doit pas dépasser 100.',
            'type.required' => 'Le type de taxe est obligatoire.',
            'type.in'       => 'Le type doit être « Pourcentage » ou « Fixe ».',
        ];
    }
}
