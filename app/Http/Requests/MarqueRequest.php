<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarqueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $marqueId = $this->route('marque');
        
        return [
            'nom' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('marques')->ignore($marqueId)->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                })
            ],
            'pays_origine' => ['nullable', 'string', 'max:100'],
            'annee_creation' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'site_web' => ['nullable', 'url', 'max:500'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la marque est requis.',
            'nom.unique' => 'Cette marque existe déjà.',
            'pays_origine.max' => 'Le pays d\'origine ne peut pas dépasser 100 caractères.',
            'annee_creation.integer' => 'L\'année de création doit être un nombre entier.',
            'annee_creation.min' => 'L\'année de création doit être supérieure ou égale à 1800.',
            'annee_creation.max' => 'L\'année de création ne peut pas être dans le futur.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'logo_url.url' => 'L\'URL du logo doit être valide.',
            'site_web.url' => 'L\'URL du site web doit être valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom de la marque',
            'pays_origine' => 'pays d\'origine',
            'annee_creation' => 'année de création',
            'description' => 'description',
            'logo_url' => 'URL du logo',
            'site_web' => 'site web',
            'is_active' => 'statut actif',
        ];
    }
} 