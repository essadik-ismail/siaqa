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
            'numero_assurance' => ['required', 'string', 'max:255'],
            'numero_police' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('assurances')->ignore($assuranceId)->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                })
            ],
            'date' => ['required', 'date'],
            'date_prochaine' => ['required', 'date', 'after:date'],
            'date_reglement' => ['required', 'date'],
            'prix' => ['required', 'numeric', 'min:0'],
            'periode' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
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
            'numero_assurance.required' => 'Le numéro d\'assurance est requis.',
            'numero_police.required' => 'Le numéro de police est requis.',
            'numero_police.unique' => 'Ce numéro de police est déjà utilisé.',
            'date.required' => 'La date est requise.',
            'date_prochaine.required' => 'La date prochaine est requise.',
            'date_prochaine.after' => 'La date prochaine doit être postérieure à la date.',
            'date_reglement.required' => 'La date de règlement est requise.',
            'prix.required' => 'Le prix est requis.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'numero_assurance' => 'numéro d\'assurance',
            'numero_police' => 'numéro de police',
            'date' => 'date',
            'date_prochaine' => 'date prochaine',
            'date_reglement' => 'date de règlement',
            'prix' => 'prix',
            'periode' => 'période',
            'description' => 'description',
        ];
    }
} 