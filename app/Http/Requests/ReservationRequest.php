<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
        $reservationId = $this->route('reservation');
        
        return [
            'client_id' => [
                'required', 
                'exists:clients,id',
                function ($attribute, $value, $fail) {
                    $client = \App\Models\Client::where('id', $value)
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->first();
                    if (!$client) {
                        $fail('Le client sélectionné n\'existe pas.');
                    }
                }
            ],
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
                    if ($vehicule && $vehicule->statut !== 'disponible') {
                        $fail('Le véhicule sélectionné n\'est pas disponible.');
                    }
                }
            ],
            'agence_id' => [
                'nullable',
                'exists:agences,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $agence = \App\Models\Agence::where('id', $value)
                            ->where('tenant_id', auth()->user()->tenant_id)
                            ->first();
                        if (!$agence) {
                            $fail('L\'agence sélectionnée n\'existe pas.');
                        }
                    }
                }
            ],
            'date_debut' => [
                'required', 
                'date', 
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $dateFin = $this->input('date_fin');
                    if ($dateFin && $value >= $dateFin) {
                        $fail('La date de début doit être antérieure à la date de fin.');
                    }
                }
            ],
            'date_fin' => [
                'required', 
                'date', 
                'after:date_debut',
                function ($attribute, $value, $fail) {
                    $dateDebut = $this->input('date_debut');
                    if ($dateDebut) {
                        $debut = \Carbon\Carbon::parse($dateDebut);
                        $fin = \Carbon\Carbon::parse($value);
                        $duree = $debut->diffInDays($fin);
                        
                        if ($duree > 365) {
                            $fail('La durée de réservation ne peut pas dépasser 1 an.');
                        }
                    }
                }
            ],
            'heure_debut' => ['nullable', 'date_format:H:i'],
            'heure_fin' => ['nullable', 'date_format:H:i'],
            'lieu_depart' => ['required', 'string', 'max:255'],
            'lieu_retour' => ['required', 'string', 'max:255'],
            'nombre_passagers' => ['required', 'integer', 'min:1', 'max:20'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'in:gps,siège_bébé,chauffeur,assurance_supplementaire'],
            'prix_total' => ['required', 'numeric', 'min:0'],
            'caution' => ['required', 'numeric', 'min:0'],
            'statut' => ['required', 'string', 'in:en_attente,confirmee,en_cours,annulee,terminee'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'motif_annulation' => ['nullable', 'string', 'max:500', 'required_if:statut,annulee'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'vehicule_id.required' => 'Le véhicule est requis.',
            'vehicule_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            'agence_id.exists' => 'L\'agence sélectionnée n\'existe pas.',
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_fin.required' => 'La date de fin est requise.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'lieu_depart.required' => 'Le lieu de départ est requis.',
            'lieu_retour.required' => 'Le lieu de retour est requis.',
            'nombre_passagers.required' => 'Le nombre de passagers est requis.',
            'nombre_passagers.min' => 'Le nombre de passagers doit être au moins 1.',
            'nombre_passagers.max' => 'Le nombre de passagers ne peut pas dépasser 20.',
            'options.*.in' => 'L\'option sélectionnée n\'est pas valide.',
            'prix_total.required' => 'Le prix total est requis.',
            'prix_total.min' => 'Le prix total ne peut pas être négatif.',
            'caution.required' => 'La caution est requise.',
            'caution.min' => 'La caution ne peut pas être négative.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être valide.',
            'motif_annulation.required_if' => 'Le motif d\'annulation est requis quand la réservation est annulée.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'vehicule_id' => 'véhicule',
            'agence_id' => 'agence',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'heure_debut' => 'heure de début',
            'heure_fin' => 'heure de fin',
            'lieu_depart' => 'lieu de départ',
            'lieu_retour' => 'lieu de retour',
            'nombre_passagers' => 'nombre de passagers',
            'options' => 'options',
            'prix_total' => 'prix total',
            'caution' => 'caution',
            'statut' => 'statut',
            'notes' => 'notes',
            'motif_annulation' => 'motif d\'annulation',
        ];
    }
} 