<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
        $clientId = $this->route('client');
        $tenantId = auth()->user()->tenant_id ?? null;
        
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
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
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
            'pays' => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'numero_permis' => [
                'required', 
                'string', 
                'max:50',
                Rule::unique('clients')->ignore($clientId)->where(function ($query) use ($tenantId) {
                    if ($tenantId) {
                        return $query->where('tenant_id', $tenantId);
                    }
                    return $query->whereNull('tenant_id');
                })
            ],
            'date_obtention_permis' => ['required', 'date', 'before_or_equal:today'],
            'numero_piece_identite' => [
                'required', 
                'string', 
                'max:50',
                Rule::unique('clients')->ignore($clientId)->where(function ($query) use ($tenantId) {
                    if ($tenantId) {
                        return $query->where('tenant_id', $tenantId);
                    }
                    return $query->whereNull('tenant_id');
                })
            ],
            'type_piece_identite' => ['required', 'string', 'in:carte_nationale,passeport,permis_conduire,carte_sejour'],
            'date_expiration_piece' => ['required', 'date', 'after:today'],
            'profession' => ['nullable', 'string', 'max:255'],
            'employeur' => ['nullable', 'string', 'max:255'],
            'revenu_mensuel' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_blacklist' => ['boolean'],
            'motif_blacklist' => ['nullable', 'string', 'max:500', 'required_if:is_blacklist,1'],
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
            'prenom.required' => 'Le prénom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre client.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'adresse.required' => 'L\'adresse est requise.',
            'ville.required' => 'La ville est requise.',
            'code_postal.required' => 'Le code postal est requis.',
            'pays.required' => 'Le pays est requis.',
            'date_naissance.required' => 'La date de naissance est requise.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'numero_permis.required' => 'Le numéro de permis est requis.',
            'numero_permis.unique' => 'Ce numéro de permis est déjà utilisé par un autre client.',
            'date_obtention_permis.required' => 'La date d\'obtention du permis est requise.',
            'date_obtention_permis.before_or_equal' => 'La date d\'obtention du permis ne peut pas être dans le futur.',
            'numero_piece_identite.required' => 'Le numéro de pièce d\'identité est requis.',
            'numero_piece_identite.unique' => 'Ce numéro de pièce d\'identité est déjà utilisé par un autre client.',
            'type_piece_identite.required' => 'Le type de pièce d\'identité est requis.',
            'type_piece_identite.in' => 'Le type de pièce d\'identité doit être valide.',
            'date_expiration_piece.required' => 'La date d\'expiration de la pièce d\'identité est requise.',
            'date_expiration_piece.after' => 'La pièce d\'identité doit être valide (date d\'expiration dans le futur).',
            'revenu_mensuel.min' => 'Le revenu mensuel ne peut pas être négatif.',
            'motif_blacklist.required_if' => 'Le motif de blacklist est requis quand le client est blacklisté.',
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
            'code_postal' => 'code postal',
            'pays' => 'pays',
            'date_naissance' => 'date de naissance',
            'numero_permis' => 'numéro de permis',
            'date_obtention_permis' => 'date d\'obtention du permis',
            'numero_piece_identite' => 'numéro de pièce d\'identité',
            'type_piece_identite' => 'type de pièce d\'identité',
            'date_expiration_piece' => 'date d\'expiration de la pièce d\'identité',
            'profession' => 'profession',
            'employeur' => 'employeur',
            'revenu_mensuel' => 'revenu mensuel',
            'notes' => 'notes',
            'is_blacklist' => 'statut blacklist',
            'motif_blacklist' => 'motif de blacklist',
            'image' => 'image principale',
            'images' => 'images supplémentaires',
        ];
    }
} 