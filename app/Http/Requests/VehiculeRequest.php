<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculeRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $vehiculeId = $this->route('vehicule');
        
        return [
            'marque' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'immatriculation' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('vehicules')->ignore($vehiculeId)->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                })
            ],
            'status' => ['required', 'string', 'in:available,rented,maintenance,out_of_service'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'marque.required' => 'La marque est requise.',
            'marque.max' => 'La marque ne peut pas dépasser 100 caractères.',
            'name.required' => 'Le nom est requis.',
            'immatriculation.required' => 'L\'immatriculation est requise.',
            'immatriculation.unique' => 'Cette immatriculation est déjà utilisée par un autre véhicule.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'marque' => 'marque',
            'name' => 'nom',
            'immatriculation' => 'immatriculation',
            'status' => 'statut',
            'is_active' => 'statut actif',
        ];
    }
} 