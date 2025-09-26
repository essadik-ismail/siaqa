<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clientId = $this->route('client');
        $tenantId = auth()->user()->tenant_id ?? null;
        
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'nom_societe' => ['nullable', 'string', 'max:255'],
            'ice_societe' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255',
                Rule::unique('clients')->ignore($clientId)->where(function ($query) use ($tenantId) {
                    if ($tenantId) {
                        return $query->where('tenant_id', $tenantId);
                    }
                    return $query->whereNull('tenant_id');
                })
            ],
            'telephone' => ['required', 'string', 'max:20'],
            'adresse' => ['required', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'lieu_de_naissance' => ['nullable', 'string', 'max:255'],
            'nationalite' => ['nullable', 'string', 'max:100'],
            'numero_cin' => ['nullable', 'string', 'max:50'],
            'date_cin_expiration' => ['nullable', 'date'],
            'numero_permis' => ['nullable', 'string', 'max:50'],
            'date_permis' => ['nullable', 'date'],
            'passport' => ['nullable', 'string', 'max:50'],
            'date_passport' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre client.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'adresse.required' => 'L\'adresse est requise.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être de type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Chaque image doit être de type: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom',
            'prenom' => 'prénom',
            'email' => 'adresse email',
            'telephone' => 'numéro de téléphone',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'postal_code' => 'code postal',
            'date_naissance' => 'date de naissance',
            'lieu_de_naissance' => 'lieu de naissance',
            'nationalite' => 'nationalité',
            'numero_cin' => 'numéro CIN',
            'date_cin_expiration' => 'date d\'expiration CIN',
            'numero_permis' => 'numéro de permis',
            'date_permis' => 'date de permis',
            'passport' => 'passeport',
            'date_passport' => 'date de passeport',
            'description' => 'description',
            'image' => 'image principale',
            'images' => 'images supplémentaires',
        ];
    }
} 