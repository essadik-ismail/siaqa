@extends('layouts.app')

@section('title', 'Modifier l\'Assurance')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Modifier l'Assurance</h2>
            <p class="text-gray-600 text-lg">{{ $assurance->numero_assurance }} - {{ $assurance->numero_police }}</p>
        </div>
        <a href="{{ route('assurances.show', $assurance) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
            <i class="fas fa-arrow-left"></i>
            <span>Retour</span>
        </a>
    </div>

    <!-- Insurance Edit Form -->
    <div class="content-card p-8">
        <form method="POST" action="{{ route('assurances.update', $assurance) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-3">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required class="form-input w-full px-4 py-3">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ $assurance->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->name }} - {{ $vehicule->immatriculation }} ({{ $vehicule->marque->marque }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicule_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_assurance" class="block text-sm font-medium text-gray-700 mb-3">Numéro d'assurance *</label>
                    <input type="text" name="numero_assurance" id="numero_assurance" value="{{ old('numero_assurance', $assurance->numero_assurance) }}" required
                           class="form-input w-full px-4 py-3" placeholder="Numéro d'assurance">
                    @error('numero_assurance')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Policy Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="numero_police" class="block text-sm font-medium text-gray-700 mb-3">Numéro de police *</label>
                    <input type="text" name="numero_police" id="numero_police" value="{{ old('numero_police', $assurance->numero_police) }}" required
                           class="form-input w-full px-4 py-3" placeholder="Numéro de la police">
                    @error('numero_police')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 mb-3">Prix (€) *</label>
                    <input type="number" name="prix" id="prix" value="{{ old('prix', $assurance->prix) }}" step="0.01" min="0" required
                           class="form-input w-full px-4 py-3" placeholder="0.00">
                    @error('prix')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-3">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $assurance->date ? $assurance->date->format('Y-m-d') : '') }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_prochaine" class="block text-sm font-medium text-gray-700 mb-3">Date prochaine *</label>
                    <input type="date" name="date_prochaine" id="date_prochaine" value="{{ old('date_prochaine', $assurance->date_prochaine ? $assurance->date_prochaine->format('Y-m-d') : '') }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date_prochaine')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_reglement" class="block text-sm font-medium text-gray-700 mb-3">Date de règlement *</label>
                    <input type="date" name="date_reglement" id="date_reglement" value="{{ old('date_reglement', $assurance->date_reglement ? $assurance->date_reglement->format('Y-m-d') : '') }}" required
                           class="form-input w-full px-4 py-3">
                    @error('date_reglement')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700 mb-3">Période</label>
                    <input type="text" name="periode" id="periode" value="{{ old('periode', $assurance->periode) }}"
                           class="form-input w-full px-4 py-3" placeholder="Période d'assurance">
                    @error('periode')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fichiers" class="block text-sm font-medium text-gray-700 mb-3">Fichiers</label>
                    <input type="file" name="fichiers[]" id="fichiers" multiple
                           class="form-input w-full px-4 py-3" accept=".pdf,.jpg,.jpeg,.png">
                    @error('fichiers')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    @if($assurance->hasFiles())
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-2">Fichiers actuels:</p>
                            @foreach($assurance->file_urls as $url)
                                <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm block">{{ basename($url) }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-3">Description</label>
                <textarea name="description" id="description" rows="4" 
                          class="form-input w-full px-4 py-3" placeholder="Description de l'assurance...">{{ old('description', $assurance->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="submit" class="btn-primary flex-1 px-8 py-4 text-lg">
                    <i class="fas fa-save mr-3"></i>Mettre à jour l'assurance
                </button>
                <a href="{{ route('assurances.show', $assurance) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-xl font-medium text-center transition-all duration-200 hover:scale-105">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
