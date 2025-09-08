@extends('layouts.app')

@section('title', 'Modifier la Vidange')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier la Vidange</h1>
                <p class="text-gray-600">Modifier les informations de la vidange</p>
            </div>
            <a href="{{ route('vidanges.show', $vidange) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('vidanges.update', $vidange) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Selection -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                        <select id="vehicule_id" name="vehicule_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
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

                    <!-- Planning Information -->
                    <div>
                        <label for="date_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date Planifiée *</label>
                        <input type="date" id="date_prevue" name="date_prevue" 
                               value="{{ $vidange->date_prevue ? $vidange->date_prevue->format('Y-m-d') : '' }}" 
                               required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_prevue') border-red-500 @enderror">
                        @error('date_prevue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                            <option value="planifiee" {{ $vidange->statut === 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ $vidange->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ $vidange->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulee" {{ $vidange->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mileage Information -->
                    <div>
                        <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage Actuel *</label>
                        <input type="number" id="kilometrage_actuel" name="kilometrage_actuel" 
                               value="{{ $vidange->kilometrage_actuel }}" 
                               placeholder="Ex: 50000" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kilometrage_actuel') border-red-500 @enderror">
                        @error('kilometrage_actuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometrage_prochaine" class="block text-sm font-medium text-gray-700 mb-2">Prochaine Vidange (km)</label>
                        <input type="number" id="kilometrage_prochaine" name="kilometrage_prochaine" 
                               value="{{ $vidange->kilometrage_prochaine }}" 
                               placeholder="Ex: 60000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kilometrage_prochaine') border-red-500 @enderror">
                        @error('kilometrage_prochaine')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Oil and Filter Information -->
                    <div>
                        <label for="type_huile" class="block text-sm font-medium text-gray-700 mb-2">Type d'Huile</label>
                        <input type="text" id="type_huile" name="type_huile" 
                               value="{{ $vidange->type_huile }}" 
                               placeholder="Ex: 5W-30, 10W-40" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_huile') border-red-500 @enderror">
                        @error('type_huile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="filtre_huile" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Huile</label>
                        <input type="text" id="filtre_huile" name="filtre_huile" 
                               value="{{ $vidange->filtre_huile }}" 
                               placeholder="Ex: Référence filtre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filtre_huile') border-red-500 @enderror">
                        @error('filtre_huile')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="filtre_air" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Air</label>
                        <input type="text" id="filtre_air" name="filtre_air" 
                               value="{{ $vidange->filtre_air }}" 
                               placeholder="Ex: Référence filtre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filtre_air') border-red-500 @enderror">
                        @error('filtre_air')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="filtre_carburant" class="block text-sm font-medium text-gray-700 mb-2">Filtre à Carburant</label>
                        <input type="text" id="filtre_carburant" name="filtre_carburant" 
                               value="{{ $vidange->filtre_carburant }}" 
                               placeholder="Ex: Référence filtre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filtre_carburant') border-red-500 @enderror">
                        @error('filtre_carburant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cout_estime" class="block text-sm font-medium text-gray-700 mb-2">Coût Estimé (€)</label>
                        <input type="number" id="cout_estime" name="cout_estime" 
                               value="{{ $vidange->cout_estime }}" 
                               step="0.01" placeholder="Ex: 45.50" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cout_estime') border-red-500 @enderror">
                        @error('cout_estime')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" 
                              placeholder="Ajoutez des notes ou observations sur cette vidange..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ $vidange->notes }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('vidanges.show', $vidange) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
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
