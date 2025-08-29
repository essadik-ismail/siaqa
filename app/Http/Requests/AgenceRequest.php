<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgenceRequest extends FormRequest
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
        $agenceId = $this->route('agence');
        
        return [
            'nom_agence' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['required', 'string', 'max:100'],
            'rc' => ['nullable', 'string', 'max:100'],
            'patente' => ['nullable', 'string', 'max:100'],
            'IF' => ['nullable', 'string', 'max:100'],
            'n_cnss' => ['nullable', 'string', 'max:100'],
            'ICE' => ['nullable', 'string', 'max:100'],
            'n_compte_bancaire' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom_agence.required' => 'Le nom de l\'agence est requis.',
            'nom_agence.max' => 'Le nom de l\'agence ne peut pas dépasser 255 caractères.',
            'adresse.required' => 'L\'adresse est requise.',
            'ville.required' => 'La ville est requise.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.mimes' => 'Le logo doit être au format : jpeg, png, jpg ou gif.',
            'logo.max' => 'Le logo ne peut pas dépasser 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom_agence' => 'nom de l\'agence',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'rc' => 'registre de commerce',
            'patente' => 'patente',
            'IF' => 'identifiant fiscal',
            'n_cnss' => 'numéro CNSS',
            'ICE' => 'ICE',
            'n_compte_bancaire' => 'numéro de compte bancaire',
            'logo' => 'logo',
            'is_active' => 'statut actif',
        ];
    }
} 