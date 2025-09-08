@extends('layouts.app')

@section('title', 'Modifier l\'Intervention')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier l'Intervention</h1>
                <p class="text-gray-600">Modifier les informations de l'intervention de maintenance</p>
            </div>
            <a href="{{ route('interventions.show', $intervention) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('interventions.update', $intervention) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Selection -->
                    <div>
                        <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                        <select id="vehicule_id" name="vehicule_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                            <option value="">Sélectionner un véhicule</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}" {{ $intervention->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                    {{ $vehicule->marque ? $vehicule->marque->marque : 'Marque inconnue' }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Intervention Information -->
                    <div>
                        <label for="type_intervention" class="block text-sm font-medium text-gray-700 mb-2">Type d'Intervention *</label>
                        <select id="type_intervention" name="type_intervention" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_intervention') border-red-500 @enderror">
                            <option value="">Sélectionner le type</option>
                            <option value="reparation" {{ $intervention->type_intervention === 'reparation' ? 'selected' : '' }}>Réparation</option>
                            <option value="maintenance" {{ $intervention->type_intervention === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="diagnostic" {{ $intervention->type_intervention === 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                            <option value="remplacement" {{ $intervention->type_intervention === 'remplacement' ? 'selected' : '' }}>Remplacement de Pièces</option>
                            <option value="revision" {{ $intervention->type_intervention === 'revision' ? 'selected' : '' }}>Révision</option>
                            <option value="autre" {{ $intervention->type_intervention === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type_intervention')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select id="statut" name="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                            <option value="planifiee" {{ $intervention->statut === 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ $intervention->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ $intervention->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulee" {{ $intervention->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                        <select id="priorite" name="priorite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priorite') border-red-500 @enderror">
                            <option value="">Sélectionner la priorité</option>
                            <option value="basse" {{ $intervention->priorite === 'basse' ? 'selected' : '' }}>Basse</option>
                            <option value="normale" {{ $intervention->priorite === 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="haute" {{ $intervention->priorite === 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="urgente" {{ $intervention->priorite === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priorite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="technicien" class="block text-sm font-medium text-gray-700 mb-2">Technicien</label>
                        <input type="text" id="technicien" name="technicien" 
                               value="{{ $intervention->technicien }}" 
                               placeholder="Ex: Nom du technicien" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('technicien') border-red-500 @enderror">
                        @error('technicien')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planning Information -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de Début *</label>
                        <input type="date" id="date_debut" name="date_debut" 
                               value="{{ $intervention->date_debut ? $intervention->date_debut->format('Y-m-d') : '' }}" 
                               required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_debut') border-red-500 @enderror">
                        @error('date_debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Date de Fin</label>
                        <input type="date" id="date_fin" name="date_fin" 
                               value="{{ $intervention->date_fin ? $intervention->date_fin->format('Y-m-d') : '' }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_fin') border-red-500 @enderror">
                        @error('date_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duree_estimee" class="block text-sm font-medium text-gray-700 mb-2">Durée Estimée</label>
                        <input type="text" id="duree_estimee" name="duree_estimee" 
                               value="{{ $intervention->duree_estimee }}" 
                               placeholder="Ex: 2 jours, 4 heures" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('duree_estimee') border-red-500 @enderror">
                        @error('duree_estimee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometrage" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage</label>
                        <input type="number" id="kilometrage" name="kilometrage" 
                               value="{{ $intervention->kilometrage }}" 
                               placeholder="Ex: 50000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kilometrage') border-red-500 @enderror">
                        @error('kilometrage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cost and Details -->
                    <div>
                        <label for="cout" class="block text-sm font-medium text-gray-700 mb-2">Coût (€)</label>
                        <input type="number" id="cout" name="cout" 
                               value="{{ $intervention->cout }}" 
                               step="0.01" placeholder="Ex: 150.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cout') border-red-500 @enderror">
                        @error('cout')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pieces_utilisees" class="block text-sm font-medium text-gray-700 mb-2">Pièces Utilisées</label>
                        <input type="text" id="pieces_utilisees" name="pieces_utilisees" 
                               value="{{ $intervention->pieces_utilisees }}" 
                               placeholder="Ex: Filtres, plaquettes, etc." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pieces_utilisees') border-red-500 @enderror">
                        @error('pieces_utilisees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description de l'Intervention</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Décrivez en détail l'intervention à effectuer..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ $intervention->description }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes et Observations</label>
                    <textarea id="notes" name="notes" rows="4" 
                              placeholder="Ajoutez des notes ou observations sur cette intervention..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ $intervention->notes }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('interventions.show', $intervention) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate end date based on start date and estimated duration
document.getElementById('date_debut').addEventListener('change', function() {
    const startDate = new Date(this.value);
    if (startDate) {
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 1); // Default: 1 day later
        document.getElementById('date_fin').value = endDate.toISOString().split('T')[0];
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
