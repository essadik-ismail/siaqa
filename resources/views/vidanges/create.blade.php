@extends('layouts.app')

@section('title', 'Nouvelle Vidange')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Nouvelle Vidange</h2>
                    <p class="text-gray-600">Planifier une nouvelle vidange pour un véhicule</p>
                </div>
                <a href="{{ route('vidanges.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('vidanges.store') }}" class="space-y-6">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules as $vehicule)
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
                    <label for="date_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date prévue *</label>
                    <input type="date" name="date_prevue" id="date_prevue" value="{{ old('date_prevue') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_prevue') border-red-500 @enderror">
                    @error('date_prevue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kilometrage Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700 mb-3">Kilométrage actuel *</label>
                    <input type="number" name="kilometrage_actuel" id="kilometrage_actuel" value="{{ old('kilometrage_actuel') }}" required min="0"
                           class="form-input w-full px-4 py-3" placeholder="0">
                    @error('kilometrage_actuel')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kilometrage_prochaine" class="block text-sm font-medium text-gray-700 mb-3">Kilométrage prochaine vidange *</label>
                    <input type="number" name="kilometrage_prochaine" id="kilometrage_prochaine" value="{{ old('kilometrage_prochaine') }}" required min="0"
                           class="form-input w-full px-4 py-3" placeholder="0">
                    @error('kilometrage_prochaine')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Oil Type and Service Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type_huile" class="block text-sm font-medium text-gray-700 mb-3">Type d'huile</label>
                    <select name="type_huile" id="type_huile" class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le type</option>
                        <option value="5w30" {{ old('type_huile') == '5w30' ? 'selected' : '' }}>5W-30</option>
                        <option value="5w40" {{ old('type_huile') == '5w40' ? 'selected' : '' }}>5W-40</option>
                        <option value="10w40" {{ old('type_huile') == '10w40' ? 'selected' : '' }}>10W-40</option>
                        <option value="15w40" {{ old('type_huile') == '15w40' ? 'selected' : '' }}>15W-40</option>
                        <option value="20w50" {{ old('type_huile') == '20w50' ? 'selected' : '' }}>20W-50</option>
                        <option value="autre" {{ old('type_huile') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_huile')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="filtre_huile" class="block text-sm font-medium text-gray-700 mb-3">Filtre à huile</label>
                    <select name="filtre_huile" id="filtre_huile" class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner</option>
                        <option value="standard" {{ old('filtre_huile') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="premium" {{ old('filtre_huile') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="haute_performance" {{ old('filtre_huile') == 'haute_performance' ? 'selected' : '' }}>Haute performance</option>
                    </select>
                    @error('filtre_huile')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Services -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="filtre_air" class="block text-sm font-medium text-gray-700 mb-3">Filtre à air</label>
                    <select name="filtre_air" id="filtre_air" class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner</option>
                        <option value="standard" {{ old('filtre_air') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="premium" {{ old('filtre_air') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="sport" {{ old('filtre_air') == 'sport' ? 'selected' : '' }}>Sport</option>
                    </select>
                    @error('filtre_air')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="filtre_carburant" class="block text-sm font-medium text-gray-700 mb-3">Filtre à carburant</label>
                    <select name="filtre_carburant" id="filtre_carburant" class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner</option>
                        <option value="standard" {{ old('filtre_carburant') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="premium" {{ old('filtre_carburant') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="haute_performance" {{ old('filtre_carburant') == 'haute_performance' ? 'selected' : '' }}>Haute performance</option>
                    </select>
                    @error('filtre_carburant')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cost and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cout_estime" class="block text-sm font-medium text-gray-700 mb-3">Coût estimé (€)</label>
                    <input type="number" name="cout_estime" id="cout_estime" value="{{ old('cout_estime') }}" step="0.01" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0.00">
                    @error('cout_estime')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-3">Statut *</label>
                    <select name="statut" id="statut" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le statut</option>
                        <option value="planifiee" {{ old('statut') == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ old('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ old('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    @error('statut')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-3">Notes</label>
                <textarea name="notes" id="notes" rows="4" 
                          class="form-input w-full px-4 py-3" placeholder="Informations supplémentaires, observations...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1 px-8 py-4 text-lg">
                    <i class="fas fa-save mr-3"></i>Planifier la vidange
                </button>
                <a href="{{ route('vidanges.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-xl font-medium text-center transition-all duration-200 hover:scale-105">
                    Annuler
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

// Auto-calculate next oil change mileage
document.getElementById('kilometrage_actuel').addEventListener('input', function() {
    const currentKm = parseInt(this.value) || 0;
    const nextKm = currentKm + 15000; // Default 15,000 km interval
    const nextKmField = document.getElementById('kilometrage_prochaine');
    
    // Only auto-fill if the next mileage field is empty or has the same value as current
    if (nextKmField && (nextKmField.value === '' || nextKmField.value === '0' || nextKmField.value === this.value)) {
        nextKmField.value = nextKm;
    }
});
</script>
@endsection
