@extends('layouts.app')

@section('title', 'Modifier l\'Assurance')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Modifier l'Assurance</h2>
                    <p class="text-gray-600">{{ $assurance->numero_assurance }} - {{ $assurance->numero_police }}</p>
                </div>
                <a href="{{ route('assurances.show', $assurance) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('assurances.update', $assurance) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Vehicle Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicule_id" class="block text-sm font-medium text-gray-700 mb-2">Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('vehicule_id') border-red-500 @enderror">
                        <option value="">Sélectionner un véhicule</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ $assurance->vehicule_id == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->marque ? $vehicule->marque->marque : 'Marque inconnue' }} {{ $vehicule->modele }} - {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicule_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_assurance" class="block text-sm font-medium text-gray-700 mb-2">Numéro d'assurance *</label>
                    <input type="text" name="numero_assurance" id="numero_assurance" value="{{ old('numero_assurance', $assurance->numero_assurance) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_assurance') border-red-500 @enderror" 
                           placeholder="Numéro d'assurance">
                    @error('numero_assurance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Policy Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="numero_police" class="block text-sm font-medium text-gray-700 mb-2">Numéro de police *</label>
                    <input type="text" name="numero_police" id="numero_police" value="{{ old('numero_police', $assurance->numero_police) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_police') border-red-500 @enderror" 
                           placeholder="Numéro de la police">
                    @error('numero_police')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">Prix (€) *</label>
                    <input type="number" name="prix" id="prix" value="{{ old('prix', $assurance->prix) }}" step="0.01" min="0" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prix') border-red-500 @enderror" 
                           placeholder="0.00">
                    @error('prix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $assurance->date ? $assurance->date->format('Y-m-d') : '') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_prochaine" class="block text-sm font-medium text-gray-700 mb-2">Date prochaine *</label>
                    <input type="date" name="date_prochaine" id="date_prochaine" value="{{ old('date_prochaine', $assurance->date_prochaine ? $assurance->date_prochaine->format('Y-m-d') : '') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_prochaine') border-red-500 @enderror">
                    @error('date_prochaine')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_reglement" class="block text-sm font-medium text-gray-700 mb-2">Date de règlement *</label>
                    <input type="date" name="date_reglement" id="date_reglement" value="{{ old('date_reglement', $assurance->date_reglement ? $assurance->date_reglement->format('Y-m-d') : '') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_reglement') border-red-500 @enderror">
                    @error('date_reglement')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                    <input type="text" name="periode" id="periode" value="{{ old('periode', $assurance->periode) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('periode') border-red-500 @enderror" 
                           placeholder="Période d'assurance">
                    @error('periode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fichiers" class="block text-sm font-medium text-gray-700 mb-2">Fichiers</label>
                    <input type="file" name="fichiers[]" id="fichiers" multiple
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fichiers') border-red-500 @enderror" 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('fichiers')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                          placeholder="Description de l'assurance...">{{ old('description', $assurance->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
