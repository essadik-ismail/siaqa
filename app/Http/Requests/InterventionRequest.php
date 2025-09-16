<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterventionRequest extends FormRequest
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
            'type_intervention' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'statut' => ['required', 'string', 'in:planifiee,en_cours,terminee,annulee,en_attente'],
            'priorite' => ['nullable', 'string', 'in:basse,normale,haute,urgente'],
            'cout' => ['nullable', 'numeric', 'min:0'],
            'technicien' => ['nullable', 'string', 'max:255'],
            'kilometrage' => ['nullable', 'integer', 'min:0'],
            'duree_estimee' => ['nullable', 'numeric', 'min:0'],
            'pieces_utilisees' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000']
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
            'type_intervention.required' => 'Le type d\'intervention est requis.',
            'type_intervention.max' => 'Le type d\'intervention ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est requise.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être valide.',
            'priorite.in' => 'La priorité doit être valide.',
            'cout.numeric' => 'Le coût doit être un nombre.',
            'cout.min' => 'Le coût ne peut pas être négatif.',
            'technicien.max' => 'Le nom du technicien ne peut pas dépasser 255 caractères.',
            'kilometrage.integer' => 'Le kilométrage doit être un nombre entier.',
            'kilometrage.min' => 'Le kilométrage ne peut pas être négatif.',
            'duree_estimee.numeric' => 'La durée estimée doit être un nombre.',
            'duree_estimee.min' => 'La durée estimée ne peut pas être négative.',
            'pieces_utilisees.max' => 'Les pièces utilisées ne peuvent pas dépasser 1000 caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'type_intervention' => 'type d\'intervention',
            'description' => 'description',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'statut' => 'statut',
            'priorite' => 'priorité',
            'cout' => 'coût',
            'technicien' => 'technicien',
            'kilometrage' => 'kilométrage',
            'duree_estimee' => 'durée estimée',
            'pieces_utilisees' => 'pièces utilisées',
            'notes' => 'notes',
        ];
    }
}


