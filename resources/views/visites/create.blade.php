@extends('layouts.app')

@section('title', 'Nouvelle Visite')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Nouvelle Visite</h2>
                    <p class="text-gray-600">Planifier une nouvelle visite technique pour un véhicule</p>
                </div>
                <a href="{{ route('visites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('visites.store') }}" class="space-y-6">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules ?? [] as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ request('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->marque ? $vehicule->marque->nom : 'Marque inconnue' }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicule_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_visite" class="block text-sm font-medium text-gray-700 mb-2">Date de visite *</label>
                    <input type="date" name="date_visite" id="date_visite" value="{{ old('date_visite') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_visite') border-red-500 @enderror">
                    @error('date_visite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Inspection Type and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type_visite" class="block text-sm font-medium text-gray-700 mb-2">Type de visite *</label>
                    <select name="type_visite" id="type_visite" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_visite') border-red-500 @enderror">
                        <option value="">Sélectionner le type</option>
                        <option value="visite_technique" {{ old('type_visite') == 'visite_technique' ? 'selected' : '' }}>Visite technique</option>
                        <option value="controle_emissions" {{ old('type_visite') == 'controle_emissions' ? 'selected' : '' }}>Contrôle des émissions</option>
                        <option value="verification_securite" {{ old('type_visite') == 'verification_securite' ? 'selected' : '' }}>Vérification de sécurité</option>
                        <option value="inspection_generale" {{ old('type_visite') == 'inspection_generale' ? 'selected' : '' }}>Inspection générale</option>
                        <option value="controle_equipements" {{ old('type_visite') == 'controle_equipements' ? 'selected' : '' }}>Contrôle des équipements</option>
                        <option value="autre" {{ old('type_visite') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_visite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="statut" id="statut" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                        <option value="">Sélectionner le statut</option>
                        <option value="planifiée" {{ old('statut') == 'planifiée' ? 'selected' : '' }}>Planifiée</option>
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminée" {{ old('statut') == 'terminée' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulée" {{ old('statut') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Inspection Center and Inspector -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="centre_visite" class="block text-sm font-medium text-gray-700 mb-2">Centre de visite</label>
                    <input type="text" name="centre_visite" id="centre_visite" value="{{ old('centre_visite') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('centre_visite') border-red-500 @enderror"
                           placeholder="Nom du centre">
                    @error('centre_visite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="inspecteur" class="block text-sm font-medium text-gray-700 mb-2">Inspecteur</label>
                    <input type="text" name="inspecteur" id="inspecteur" value="{{ old('inspecteur') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('inspecteur') border-red-500 @enderror"
                           placeholder="Nom de l'inspecteur">
                    @error('inspecteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cost and Results -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cout" class="block text-sm font-medium text-gray-700 mb-2">Coût (€)</label>
                    <input type="number" name="cout" id="cout" value="{{ old('cout') }}" step="0.01" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cout') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('cout')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="resultat" class="block text-sm font-medium text-gray-700 mb-2">Résultat</label>
                    <select name="resultat" id="resultat" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('resultat') border-red-500 @enderror">
                        <option value="">Sélectionner le résultat</option>
                        <option value="favorable" {{ old('resultat') == 'favorable' ? 'selected' : '' }}>Favorable</option>
                        <option value="defavorable" {{ old('resultat') == 'defavorable' ? 'selected' : '' }}>Défavorable</option>
                        <option value="avec_reserves" {{ old('resultat') == 'avec_reserves' ? 'selected' : '' }}>Avec réserves</option>
                        <option value="en_attente" {{ old('resultat') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                    @error('resultat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prochaine_visite" class="block text-sm font-medium text-gray-700 mb-2">Prochaine visite</label>
                    <input type="date" name="prochaine_visite" id="prochaine_visite" value="{{ old('prochaine_visite') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prochaine_visite') border-red-500 @enderror">
                    @error('prochaine_visite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kilometrage_visite" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage lors de la visite</label>
                    <input type="number" name="kilometrage_visite" id="kilometrage_visite" value="{{ old('kilometrage_visite') }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kilometrage_visite') border-red-500 @enderror"
                           placeholder="0">
                    @error('kilometrage_visite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">Observations</label>
                <textarea name="observations" id="observations" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observations') border-red-500 @enderror"
                          placeholder="Observations, remarques, points à vérifier...">{{ old('observations') }}</textarea>
                @error('observations')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center flex-1">
                    <i class="fas fa-save mr-2"></i>Planifier la visite
                </button>
                <a href="{{ route('visites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill vehicle_id if passed as query parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const vehiculeId = urlParams.get('vehicule_id');
    if (vehiculeId) {
        document.getElementById('vehicule_id').value = vehiculeId;
    }
});

// Auto-calculate next visit date (1 year from visit date)
document.getElementById('date_visite').addEventListener('change', function() {
    const visitDate = new Date(this.value);
    if (visitDate) {
        const nextVisit = new Date(visitDate);
        nextVisit.setFullYear(nextVisit.getFullYear() + 1);
        document.getElementById('prochaine_visite').value = nextVisit.toISOString().split('T')[0];
    }
});
</script>
@endsection
