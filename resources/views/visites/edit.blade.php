@extends('layouts.app')

@section('title', 'Modifier la Visite Technique')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Modifier la Visite Technique</h2>
            <p class="text-gray-600 text-lg">Modifier les informations de la visite technique</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('visites.show', $visite) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('visites.update', $visite) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Vehicle Selection -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Sélection du Véhicule</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                        <select id="vehicule_id" name="vehicule_id" required class="form-select w-full">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}" {{ $visite->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque->nom }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Visit Information -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Informations de la Visite</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_visite" class="block text-sm font-medium text-gray-700 mb-2">Date de Visite *</label>
                        <input type="date" id="date_visite" name="date_visite" 
                               value="{{ $visite->date_visite ? $visite->date_visite->format('Y-m-d') : '' }}" 
                               required class="form-input w-full">
                        @error('date_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="type_visite" class="block text-sm font-medium text-gray-700 mb-2">Type de Visite</label>
                        <select id="type_visite" name="type_visite" class="form-select w-full">
                            <option value="">Sélectionner le type</option>
                            <option value="controle_technique" {{ $visite->type_visite === 'controle_technique' ? 'selected' : '' }}>Contrôle Technique</option>
                            <option value="visite_periodique" {{ $visite->type_visite === 'visite_periodique' ? 'selected' : '' }}>Visite Périodique</option>
                            <option value="visite_pre_vente" {{ $visite->type_visite === 'visite_pre_vente' ? 'selected' : '' }}>Visite Pré-Vente</option>
                            <option value="autre" {{ $visite->type_visite === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select id="statut" name="statut" required class="form-select w-full">
                            <option value="planifiee" {{ $visite->statut === 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ $visite->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ $visite->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulee" {{ $visite->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="centre_inspection" class="block text-sm font-medium text-gray-700 mb-2">Centre d'Inspection</label>
                        <input type="text" id="centre_inspection" name="centre_inspection" 
                               value="{{ $visite->centre_inspection }}" 
                               placeholder="Ex: Centre Auto Plus" class="form-input w-full">
                        @error('centre_inspection')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inspection Details -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Détails de l'Inspection</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="inspecteur" class="block text-sm font-medium text-gray-700 mb-2">Inspecteur</label>
                        <input type="text" id="inspecteur" name="inspecteur" 
                               value="{{ $visite->inspecteur }}" 
                               placeholder="Ex: Nom de l'inspecteur" class="form-input w-full">
                        @error('inspecteur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cout" class="block text-sm font-medium text-gray-700 mb-2">Coût (€)</label>
                        <input type="number" id="cout" name="cout" 
                               value="{{ $visite->cout }}" 
                               step="0.01" placeholder="Ex: 75.00" class="form-input w-full">
                        @error('cout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="resultats" class="block text-sm font-medium text-gray-700 mb-2">Résultats</label>
                        <select id="resultats" name="resultats" class="form-select w-full">
                            <option value="">Sélectionner le résultat</option>
                            <option value="favorable" {{ $visite->resultats === 'favorable' ? 'selected' : '' }}>Favorable</option>
                            <option value="defavorable" {{ $visite->resultats === 'defavorable' ? 'selected' : '' }}>Défavorable</option>
                            <option value="avec_reserves" {{ $visite->resultats === 'avec_reserves' ? 'selected' : '' }}>Avec Réserves</option>
                            <option value="en_attente" {{ $visite->resultats === 'en_attente' ? 'selected' : '' }}>En Attente</option>
                        </select>
                        @error('resultats')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kilometrage" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage</label>
                        <input type="number" id="kilometrage" name="kilometrage" 
                               value="{{ $visite->kilometrage }}" 
                               placeholder="Ex: 50000" class="form-input w-full">
                        @error('kilometrage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Next Visit Planning -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Planification Prochaine Visite</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prochaine_visite" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Visite</label>
                        <input type="date" id="prochaine_visite" name="prochaine_visite" 
                               value="{{ $visite->prochaine_visite ? $visite->prochaine_visite->format('Y-m-d') : '' }}" 
                               class="form-input w-full">
                        @error('prochaine_visite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Notes et Observations</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" 
                              placeholder="Ajoutez des notes ou observations sur cette visite..." 
                              class="form-textarea w-full">{{ $visite->notes }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('visites.show', $visite) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="btn-primary px-8 py-3">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
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
