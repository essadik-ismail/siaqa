@extends('layouts.app')

@section('title', 'Nouveau Véhicule')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nouveau Véhicule</h1>
                <p class="text-gray-600">Ajouter un nouveau véhicule à votre flotte</p>
            </div>
            <a href="{{ route('vehicules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('vehicules.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <label for="marque" class="block text-sm font-medium text-gray-700 mb-2">Marque *</label>
                        <input type="text" name="marque" id="marque" value="{{ old('marque') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: Toyota, BMW, Mercedes...">
                        @error('marque')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du véhicule *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="immatriculation" class="block text-sm font-medium text-gray-700 mb-2">Immatriculation *</label>
                        <input type="text" name="immatriculation" id="immatriculation" value="{{ old('immatriculation') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('immatriculation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="couleur" class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                        <input type="text" name="couleur" id="couleur" value="{{ old('couleur') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('couleur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_carburant" class="block text-sm font-medium text-gray-700 mb-2">Type de carburant</label>
                        <select name="type_carburant" id="type_carburant" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="essence" {{ old('type_carburant') == 'essence' ? 'selected' : '' }}>Essence</option>
                            <option value="diesel" {{ old('type_carburant') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="electrique" {{ old('type_carburant') == 'electrique' ? 'selected' : '' }}>Électrique</option>
                            <option value="hybride" {{ old('type_carburant') == 'hybride' ? 'selected' : '' }}>Hybride</option>
                            <option value="gpl" {{ old('type_carburant') == 'gpl' ? 'selected' : '' }}>GPL</option>
                        </select>
                        @error('type_carburant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nbr_place" class="block text-sm font-medium text-gray-700 mb-2">Nombre de places</label>
                        <input type="number" name="nbr_place" id="nbr_place" value="{{ old('nbr_place', 5) }}" min="1" max="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nbr_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nombre_cylindre" class="block text-sm font-medium text-gray-700 mb-2">Nombre de cylindres</label>
                        <input type="number" name="nombre_cylindre" id="nombre_cylindre" value="{{ old('nombre_cylindre', 4) }}" min="0" max="16"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nombre_cylindre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prix_location_jour" class="block text-sm font-medium text-gray-700 mb-2">Prix de location journalier *</label>
                        <input type="number" name="prix_location_jour" id="prix_location_jour" value="{{ old('prix_location_jour') }}" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix_location_jour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prix_achat" class="block text-sm font-medium text-gray-700 mb-2">Prix d'achat</label>
                        <input type="number" name="prix_achat" id="prix_achat" value="{{ old('prix_achat') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix_achat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage actuel</label>
                        <input type="number" name="kilometrage_actuel" id="kilometrage_actuel" value="{{ old('kilometrage_actuel', 0) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('kilometrage_actuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categorie_vehicule" class="block text-sm font-medium text-gray-700 mb-2">Catégorie du véhicule</label>
                        <select name="categorie_vehicule" id="categorie_vehicule" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="A" {{ old('categorie_vehicule') == 'A' ? 'selected' : '' }}>A - Moto</option>
                            <option value="B" {{ old('categorie_vehicule') == 'B' ? 'selected' : '' }}>B - Voiture</option>
                            <option value="C" {{ old('categorie_vehicule') == 'C' ? 'selected' : '' }}>C - Camion</option>
                            <option value="D" {{ old('categorie_vehicule') == 'D' ? 'selected' : '' }}>D - Bus</option>
                            <option value="E" {{ old('categorie_vehicule') == 'E' ? 'selected' : '' }}>E - Remorque</option>
                        </select>
                        @error('categorie_vehicule')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>En location</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>Hors service</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Véhicule actif</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Images Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Images du véhicule</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image principale</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Images supplémentaires</label>
                            <input type="file" name="images[]" id="images" accept="image/*" multiple
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Créer le véhicule
                    </button>
                    <a href="{{ route('vehicules.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 