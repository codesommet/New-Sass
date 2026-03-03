<?php

namespace App\Http\Requests\Catalog\Store;

use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaxGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('tax_groups', 'name')
                    ->where('tenant_id', TenantContext::id()),
            ],
            'is_active'       => ['nullable', 'boolean'],
            'rates'           => ['sometimes', 'array'],
            'rates.*.name'    => ['required_with:rates', 'string', 'max:255'],
            'rates.*.rate'    => ['required_with:rates', 'numeric', 'min:0', 'max:100'],
            'rates.*.position' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Le nom du groupe de taxes est obligatoire.',
            'name.max'               => 'Le nom ne doit pas dépasser 255 caractères.',
            'name.unique'            => 'Ce nom de groupe est déjà utilisé.',
            'rates.*.name.required_with' => 'Le nom de chaque taux est obligatoire.',
            'rates.*.rate.required_with' => 'Le taux est obligatoire.',
            'rates.*.rate.numeric'   => 'Le taux doit être un nombre.',
            'rates.*.rate.min'       => 'Le taux ne peut pas être négatif.',
            'rates.*.rate.max'       => 'Le taux ne doit pas dépasser 100.',
        ];
    }
}
