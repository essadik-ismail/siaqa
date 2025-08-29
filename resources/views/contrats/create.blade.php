@extends('layouts.app')

@section('title', 'Nouveau Contrat')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Nouveau Contrat de Location</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('contrats.store') }}">
                @csrf

                <!-- Client and Vehicle Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="client_one_id" class="block text-sm font-medium text-gray-700 mb-2">Client Principal *</label>
                        <select name="client_one_id" id="client_one_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner un client</option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('client_one_id') == $client->id ? 'selected' : '' }}>
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
                                <option value="{{ $client->id }}" {{ old('client_two_id') == $client->id ? 'selected' : '' }}>
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
                                <option value="{{ $vehicule->id }}" {{ old('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
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
                            <option value="en cours" {{ old('etat_contrat') == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('etat_contrat') == 'termine' ? 'selected' : '' }}>Terminé</option>
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
                        <input type="date" name="date_contrat" id="date_contrat" value="{{ old('date_contrat', date('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('date_contrat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_contrat" class="block text-sm font-medium text-gray-700 mb-2">Heure du Contrat</label>
                        <input type="time" name="heure_contrat" id="heure_contrat" value="{{ old('heure_contrat') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('heure_contrat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_retour" class="block text-sm font-medium text-gray-700 mb-2">Date de Retour</label>
                        <input type="date" name="date_retour" id="date_retour" value="{{ old('date_retour') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('date_retour')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_retour" class="block text-sm font-medium text-gray-700 mb-2">Heure de Retour</label>
                        <input type="time" name="heure_retour" id="heure_retour" value="{{ old('heure_retour') }}"
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
                        <input type="text" name="km_depart" id="km_depart" value="{{ old('km_depart') }}" placeholder="ex: 45000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('km_depart')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_depart" class="block text-sm font-medium text-gray-700 mb-2">Heure de Départ</label>
                        <input type="time" name="heure_depart" id="heure_depart" value="{{ old('heure_depart') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('heure_depart')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu_depart" class="block text-sm font-medium text-gray-700 mb-2">Lieu de Départ</label>
                        <input type="text" name="lieu_depart" id="lieu_depart" value="{{ old('lieu_depart') }}" placeholder="ex: Agence principale"
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
                        <input type="text" name="lieu_livraison" id="lieu_livraison" value="{{ old('lieu_livraison') }}" placeholder="ex: Même lieu"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('lieu_livraison')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nbr_jours" class="block text-sm font-medium text-gray-700 mb-2">Nombre de Jours</label>
                        <input type="number" name="nbr_jours" id="nbr_jours" value="{{ old('nbr_jours') }}" min="1" placeholder="ex: 7"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nbr_jours')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix') }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_ht" class="block text-sm font-medium text-gray-700 mb-2">Total HT (€)</label>
                        <input type="number" name="total_ht" id="total_ht" value="{{ old('total_ht') }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('total_ht')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_ttc" class="block text-sm font-medium text-gray-700 mb-2">Total TTC (€)</label>
                        <input type="number" name="total_ttc" id="total_ttc" value="{{ old('total_ttc') }}" min="0" step="0.01" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('total_ttc')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="remise" class="block text-sm font-medium text-gray-700 mb-2">Remise (€)</label>
                        <input type="number" name="remise" id="remise" value="{{ old('remise', 0) }}" min="0" step="0.01" placeholder="0.00"
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
                            <option value="cheque" {{ old('mode_reglement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="espece" {{ old('mode_reglement') == 'espece' ? 'selected' : '' }}>Espèces</option>
                            <option value="tpe" {{ old('mode_reglement') == 'tpe' ? 'selected' : '' }}>TPE</option>
                            <option value="versement" {{ old('mode_reglement') == 'versement' ? 'selected' : '' }}>Versement</option>
                        </select>
                        @error('mode_reglement')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="caution_assurance" class="block text-sm font-medium text-gray-700 mb-2">Caution Assurance</label>
                        <input type="text" name="caution_assurance" id="caution_assurance" value="{{ old('caution_assurance') }}" placeholder="ex: 500€"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('caution_assurance')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position_resrvoir" class="block text-sm font-medium text-gray-700 mb-2">Position Réservoir</label>
                        <select name="position_resrvoir" id="position_resrvoir" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="0" {{ old('position_resrvoir') == '0' ? 'selected' : '' }}>Vide</option>
                            <option value="1/4" {{ old('position_resrvoir') == '1/4' ? 'selected' : '' }}>1/4</option>
                            <option value="2/4" {{ old('position_resrvoir') == '2/4' ? 'selected' : '' }}>2/4</option>
                            <option value="3/4" {{ old('position_resrvoir') == '3/4' ? 'selected' : '' }}>3/4</option>
                            <option value="4/4" {{ old('position_resrvoir') == '4/4' ? 'selected' : '' }}>Plein</option>
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
                            <input type="checkbox" name="documents" value="1" {{ old('documents', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Documents</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="cric" value="1" {{ old('cric', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Cric</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="siege_enfant" value="1" {{ old('siege_enfant', 0) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Siège Enfant</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="roue_secours" value="1" {{ old('roue_secours', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Roue de Secours</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="poste_radio" value="1" {{ old('poste_radio', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Poste Radio</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="plaque_panne" value="1" {{ old('plaque_panne', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Plaque de Panne</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="gillet" value="1" {{ old('gillet', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Gilet de Sécurité</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="extincteur" value="1" {{ old('extincteur', 1) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">Extincteur</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="prolongation" class="block text-sm font-medium text-gray-700 mb-2">Prolongation</label>
                        <input type="text" name="prolongation" id="prolongation" value="{{ old('prolongation') }}" placeholder="ex: 2 jours supplémentaires"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prolongation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="autre_fichier" class="block text-sm font-medium text-gray-700 mb-2">Autre Fichier</label>
                        <input type="text" name="autre_fichier" id="autre_fichier" value="{{ old('autre_fichier') }}" placeholder="ex: Contrat d'assurance"
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
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
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
                        Créer le Contrat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 