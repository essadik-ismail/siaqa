<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssuranceRequest extends FormRequest
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
        $assuranceId = $this->route('assurance');
        
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
            'numero_police' => [
                'required', 
                'string', 
                'max:100',
                Rule::unique('assurances')->ignore($assuranceId)->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                })
            ],
            'compagnie' => ['required', 'string', 'max:255'],
            'type_assurance' => ['required', 'string', 'in:responsabilite_civile,vol_incendie,tous_risques,assistance_0km'],
            'date_debut' => ['required', 'date', 'before_or_equal:today'],
            'date_expiration' => ['required', 'date', 'after:date_debut'],
            'montant_prime' => ['required', 'numeric', 'min:0'],
            'franchise' => ['nullable', 'numeric', 'min:0'],
            'conditions_particulieres' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'numero_police.required' => 'Le numéro de police est requis.',
            'numero_police.unique' => 'Ce numéro de police est déjà utilisé.',
            'compagnie.required' => 'La compagnie d\'assurance est requise.',
            'type_assurance.required' => 'Le type d\'assurance est requis.',
            'type_assurance.in' => 'Le type d\'assurance doit être valide.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.before_or_equal' => 'La date de début ne peut pas être dans le futur.',
            'date_expiration.required' => 'La date d\'expiration est requise.',
            'date_expiration.after' => 'La date d\'expiration doit être postérieure à la date de début.',
            'montant_prime.required' => 'Le montant de la prime est requis.',
            'montant_prime.min' => 'Le montant de la prime ne peut pas être négatif.',
            'franchise.min' => 'La franchise ne peut pas être négative.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'numero_police' => 'numéro de police',
            'compagnie' => 'compagnie d\'assurance',
            'type_assurance' => 'type d\'assurance',
            'date_debut' => 'date de début',
            'date_expiration' => 'date d\'expiration',
            'montant_prime' => 'montant de la prime',
            'franchise' => 'franchise',
            'conditions_particulieres' => 'conditions particulières',
            'notes' => 'notes',
        ];
    }
} 