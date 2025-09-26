<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'designation' => 'required|string|max:255|in:Carburant,Maintenance,Assurance,Réparation,Autre',
            'date' => 'required|date|before_or_equal:today',
            'montant' => 'required|numeric|min:0.01|max:999999.99',
            'fichier' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'designation.required' => 'La désignation de la charge est obligatoire.',
            'designation.string' => 'La désignation doit être une chaîne de caractères.',
            'designation.max' => 'La désignation ne peut pas dépasser 255 caractères.',
            'designation.in' => 'La désignation sélectionnée n\'est pas valide.',
            
            'date.required' => 'La date de la charge est obligatoire.',
            'date.date' => 'La date doit être une date valide.',
            'date.before_or_equal' => 'La date ne peut pas être dans le futur.',
            
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'montant.max' => 'Le montant ne peut pas dépasser 999 999,99 €.',
            
            'fichier.string' => 'Le nom du fichier doit être une chaîne de caractères.',
            'fichier.max' => 'Le nom du fichier ne peut pas dépasser 255 caractères.',
            
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'designation' => 'désignation',
            'date' => 'date',
            'montant' => 'montant',
            'fichier' => 'fichier',
            'description' => 'description',
        ];
    }
}


