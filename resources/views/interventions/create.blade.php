@extends('layouts.app')

@section('title', 'Nouvelle Intervention')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Nouvelle Intervention</h2>
            <p class="text-gray-600 text-lg">Planifier une nouvelle intervention de maintenance pour un véhicule</p>
        </div>
        <a href="{{ route('interventions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <!-- Intervention Form -->
    <div class="content-card p-8">
        <form method="POST" action="{{ route('interventions.store') }}" class="space-y-6">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-3">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules ?? [] as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ request('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->name }} - {{ $vehicule->immatriculation }} ({{ $vehicule->marque->marque }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicule_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_intervention" class="block text-sm font-medium text-gray-700 mb-3">Type d'intervention *</label>
                    <select name="type_intervention" id="type_intervention" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le type</option>
                        <option value="maintenance_preventive" {{ old('type_intervention') == 'maintenance_preventive' ? 'selected' : '' }}>Maintenance préventive</option>
                        <option value="maintenance_corrective" {{ old('type_intervention') == 'maintenance_corrective' ? 'selected' : '' }}>Maintenance corrective</option>
                        <option value="reparation" {{ old('type_intervention') == 'reparation' ? 'selected' : '' }}>Réparation</option>
                        <option value="diagnostic" {{ old('type_intervention') == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                        <option value="remplacement_piece" {{ old('type_intervention') == 'remplacement_piece' ? 'selected' : '' }}>Remplacement de pièce</option>
                        <option value="revision" {{ old('type_intervention') == 'revision' ? 'selected' : '' }}>Révision</option>
                        <option value="autre" {{ old('type_intervention') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_intervention')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-3">Date de début *</label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', now()->format('Y-m-d')) }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date_debut')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-3">Date de fin prévue</label>
                    <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}"
                           class="form-input w-full px-4 py-3">
                    @error('date_fin')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status and Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-3">Statut *</label>
                    <select name="statut" id="statut" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le statut</option>
                        <option value="planifiée" {{ old('statut') == 'planifiée' ? 'selected' : '' }}>Planifiée</option>
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminée" {{ old('statut') == 'terminée' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulée" {{ old('statut') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                    @error('statut')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priorite" class="block text-sm font-medium text-gray-700 mb-3">Priorité</label>
                    <select name="priorite" id="priorite" class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner la priorité</option>
                        <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                        <option value="normale" {{ old('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="elevee" {{ old('priorite') == 'elevee' ? 'selected' : '' }}>Élevée</option>
                        <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                    @error('priorite')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Technician and Cost -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="technicien" class="block text-sm font-medium text-gray-700 mb-3">Technicien</label>
                    <input type="text" name="technicien" id="technicien" value="{{ old('technicien') }}"
                           class="form-input w-full px-4 py-3" placeholder="Nom du technicien">
                    @error('technicien')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cout" class="block text-sm font-medium text-gray-700 mb-3">Coût estimé (€)</label>
                    <input type="number" name="cout" id="cout" value="{{ old('cout') }}" step="0.01" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0.00">
                    @error('cout')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-3">Description *</label>
                <textarea name="description" id="description" rows="4" required
                          class="form-input w-full px-4 py-3" placeholder="Description détaillée de l'intervention...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kilometrage_intervention" class="block text-sm font-medium text-gray-700 mb-3">Kilométrage lors de l'intervention</label>
                    <input type="number" name="kilometrage_intervention" id="kilometrage_intervention" value="{{ old('kilometrage_intervention') }}" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0">
                    @error('kilometrage_intervention')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duree_estimee" class="block text-sm font-medium text-gray-700 mb-3">Durée estimée (heures)</label>
                    <input type="number" name="duree_estimee" id="duree_estimee" value="{{ old('duree_estimee') }}" step="0.5" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0.0">
                    @error('duree_estimee')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Parts and Materials -->
            <div>
                <label for="pieces_utilisees" class="block text-sm font-medium text-gray-700 mb-3">Pièces utilisées</label>
                <textarea name="pieces_utilisees" id="pieces_utilisees" rows="3"
                          class="form-input w-full px-4 py-3" placeholder="Liste des pièces, références...">{{ old('pieces_utilisees') }}</textarea>
                @error('pieces_utilisees')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-3">Notes</label>
                <textarea name="notes" id="notes" rows="4" 
                          class="form-input w-full px-4 py-3" placeholder="Observations, remarques, points d'attention...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1 px-8 py-4 text-lg">
                    <i class="fas fa-save mr-3"></i>Planifier l'intervention
                </button>
                <a href="{{ route('interventions.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-xl font-medium text-center transition-all duration-200 hover:scale-105">
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

// Auto-calculate end date (1 week from start date by default)
document.getElementById('date_debut').addEventListener('change', function() {
    const startDate = new Date(this.value);
    if (startDate && !document.getElementById('date_fin').value) {
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 7);
        document.getElementById('date_fin').value = endDate.toISOString().split('T')[0];
    }
});
</script>
@endsection
