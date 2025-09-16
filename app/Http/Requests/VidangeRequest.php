<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VidangeRequest extends FormRequest
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
            'date_prevue' => ['required', 'date', 'after:today'],
            'kilometrage_actuel' => ['required', 'numeric', 'min:0'],
            'kilometrage_prochaine' => ['required', 'numeric', 'gte:kilometrage_actuel'],
            'type_huile' => ['nullable', 'string', 'max:50'],
            'filtre_huile' => ['nullable', 'string', 'max:50'],
            'filtre_air' => ['nullable', 'string', 'max:50'],
            'filtre_carburant' => ['nullable', 'string', 'max:50'],
            'cout_estime' => ['nullable', 'numeric', 'min:0'],
            'statut' => ['required', 'string', 'in:planifiee,en_cours,terminee,annulee'],
            'notes' => ['nullable', 'string', 'max:500']
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
            'date_prevue.required' => 'La date prévue est requise.',
            'date_prevue.date' => 'La date prévue doit être une date valide.',
            'date_prevue.after' => 'La date prévue doit être dans le futur.',
            'kilometrage_actuel.required' => 'Le kilométrage actuel est requis.',
            'kilometrage_actuel.numeric' => 'Le kilométrage actuel doit être un nombre.',
            'kilometrage_actuel.min' => 'Le kilométrage actuel ne peut pas être négatif.',
            'kilometrage_prochaine.required' => 'Le kilométrage de la prochaine vidange est requis.',
            'kilometrage_prochaine.numeric' => 'Le kilométrage de la prochaine vidange doit être un nombre.',
            'kilometrage_prochaine.gte' => 'Le kilométrage de la prochaine vidange doit être supérieur ou égal au kilométrage actuel.',
            'type_huile.max' => 'Le type d\'huile ne peut pas dépasser 50 caractères.',
            'filtre_huile.max' => 'Le filtre à huile ne peut pas dépasser 50 caractères.',
            'filtre_air.max' => 'Le filtre à air ne peut pas dépasser 50 caractères.',
            'filtre_carburant.max' => 'Le filtre à carburant ne peut pas dépasser 50 caractères.',
            'cout_estime.numeric' => 'Le coût estimé doit être un nombre.',
            'cout_estime.min' => 'Le coût estimé ne peut pas être négatif.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être valide.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'date_prevue' => 'date prévue',
            'kilometrage_actuel' => 'kilométrage actuel',
            'kilometrage_prochaine' => 'kilométrage prochaine vidange',
            'type_huile' => 'type d\'huile',
            'filtre_huile' => 'filtre à huile',
            'filtre_air' => 'filtre à air',
            'filtre_carburant' => 'filtre à carburant',
            'cout_estime' => 'coût estimé',
            'statut' => 'statut',
            'notes' => 'notes',
        ];
    }
}


