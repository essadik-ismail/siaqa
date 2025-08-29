<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehiculeRequest extends FormRequest
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
        $vehiculeId = $this->route('vehicule');
        
        return [
            'marque_id' => [
                'required', 
                'exists:marques,id',
                function ($attribute, $value, $fail) {
                    $marque = \App\Models\Marque::where('id', $value)
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->first();
                    if (!$marque) {
                        $fail('La marque sélectionnée n\'existe pas.');
                    }
                }
            ],
            'name' => ['required', 'string', 'max:255'],
            'immatriculation' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('vehicules')->ignore($vehiculeId)->where(function ($query) {
                    return $query->where('tenant_id', auth()->user()->tenant_id);
                })
            ],
            'couleur' => ['nullable', 'string', 'max:50'],
            'type_carburant' => ['nullable', 'string', 'in:essence,diesel,hybride,electrique,gpl'],
            'nombre_cylindre' => ['nullable', 'integer', 'min:0', 'max:16'],
            'nbr_place' => ['nullable', 'integer', 'min:1', 'max:20'],
            'prix_location_jour' => ['required', 'numeric', 'min:0'],
            'prix_achat' => ['nullable', 'numeric', 'min:0'],
            'caution' => ['nullable', 'numeric', 'min:0'],
            'kilometrage_actuel' => ['nullable', 'integer', 'min:0'],
            'categorie_vehicule' => ['nullable', 'string', 'in:A,B,C,D,E'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'agence_id' => [
                'required', 
                'exists:agences,id',
                function ($attribute, $value, $fail) {
                    $agence = \App\Models\Agence::where('id', $value)
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->first();
                    if (!$agence) {
                        $fail('L\'agence sélectionnée n\'existe pas.');
                    }
                }
            ],
            'statut' => ['required', 'string', 'in:disponible,en_location,en_maintenance,hors_service'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'marque_id.required' => 'La marque est requise.',
            'marque_id.exists' => 'La marque sélectionnée n\'existe pas.',
            'name.required' => 'Le nom est requis.',
            'immatriculation.required' => 'L\'immatriculation est requise.',
            'immatriculation.unique' => 'Cette immatriculation est déjà utilisée par un autre véhicule.',
            'couleur.max' => 'La couleur ne peut pas dépasser 50 caractères.',
            'type_carburant.in' => 'Le type de carburant doit être valide.',
            'nombre_cylindre.min' => 'Le nombre de cylindres ne peut pas être négatif.',
            'nombre_cylindre.max' => 'Le nombre de cylindres ne peut pas dépasser 16.',
            'nbr_place.min' => 'Le nombre de places doit être au moins 1.',
            'nbr_place.max' => 'Le nombre de places ne peut pas dépasser 20.',
            'prix_location_jour.required' => 'Le prix journalier de location est requis.',
            'prix_location_jour.min' => 'Le prix journalier de location ne peut pas être négatif.',
            'prix_achat.min' => 'Le prix d\'achat ne peut pas être négatif.',
            'caution.min' => 'La caution ne peut pas être négative.',
            'kilometrage_actuel.min' => 'Le kilométrage actuel ne peut pas être négatif.',
            'categorie_vehicule.in' => 'La catégorie du véhicule doit être valide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'image.max' => 'L\'image ne peut pas dépasser 2048 kilooctets.',
            'images.*.image' => 'Les images doivent être au format jpeg, png, jpg, gif ou svg.',
            'images.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou svg.',
            'images.*.max' => 'Les images ne peuvent pas dépasser 2048 kilooctets.',
            'agence_id.required' => 'L\'agence est requise.',
            'agence_id.exists' => 'L\'agence sélectionnée n\'existe pas.',
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'marque_id' => 'marque',
            'name' => 'nom',
            'immatriculation' => 'immatriculation',
            'couleur' => 'couleur',
            'type_carburant' => 'type de carburant',
            'nombre_cylindre' => 'nombre de cylindres',
            'nbr_place' => 'nombre de places',
            'prix_location_jour' => 'prix de location journalier',
            'prix_achat' => 'prix d\'achat',
            'caution' => 'caution',
            'kilometrage_actuel' => 'kilométrage actuel',
            'categorie_vehicule' => 'catégorie du véhicule',
            'description' => 'description',
            'image' => 'image',
            'images' => 'images',
            'agence_id' => 'agence',
            'statut' => 'statut',
            'is_active' => 'statut actif',
        ];
    }
} 