<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContratRequest extends FormRequest
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
        $contratId = $this->route('contrat');
        
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
            'client_one_id' => [
                'required', 
                'exists:clients,id',
                function ($attribute, $value, $fail) {
                    $client = \App\Models\Client::where('id', $value)
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->first();
                    if (!$client) {
                        $fail('Le client principal sélectionné n\'existe pas.');
                    }
                }
            ],
            'client_two_id' => [
                'nullable',
                'exists:clients,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $client = \App\Models\Client::where('id', $value)
                            ->where('tenant_id', auth()->user()->tenant_id)
                            ->first();
                        if (!$client) {
                            $fail('Le client secondaire sélectionné n\'existe pas.');
                        }
                    }
                }
            ],
            'etat_contrat' => ['required', 'string', 'in:en cours,termine'],
            'date_contrat' => ['required', 'date'],
            'heure_contrat' => ['nullable', 'date_format:H:i'],
            'km_depart' => ['nullable', 'string', 'max:50'],
            'heure_depart' => ['nullable', 'date_format:H:i'],
            'lieu_depart' => ['nullable', 'string', 'max:255'],
            'date_retour' => ['nullable', 'date', 'after_or_equal:date_contrat'],
            'heure_retour' => ['nullable', 'date_format:H:i'],
            'lieu_livraison' => ['nullable', 'string', 'max:255'],
            'nbr_jours' => ['nullable', 'integer', 'min:1'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'total_ht' => ['nullable', 'numeric', 'min:0'],
            'total_ttc' => ['nullable', 'numeric', 'min:0'],
            'remise' => ['nullable', 'numeric', 'min:0'],
            'mode_reglement' => ['nullable', 'string', 'in:cheque,espece,tpe,versement'],
            'caution_assurance' => ['nullable', 'string', 'max:255'],
            'position_resrvoir' => ['nullable', 'string', 'in:0,1/4,2/4,3/4,4/4'],
            'prolongation' => ['nullable', 'string', 'max:255'],
            'documents' => ['nullable', 'boolean'],
            'cric' => ['nullable', 'boolean'],
            'siege_enfant' => ['nullable', 'boolean'],
            'roue_secours' => ['nullable', 'boolean'],
            'poste_radio' => ['nullable', 'boolean'],
            'plaque_panne' => ['nullable', 'boolean'],
            'gillet' => ['nullable', 'boolean'],
            'extincteur' => ['nullable', 'boolean'],
            'autre_fichier' => ['nullable', 'string', 'max:255'],
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
            'client_one_id.required' => 'Le client principal est requis.',
            'client_one_id.exists' => 'Le client principal sélectionné n\'existe pas.',
            'client_two_id.exists' => 'Le client secondaire sélectionné n\'existe pas.',
            'etat_contrat.required' => 'L\'état du contrat est requis.',
            'etat_contrat.in' => 'L\'état du contrat doit être "en cours" ou "termine".',
            'date_contrat.required' => 'La date du contrat est requise.',
            'heure_contrat.date_format' => 'L\'heure du contrat doit être au format HH:MM.',
            'km_depart.max' => 'Le kilométrage de départ ne peut pas dépasser 50 caractères.',
            'heure_depart.date_format' => 'L\'heure de départ doit être au format HH:MM.',
            'lieu_depart.max' => 'Le lieu de départ ne peut pas dépasser 255 caractères.',
            'date_retour.after_or_equal' => 'La date de retour doit être postérieure ou égale à la date du contrat.',
            'heure_retour.date_format' => 'L\'heure de retour doit être au format HH:MM.',
            'lieu_livraison.max' => 'Le lieu de livraison ne peut pas dépasser 255 caractères.',
            'nbr_jours.min' => 'Le nombre de jours doit être au moins 1.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'total_ht.min' => 'Le total HT ne peut pas être négatif.',
            'total_ttc.min' => 'Le total TTC ne peut pas être négatif.',
            'remise.min' => 'La remise ne peut pas être négative.',
            'mode_reglement.in' => 'Le mode de règlement doit être valide.',
            'caution_assurance.max' => 'La caution assurance ne peut pas dépasser 255 caractères.',
            'position_resrvoir.in' => 'La position du réservoir doit être valide.',
            'prolongation.max' => 'La prolongation ne peut pas dépasser 255 caractères.',
            'autre_fichier.max' => 'L\'autre fichier ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicule_id' => 'véhicule',
            'client_one_id' => 'client principal',
            'client_two_id' => 'client secondaire',
            'etat_contrat' => 'état du contrat',
            'date_contrat' => 'date du contrat',
            'heure_contrat' => 'heure du contrat',
            'km_depart' => 'kilométrage de départ',
            'heure_depart' => 'heure de départ',
            'lieu_depart' => 'lieu de départ',
            'date_retour' => 'date de retour',
            'heure_retour' => 'heure de retour',
            'lieu_livraison' => 'lieu de livraison',
            'nbr_jours' => 'nombre de jours',
            'prix' => 'prix',
            'total_ht' => 'total HT',
            'total_ttc' => 'total TTC',
            'remise' => 'remise',
            'mode_reglement' => 'mode de règlement',
            'caution_assurance' => 'caution assurance',
            'position_resrvoir' => 'position du réservoir',
            'prolongation' => 'prolongation',
            'documents' => 'documents',
            'cric' => 'cric',
            'siege_enfant' => 'siège enfant',
            'roue_secours' => 'roue de secours',
            'poste_radio' => 'poste radio',
            'plaque_panne' => 'plaque de panne',
            'gillet' => 'gilet',
            'extincteur' => 'extincteur',
            'autre_fichier' => 'autre fichier',
            'description' => 'description',
        ];
    }
} 