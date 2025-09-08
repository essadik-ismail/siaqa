@extends('layouts.app')

@section('title', 'Modifier la Visite Technique')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier la Visite Technique</h1>
                <p class="text-gray-600">Modifier les informations de la visite technique</p>
            </div>
            <a href="{{ route('visites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('visites.update', $visite) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Selection -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                        <select id="vehicule_id" name="vehicule_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}" {{ $visite->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque ? $vehicule->marque->nom : 'Marque inconnue' }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Visit Information -->
                    <div>
                        <label for="date_visite" class="block text-sm font-medium text-gray-700 mb-2">Date de Visite *</label>
                        <input type="date" id="date_visite" name="date_visite" 
                               value="{{ $visite->date_visite ? $visite->date_visite->format('Y-m-d') : '' }}" 
                               required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_visite') border-red-500 @enderror">
                        @error('date_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_visite" class="block text-sm font-medium text-gray-700 mb-2">Type de Visite</label>
                        <select id="type_visite" name="type_visite" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_visite') border-red-500 @enderror">
                            <option value="">Sélectionner le type</option>
                            <option value="visite_technique" {{ $visite->type_visite === 'visite_technique' ? 'selected' : '' }}>Visite technique</option>
                            <option value="controle_emissions" {{ $visite->type_visite === 'controle_emissions' ? 'selected' : '' }}>Contrôle des émissions</option>
                            <option value="verification_securite" {{ $visite->type_visite === 'verification_securite' ? 'selected' : '' }}>Vérification de sécurité</option>
                            <option value="inspection_generale" {{ $visite->type_visite === 'inspection_generale' ? 'selected' : '' }}>Inspection générale</option>
                            <option value="controle_equipements" {{ $visite->type_visite === 'controle_equipements' ? 'selected' : '' }}>Contrôle des équipements</option>
                            <option value="autre" {{ $visite->type_visite === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select id="statut" name="statut" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                            <option value="planifiée" {{ $visite->statut === 'planifiée' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ $visite->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminée" {{ $visite->statut === 'terminée' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulée" {{ $visite->statut === 'annulée' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="centre_inspection" class="block text-sm font-medium text-gray-700 mb-2">Centre d'Inspection</label>
                        <input type="text" id="centre_inspection" name="centre_inspection" 
                               value="{{ $visite->centre_inspection }}" 
                               placeholder="Ex: Centre Auto Plus" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('centre_inspection') border-red-500 @enderror">
                        @error('centre_inspection')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inspection Details -->
                    <div>
                        <label for="inspecteur" class="block text-sm font-medium text-gray-700 mb-2">Inspecteur</label>
                        <input type="text" id="inspecteur" name="inspecteur" 
                               value="{{ $visite->inspecteur }}" 
                               placeholder="Ex: Nom de l'inspecteur" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('inspecteur') border-red-500 @enderror">
                        @error('inspecteur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cout" class="block text-sm font-medium text-gray-700 mb-2">Coût (€)</label>
                        <input type="number" id="cout" name="cout" 
                               value="{{ $visite->cout }}" 
                               step="0.01" placeholder="Ex: 75.00" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cout') border-red-500 @enderror">
                        @error('cout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="resultat" class="block text-sm font-medium text-gray-700 mb-2">Résultats</label>
                        <select id="resultat" name="resultat" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('resultat') border-red-500 @enderror">
                            <option value="">Sélectionner le résultat</option>
                            <option value="favorable" {{ $visite->resultat === 'favorable' ? 'selected' : '' }}>Favorable</option>
                            <option value="defavorable" {{ $visite->resultat === 'defavorable' ? 'selected' : '' }}>Défavorable</option>
                            <option value="avec_reserves" {{ $visite->resultat === 'avec_reserves' ? 'selected' : '' }}>Avec Réserves</option>
                            <option value="en_attente" {{ $visite->resultat === 'en_attente' ? 'selected' : '' }}>En Attente</option>
                        </select>
                        @error('resultat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometrage" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage</label>
                        <input type="number" id="kilometrage" name="kilometrage" 
                               value="{{ $visite->kilometrage }}" 
                               placeholder="Ex: 50000" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kilometrage') border-red-500 @enderror">
                        @error('kilometrage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Next Visit Planning -->
                    <div>
                        <label for="prochaine_visite" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Visite</label>
                        <input type="date" id="prochaine_visite" name="prochaine_visite" 
                               value="{{ $visite->prochaine_visite ? $visite->prochaine_visite->format('Y-m-d') : '' }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prochaine_visite') border-red-500 @enderror">
                        @error('prochaine_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">Notes et Observations</label>
                    <textarea id="observations" name="observations" rows="4" 
                              placeholder="Ajoutez des notes ou observations sur cette visite..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('observations') border-red-500 @enderror">{{ $visite->observations }}</textarea>
                    @error('observations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('visites.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate next visit date (default: 1 year from current visit)
document.getElementById('date_visite').addEventListener('change', function() {
    const visitDate = new Date(this.value);
    if (visitDate) {
        const nextVisit = new Date(visitDate);
        nextVisit.setFullYear(nextVisit.getFullYear() + 1);
        document.getElementById('prochaine_visite').value = nextVisit.toISOString().split('T')[0];
    }
});

// Auto-fill vehicle ID if passed via URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const vehiculeId = urlParams.get('vehicule_id');
    if (vehiculeId) {
        document.getElementById('vehicule_id').value = vehiculeId;
    }
});
</script>
@endsection
