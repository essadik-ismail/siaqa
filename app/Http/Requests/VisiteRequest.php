<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisiteRequest extends FormRequest
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
        return [
            'vehicule_id' => [
                'required', 
                'exists:vehicules,id',
                function ($attribute, $value, $fail) {
                    $vehicule = \App\Models\Vehicule::where('id', $value)
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->first();
                    if (!$vehicule) {
                        $fail('Le véhicule sélectionné n\'existe pas.');
                    }
                }
            ],
            'date_visite' => ['required', 'date'],
            'type_visite' => ['required', 'string', 'max:255'],
            'statut' => ['required', 'string', 'in:planifiée,en_cours,terminée,annulée'],
            'centre_visite' => ['nullable', 'string', 'max:255'],
            'inspecteur' => ['nullable', 'string', 'max:255'],
            'cout' => ['nullable', 'numeric', 'min:0'],
            'resultat' => ['nullable', 'string', 'in:conforme,non_conforme,partiellement_conforme'],
            'prochaine_visite' => ['nullable', 'date', 'after:date_visite'],
            'kilometrage' => ['nullable', 'integer', 'min:0'],
            'observations' => ['nullable', 'string', 'max:1000']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicule_id.required' => 'Le véhicule est requis.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'date_visite.required' => 'La date de visite est requise.',
            'date_visite.date' => 'La date de visite doit être une date valide.',
            'type_visite.required' => 'Le type de visite est requis.',
            'type_visite.max' => 'Le type de visite ne peut pas dépasser 255 caractères.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être valide.',
            'centre_visite.max' => 'Le nom du centre ne peut pas dépasser 255 caractères.',
            'inspecteur.max' => 'Le nom de l\'inspecteur ne peut pas dépasser 255 caractères.',
            'cout.numeric' => 'Le coût doit être un nombre.',
            'cout.min' => 'Le coût ne peut pas être négatif.',
            'resultat.in' => 'Le résultat doit être valide.',
            'prochaine_visite.date' => 'La prochaine visite doit être une date valide.',
            'prochaine_visite.after' => 'La prochaine visite doit être postérieure à la date de visite.',
            'kilometrage.integer' => 'Le kilométrage doit être un nombre entier.',
            'kilometrage.min' => 'Le kilométrage ne peut pas être négatif.',
            'observations.max' => 'Les observations ne peuvent pas dépasser 1000 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'date_visite' => 'date de visite',
            'type_visite' => 'type de visite',
            'statut' => 'statut',
            'centre_visite' => 'centre de visite',
            'inspecteur' => 'inspecteur',
            'cout' => 'coût',
            'resultat' => 'résultat',
            'prochaine_visite' => 'prochaine visite',
            'kilometrage' => 'kilométrage',
            'observations' => 'observations',
        ];
    }
}


