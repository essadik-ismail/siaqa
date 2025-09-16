@extends('layouts.app')

@section('title', 'Modifier Contrat')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Modifier Contrat de Location</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('contrats.update', $contrat) }}">
                @csrf
                @method('PUT')

                <!-- Client and Vehicle Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="client_one_id" class="block text-sm font-medium text-gray-700 mb-2">Client Principal *</label>
                        <select name="client_one_id" id="client_one_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner un client</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_one_id', $contrat->client_one_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_one_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="client_two_id" class="block text-sm font-medium text-gray-700 mb-2">Client Secondaire (optionnel)</label>
                        <select name="client_two_id" id="client_two_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Aucun client secondaire</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_two_id', $contrat->client_two_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_two_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                        <select name="vehicule_id" id="vehicule_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach($vehicules ?? [] as $vehicule)
                                <option value="{{ $vehicule->id }}" {{ old('vehicule_id', $contrat->vehicule_id) == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque->marque }} {{ $vehicule->name }} - {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="etat_contrat" class="block text-sm font-medium text-gray-700 mb-2">État du Contrat *</label>
                        <select name="etat_contrat" id="etat_contrat" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner un état</option>
                            <option value="en cours" {{ old('etat_contrat', $contrat->etat_contrat) == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('etat_contrat', $contrat->etat_contrat) == 'termine' ? 'selected' : '' }}>Terminé</option>
                        </select>
                        @error('etat_contrat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contract Dates and Times -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="date_contrat" class="block text-sm font-medium text-gray-700 mb-2">Date du Contrat *</label>
                        <input type="date" name="date_contrat" id="date_contrat" value="{{ old('date_contrat', $contrat->date_contrat) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('date_contrat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_contrat" class="block text-sm font-medium text-gray-700 mb-2">Heure du Contrat</label>
                        <input type="time" name="heure_contrat" id="heure_contrat" value="{{ old('heure_contrat', $contrat->heure_contrat) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('heure_contrat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_retour" class="block text-sm font-medium text-gray-700 mb-2">Date de Retour</label>
                        <input type="date" name="date_retour" id="date_retour" value="{{ old('date_retour', $contrat->date_retour) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('date_retour')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_retour" class="block text-sm font-medium text-gray-700 mb-2">Heure de Retour</label>
                        <input type="time" name="heure_retour" id="heure_retour" value="{{ old('heure_retour', $contrat->heure_retour) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('heure_retour')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Departure Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label for="km_depart" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage de Départ</label>
                        <input type="text" name="km_depart" id="km_depart" value="{{ old('km_depart', $contrat->km_depart) }}" placeholder="ex: 45000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('km_depart')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_depart" class="block text-sm font-medium text-gray-700 mb-2">Heure de Départ</label>
                        <input type="time" name="heure_depart" id="heure_depart" value="{{ old('heure_depart', $contrat->heure_depart) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('heure_depart')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu_depart" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Départ</label>
                        <input type="text" name="lieu_depart" id="lieu_depart" value="{{ old('lieu_depart', $contrat->lieu_depart) }}" placeholder="ex: Agence principale"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('lieu_depart')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Return Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="lieu_livraison" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Livraison</label>
                        <input type="text" name="lieu_livraison" id="lieu_livraison" value="{{ old('lieu_livraison', $contrat->lieu_livraison) }}" placeholder="ex: Même lieu"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('lieu_livraison')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nbr_jours" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Jours</label>
                        <input type="number" name="nbr_jours" id="nbr_jours" value="{{ old('nbr_jours', $contrat->nbr_jours) }}" min="1" placeholder="ex: 7"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nbr_jours')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (DH)</label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix', $contrat->prix) }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_ht" class="block text-sm font-medium text-gray-700 mb-2">Total HT (DH)</label>
                        <input type="number" name="total_ht" id="total_ht" value="{{ old('total_ht', $contrat->total_ht) }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('total_ht')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_ttc" class="block text-sm font-medium text-gray-700 mb-2">Total TTC (DH)</label>
                        <input type="number" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', $contrat->total_ttc) }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('total_ttc')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="remise" class="block text-sm font-medium text-gray-700 mb-2">Remise (DH)</label>
                        <input type="number" name="remise" id="remise" value="{{ old('remise', $contrat->remise) }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('remise')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment and Insurance -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label for="mode_reglement" class="block text-sm font-medium text-gray-700 mb-2">Mode de Règlement</label>
                        <select name="mode_reglement" id="mode_reglement" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner un mode</option>
                            <option value="cheque" {{ old('mode_reglement', $contrat->mode_reglement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="espece" {{ old('mode_reglement', $contrat->mode_reglement) == 'espece' ? 'selected' : '' }}>Espèces</option>
                            <option value="tpe" {{ old('mode_reglement', $contrat->mode_reglement) == 'tpe' ? 'selected' : '' }}>TPE</option>
                            <option value="versement" {{ old('mode_reglement', $contrat->mode_reglement) == 'versement' ? 'selected' : '' }}>Versement</option>
                        </select>
                        @error('mode_reglement')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="caution_assurance" class="block text-sm font-medium text-gray-700 mb-2">Caution Assurance</label>
                        <input type="text" name="caution_assurance" id="caution_assurance" value="{{ old('caution_assurance', $contrat->caution_assurance) }}" placeholder="ex: 500 DH"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('caution_assurance')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position_resrvoir" class="block text-sm font-medium text-gray-700 mb-2">Position Réservoir</label>
                        <select name="position_resrvoir" id="position_resrvoir" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="0" {{ old('position_resrvoir', $contrat->position_resrvoir) == '0' ? 'selected' : '' }}>Vide</option>
                            <option value="1/4" {{ old('position_resrvoir', $contrat->position_resrvoir) == '1/4' ? 'selected' : '' }}>1/4</option>
                            <option value="2/4" {{ old('position_resrvoir', $contrat->position_resrvoir) == '2/4' ? 'selected' : '' }}>2/4</option>
                            <option value="3/4" {{ old('position_resrvoir', $contrat->position_resrvoir) == '3/4' ? 'selected' : '' }}>3/4</option>
                            <option value="4/4" {{ old('position_resrvoir', $contrat->position_resrvoir) == '4/4' ? 'selected' : '' }}>Plein</option>
                        </select>
                        @error('position_resrvoir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Equipment Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Équipements Inclus</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="documents" value="1" {{ old('documents', $contrat->documents) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Documents</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="cric" value="1" {{ old('cric', $contrat->cric) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Cric</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="siege_enfant" value="1" {{ old('siege_enfant', $contrat->siege_enfant) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Siège Enfant</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="roue_secours" value="1" {{ old('roue_secours', $contrat->roue_secours) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Roue de Secours</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="poste_radio" value="1" {{ old('poste_radio', $contrat->poste_radio) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Poste Radio</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="plaque_panne" value="1" {{ old('plaque_panne', $contrat->plaque_panne) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Plaque de Panne</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="gillet" value="1" {{ old('gillet', $contrat->gillet) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Gilet de Sécurité</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="extincteur" value="1" {{ old('extincteur', $contrat->extincteur) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Extincteur</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="prolongation" class="block text-sm font-medium text-gray-700 mb-2">Prolongation</label>
                        <input type="text" name="prolongation" id="prolongation" value="{{ old('prolongation', $contrat->prolongation) }}" placeholder="ex: 2 jours supplémentaires"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prolongation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="autre_fichier" class="block text-sm font-medium text-gray-700 mb-2">Autre Fichier</label>
                        <input type="text" name="autre_fichier" id="autre_fichier" value="{{ old('autre_fichier', $contrat->autre_fichier) }}" placeholder="ex: Contrat d'assurance"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('autre_fichier')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description et Conditions Particulières</label>
                    <textarea name="description" id="description" rows="4" placeholder="Décrivez les conditions particulières du contrat..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $contrat->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('contrats.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        Mettre à jour le Contrat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // Validation rules
    const validationRules = {
        client_one_id: {
            required: true,
            message: 'Le client principal est requis'
        },
        vehicule_id: {
            required: true,
            message: 'Le véhicule est requis'
        },
        etat_contrat: {
            required: true,
            message: 'L\'état du contrat est requis'
        },
        date_contrat: {
            required: true,
            message: 'La date du contrat est requise'
        },
        date_retour: {
            required: false,
            message: 'La date de retour doit être postérieure ou égale à la date du contrat'
        },
        km_depart: {
            pattern: /^\d+$/,
            message: 'Le kilométrage doit être un nombre entier'
        },
        nbr_jours: {
            min: 1,
            message: 'Le nombre de jours doit être au moins 1'
        },
        prix: {
            min: 0,
            message: 'Le prix ne peut pas être négatif'
        },
        total_ht: {
            min: 0,
            message: 'Le total HT ne peut pas être négatif'
        },
        total_ttc: {
            min: 0,
            message: 'Le total TTC ne peut pas être négatif'
        },
        remise: {
            min: 0,
            message: 'La remise ne peut pas être négative'
        }
    };

    // Add validation styles
    function addValidationStyles(input, isValid, message) {
        const errorElement = input.parentNode.querySelector('.validation-error');
        
        // Remove existing error styling
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
        
        if (errorElement) {
            errorElement.remove();
        }
        
        if (!isValid) {
            input.classList.remove('border-gray-300');
            input.classList.add('border-red-500');
            
            // Add error message
            const errorDiv = document.createElement('p');
            errorDiv.className = 'text-red-500 text-sm mt-1 validation-error';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        } else {
            input.classList.remove('border-gray-300');
            input.classList.add('border-green-500');
        }
    }

    // Validate individual field
    function validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();
        const rules = validationRules[fieldName];
        
        if (!rules) return true;
        
        let isValid = true;
        let message = '';
        
        // Required validation
        if (rules.required && !value) {
            isValid = false;
            message = rules.message;
        }
        // Pattern validation
        else if (value && rules.pattern && !rules.pattern.test(value)) {
            isValid = false;
            message = rules.message;
        }
        // Min value validation
        else if (value && rules.min !== undefined && parseFloat(value) < rules.min) {
            isValid = false;
            message = rules.message;
        }
        
        addValidationStyles(input, isValid, message);
        return isValid;
    }

    // Validate date fields
    function validateDates() {
        const dateContrat = document.getElementById('date_contrat');
        const dateRetour = document.getElementById('date_retour');
        
        let isValid = true;
        
        // Validate return date is after or equal to contract date
        if (dateContrat.value && dateRetour.value) {
            const contratDate = new Date(dateContrat.value);
            const retourDate = new Date(dateRetour.value);
            
            if (retourDate < contratDate) {
                addValidationStyles(dateRetour, false, 'La date de retour doit être postérieure ou égale à la date du contrat');
                isValid = false;
            } else {
                addValidationStyles(dateRetour, true, '');
            }
        }
        
        return isValid;
    }

    // Auto-calculate number of days
    function calculateDays() {
        const dateContrat = document.getElementById('date_contrat');
        const dateRetour = document.getElementById('date_retour');
        const nbrJours = document.getElementById('nbr_jours');
        
        if (dateContrat.value && dateRetour.value) {
            const contratDate = new Date(dateContrat.value);
            const retourDate = new Date(dateRetour.value);
            const days = Math.ceil((retourDate - contratDate) / (1000 * 60 * 60 * 24)) + 1;
            
            if (days > 0) {
                nbrJours.value = days;
                addValidationStyles(nbrJours, true, '');
            }
        }
    }

    // Auto-calculate totals
    function calculateTotals() {
        const prix = parseFloat(document.getElementById('prix').value) || 0;
        const remise = parseFloat(document.getElementById('remise').value) || 0;
        const nbrJours = parseFloat(document.getElementById('nbr_jours').value) || 1;
        
        // Calculate total HT
        const totalHT = (prix * nbrJours) - remise;
        document.getElementById('total_ht').value = totalHT.toFixed(2);
        
        // Calculate total TTC (assuming 20% VAT)
        const totalTTC = totalHT * 1.20;
        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        
        // Validate totals
        addValidationStyles(document.getElementById('total_ht'), true, '');
        addValidationStyles(document.getElementById('total_ttc'), true, '');
    }

    // Validate client selection
    function validateClientSelection() {
        const clientOne = document.getElementById('client_one_id');
        const clientTwo = document.getElementById('client_two_id');
        
        if (clientOne.value && clientTwo.value && clientOne.value === clientTwo.value) {
            addValidationStyles(clientTwo, false, 'Le client secondaire doit être différent du client principal');
            return false;
        } else {
            addValidationStyles(clientTwo, true, '');
            return true;
        }
    }

    // Add event listeners
    inputs.forEach(input => {
        // Real-time validation on input
        input.addEventListener('input', function() {
            validateField(this);
            if (this.name === 'date_contrat' || this.name === 'date_retour') {
                validateDates();
                calculateDays();
            }
            if (this.name === 'prix' || this.name === 'remise' || this.name === 'nbr_jours') {
                calculateTotals();
            }
            if (this.name === 'client_one_id' || this.name === 'client_two_id') {
                validateClientSelection();
            }
        });
        
        // Validation on blur
        input.addEventListener('blur', function() {
            validateField(this);
            if (this.name === 'date_contrat' || this.name === 'date_retour') {
                validateDates();
                calculateDays();
            }
            if (this.name === 'prix' || this.name === 'remise' || this.name === 'nbr_jours') {
                calculateTotals();
            }
            if (this.name === 'client_one_id' || this.name === 'client_two_id') {
                validateClientSelection();
            }
        });
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        
        // Validate all fields
        inputs.forEach(input => {
            if (!validateField(input)) {
                isFormValid = false;
            }
        });
        
        // Validate dates
        if (!validateDates()) {
            isFormValid = false;
        }
        
        // Validate client selection
        if (!validateClientSelection()) {
            isFormValid = false;
        }
        
        if (isFormValid) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour en cours...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
        } else {
            // Scroll to first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            errorAlert.innerHTML = '<strong>Erreur:</strong> Veuillez corriger les erreurs ci-dessous avant de soumettre le formulaire.';
            
            const formContainer = document.querySelector('.bg-white.rounded-lg.shadow-md.p-6');
            formContainer.insertBefore(errorAlert, form);
            
            // Remove error alert after 5 seconds
            setTimeout(() => {
                if (errorAlert.parentNode) {
                    errorAlert.remove();
                }
            }, 5000);
        }
    });

    // Add visual feedback for required fields
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
    requiredFields.forEach(field => {
        const label = field.parentNode.querySelector('label');
        if (label && !label.textContent.includes('*')) {
            label.innerHTML = label.innerHTML + ' <span class="text-red-500">*</span>';
        }
    });

    // Initialize calculations
    calculateDays();
    calculateTotals();
});
</script>
@endsection




