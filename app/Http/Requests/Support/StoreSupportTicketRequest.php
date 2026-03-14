<?php

namespace App\Http\Requests\Support;

use App\Http\Requests\TenantFormRequest;

class StoreSupportTicketRequest extends TenantFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject'     => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category'    => ['required', 'in:bug,feature,billing,account,other'],
            'priority'      => ['required', 'in:low,medium,high,urgent'],
            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required'     => 'Le sujet est obligatoire.',
            'subject.max'          => 'Le sujet ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.max'      => 'La description ne peut pas dépasser 5000 caractères.',
            'category.required'    => 'La catégorie est obligatoire.',
            'category.in'          => 'La catégorie sélectionnée est invalide.',
            'priority.required'    => 'La priorité est obligatoire.',
            'priority.in'          => 'La priorité sélectionnée est invalide.',
            'attachments.max'      => 'Vous ne pouvez pas joindre plus de 5 fichiers.',
            'attachments.*.max'    => 'Chaque fichier ne doit pas dépasser 10 Mo.',
            'attachments.*.mimes'  => 'Format de fichier non autorisé. Formats acceptés : JPG, PNG, GIF, WebP, PDF, DOC, DOCX, ZIP.',
        ];
    }
}
