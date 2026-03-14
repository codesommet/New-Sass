<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name'    => ['required', 'string', 'max:255'],
            'company_email'   => ['required', 'email', 'max:255'],
            'company_phone'   => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_city'    => ['nullable', 'string', 'max:255'],
            'company_country' => ['nullable', 'string', 'max:255'],
            'sector'          => ['nullable', 'string', 'in:commerce,services,industrie,construction,technologie,sante,education,transport,agriculture,immobilier,restauration,autre'],
            'employees_count' => ['nullable', 'string', 'in:1-5,6-20,21-50,51-200,200+'],
            'contact_name'    => ['required', 'string', 'max:255'],
            'contact_email'   => ['required', 'email', 'max:255'],
            'contact_phone'   => ['nullable', 'string', 'max:50'],
            'message'         => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required'  => 'Veuillez saisir le nom de votre entreprise.',
            'company_email.required' => 'Veuillez saisir l\'adresse email de l\'entreprise.',
            'company_email.email'    => 'Veuillez saisir une adresse email valide.',
            'contact_name.required'  => 'Veuillez saisir votre nom complet.',
            'contact_email.required' => 'Veuillez saisir votre adresse email.',
            'contact_email.email'    => 'Veuillez saisir une adresse email valide.',
            'sector.in'              => 'Le secteur sélectionné est invalide.',
            'employees_count.in'     => 'Le nombre d\'employés sélectionné est invalide.',
            'message.max'            => 'Votre message ne peut pas dépasser :max caractères.',
        ];
    }
}
