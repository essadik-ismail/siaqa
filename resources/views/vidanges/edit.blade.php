@extends('layouts.app')

@section('title', 'Modifier la Vidange')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Modifier la Vidange</h2>
            <p class="text-gray-600 text-lg">Modifier les informations de la vidange</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('vidanges.show', $vidange) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('vidanges.update', $vidange) }}" method="POST" class="space-y-6">
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
                                <option value="{{ $vehicule->id }}" {{ $vidange->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque ? $vehicule->marque->nom : 'Marque inconnue' }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Planning Information -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Informations de Planification</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date Planifiée *</label>
                        <input type="date" id="date_prevue" name="date_prevue" 
                               value="{{ $vidange->date_prevue ? $vidange->date_prevue->format('Y-m-d') : '' }}" 
                               required class="form-input w-full">
                        @error('date_prevue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select id="statut" name="statut" required class="form-select w-full">
                            <option value="planifiee" {{ $vidange->statut === 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ $vidange->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ $vidange->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulee" {{ $vidange->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mileage Information -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Informations Kilométriques</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage Actuel *</label>
                        <input type="number" id="kilometrage_actuel" name="kilometrage_actuel" 
                               value="{{ $vidange->kilometrage_actuel }}" 
                               placeholder="Ex: 50000" required class="form-input w-full">
                        @error('kilometrage_actuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kilometrage_prochaine" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Vidange (km)</label>
                        <input type="number" id="kilometrage_prochaine" name="kilometrage_prochaine" 
                               value="{{ $vidange->kilometrage_prochaine }}" 
                               placeholder="Ex: 60000" class="form-input w-full">
                        @error('kilometrage_prochaine')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Oil and Filter Information -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Huile et Filtres</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type_huile" class="block text-sm font-medium text-gray-700 mb-2">Type d'Huile</label>
                        <input type="text" id="type_huile" name="type_huile" 
                               value="{{ $vidange->type_huile }}" 
                               placeholder="Ex: 5W-30, 10W-40" class="form-input w-full">
                        @error('type_huile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="filtre_huile" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Huile</label>
                        <input type="text" id="filtre_huile" name="filtre_huile" 
                               value="{{ $vidange->filtre_huile }}" 
                               placeholder="Ex: Référence filtre" class="form-input w-full">
                        @error('filtre_huile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="filtre_air" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Air</label>
                        <input type="text" id="filtre_air" name="filtre_air" 
                               value="{{ $vidange->filtre_air }}" 
                               placeholder="Ex: Référence filtre" class="form-input w-full">
                        @error('filtre_air')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="filtre_carburant" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Carburant</label>
                        <input type="text" id="filtre_carburant" name="filtre_carburant" 
                               value="{{ $vidange->filtre_carburant }}" 
                               placeholder="Ex: Référence filtre" class="form-input w-full">
                        @error('filtre_carburant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="cout_estime" class="block text-sm font-medium text-gray-700 mb-2">Coût Estimé (€)</label>
                        <input type="number" id="cout_estime" name="cout_estime" 
                               value="{{ $vidange->cout_estime }}" 
                               step="0.01" placeholder="Ex: 45.50" class="form-input w-full">
                        @error('cout_estime')
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
                              placeholder="Ajoutez des notes ou observations sur cette vidange..." 
                              class="form-textarea w-full">{{ $vidange->notes }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('vidanges.show', $vidange) }}" 
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
// Auto-calculate next oil change mileage
document.getElementById('kilometrage_actuel').addEventListener('input', function() {
    const currentKm = parseInt(this.value) || 0;
    const nextKm = currentKm + 10000; // Default 10,000 km interval
    document.getElementById('kilometrage_prochaine').value = nextKm;
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
