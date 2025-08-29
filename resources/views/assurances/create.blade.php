@extends('layouts.app')

@section('title', 'Nouvelle Assurance')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Nouvelle Assurance</h2>
            <p class="text-gray-600 text-lg">Ajouter une nouvelle assurance pour un véhicule</p>
        </div>
        <a href="{{ route('assurances.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <!-- Insurance Form -->
    <div class="content-card p-8">
        <form method="POST" action="{{ route('assurances.store') }}" class="space-y-6">
            @csrf
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-3">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules as $vehicule)
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
                    <label for="compagnie" class="block text-sm font-medium text-gray-700 mb-3">Compagnie d'assurance *</label>
                    <input type="text" name="compagnie" id="compagnie" value="{{ old('compagnie') }}" required
                           class="form-input w-full px-4 py-3" placeholder="Nom de la compagnie">
                    @error('compagnie')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Policy Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="numero_police" class="block text-sm font-medium text-gray-700 mb-3">Numéro de police *</label>
                    <input type="text" name="numero_police" id="numero_police" value="{{ old('numero_police') }}" required
                           class="form-input w-full px-4 py-3" placeholder="Numéro de la police">
                    @error('numero_police')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_assurance" class="block text-sm font-medium text-gray-700 mb-3">Type d'assurance *</label>
                    <select name="type_assurance" id="type_assurance" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le type</option>
                        <option value="responsabilite_civile" {{ old('type_assurance') == 'responsabilite_civile' ? 'selected' : '' }}>Responsabilité civile</option>
                        <option value="tous_risques" {{ old('type_assurance') == 'tous_risques' ? 'selected' : '' }}>Tous risques</option>
                        <option value="vol_incendie" {{ old('type_assurance') == 'vol_incendie' ? 'selected' : '' }}>Vol et incendie</option>
                        <option value="assistance" {{ old('type_assurance') == 'assistance' ? 'selected' : '' }}>Assistance</option>
                    </select>
                    @error('type_assurance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates and Cost -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-3">Date de début *</label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', now()->format('Y-m-d')) }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date_debut')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_expiration" class="block text-sm font-medium text-gray-700 mb-3">Date d'expiration *</label>
                    <input type="date" name="date_expiration" id="date_expiration" value="{{ old('date_expiration') }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date_expiration')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cout_annuel" class="block text-sm font-medium text-gray-700 mb-3">Coût annuel (€)</label>
                    <input type="number" name="cout_annuel" id="cout_annuel" value="{{ old('cout_annuel') }}" step="0.01" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0.00">
                    @error('cout_annuel')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="franchise" class="block text-sm font-medium text-gray-700 mb-3">Franchise (€)</label>
                    <input type="number" name="franchise" id="franchise" value="{{ old('franchise') }}" step="0.01" min="0"
                           class="form-input w-full px-4 py-3" placeholder="0.00">
                    @error('franchise')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-3">Statut *</label>
                    <select name="statut" id="statut" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner le statut</option>
                        <option value="active" {{ old('statut') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expiree" {{ old('statut') == 'expiree' ? 'selected' : '' }}>Expirée</option>
                        <option value="resiliee" {{ old('statut') == 'resiliee' ? 'selected' : '' }}>Résiliée</option>
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
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
                          class="form-input w-full px-4 py-3" placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1 px-8 py-4 text-lg">
                    <i class="fas fa-save mr-3"></i>Créer l'assurance
                </button>
                <a href="{{ route('assurances.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-xl font-medium text-center transition-all duration-200 hover:scale-105">
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
</script>
@endsection
