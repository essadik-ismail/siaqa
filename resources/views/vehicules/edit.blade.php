@extends('layouts.app')

@section('title', 'Modifier Véhicule')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier Véhicule</h1>
                <p class="text-gray-600">Modifier les informations du véhicule {{ $vehicule->name }}</p>
            </div>
            <a href="{{ route('vehicules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux Véhicules
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('vehicules.update', $vehicule) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <label for="marque" class="block text-sm font-medium text-gray-700 mb-2">Marque *</label>
                        <input type="text" name="marque" id="marque" value="{{ old('marque', $vehicule->marque->marque ?? '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ex: Toyota, BMW, Mercedes...">
                        @error('marque')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du véhicule *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vehicule->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="immatriculation" class="block text-sm font-medium text-gray-700 mb-2">Immatriculation *</label>
                        <input type="text" name="immatriculation" id="immatriculation" value="{{ old('immatriculation', $vehicule->immatriculation) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('immatriculation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="couleur" class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                        <input type="text" name="couleur" id="couleur" value="{{ old('couleur', $vehicule->couleur) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('couleur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_carburant" class="block text-sm font-medium text-gray-700 mb-2">Type de carburant</label>
                        <select name="type_carburant" id="type_carburant" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="essence" {{ old('type_carburant', $vehicule->type_carburant) == 'essence' ? 'selected' : '' }}>Essence</option>
                            <option value="diesel" {{ old('type_carburant', $vehicule->type_carburant) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="electrique" {{ old('type_carburant', $vehicule->type_carburant) == 'electrique' ? 'selected' : '' }}>Électrique</option>
                            <option value="hybride" {{ old('type_carburant', $vehicule->type_carburant) == 'hybride' ? 'selected' : '' }}>Hybride</option>
                            <option value="gpl" {{ old('type_carburant', $vehicule->type_carburant) == 'gpl' ? 'selected' : '' }}>GPL</option>
                        </select>
                        @error('type_carburant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nbr_place" class="block text-sm font-medium text-gray-700 mb-2">Nombre de places</label>
                        <input type="number" name="nbr_place" id="nbr_place" value="{{ old('nbr_place', $vehicule->nbr_place) }}" min="1" max="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nbr_place')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nombre_cylindre" class="block text-sm font-medium text-gray-700 mb-2">Nombre de cylindres</label>
                        <input type="number" name="nombre_cylindre" id="nombre_cylindre" value="{{ old('nombre_cylindre', $vehicule->nombre_cylindre) }}" min="0" max="16"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nombre_cylindre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prix_location_jour" class="block text-sm font-medium text-gray-700 mb-2">Prix de location journalier *</label>
                        <input type="number" name="prix_location_jour" id="prix_location_jour" value="{{ old('prix_location_jour', $vehicule->prix_location_jour) }}" step="0.01" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix_location_jour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prix_achat" class="block text-sm font-medium text-gray-700 mb-2">Prix d'achat</label>
                        <input type="number" name="prix_achat" id="prix_achat" value="{{ old('prix_achat', $vehicule->prix_achat) }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('prix_achat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kilometrage_actuel" class="block text-sm font-medium text-gray-700 mb-2">Kilométrage actuel</label>
                        <input type="number" name="kilometrage_actuel" id="kilometrage_actuel" value="{{ old('kilometrage_actuel', $vehicule->kilometrage_actuel) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('kilometrage_actuel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categorie_vehicule" class="block text-sm font-medium text-gray-700 mb-2">Catégorie du véhicule</label>
                        <select name="categorie_vehicule" id="categorie_vehicule" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="A" {{ old('categorie_vehicule', $vehicule->categorie_vehicule) == 'A' ? 'selected' : '' }}>A - Moto</option>
                            <option value="B" {{ old('categorie_vehicule', $vehicule->categorie_vehicule) == 'B' ? 'selected' : '' }}>B - Voiture</option>
                            <option value="C" {{ old('categorie_vehicule', $vehicule->categorie_vehicule) == 'C' ? 'selected' : '' }}>C - Camion</option>
                            <option value="D" {{ old('categorie_vehicule', $vehicule->categorie_vehicule) == 'D' ? 'selected' : '' }}>D - Bus</option>
                            <option value="E" {{ old('categorie_vehicule', $vehicule->categorie_vehicule) == 'E' ? 'selected' : '' }}>E - Remorque</option>
                        </select>
                        @error('categorie_vehicule')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="agence_id" class="block text-sm font-medium text-gray-700 mb-2">Agence *</label>
                        <select name="agence_id" id="agence_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Sélectionner une agence</option>
                            @foreach($agences ?? [] as $agence)
                                <option value="{{ $agence->id }}" {{ old('agence_id', $vehicule->agence_id) == $agence->id ? 'selected' : '' }}>
                                    {{ $agence->nom_agence }}
                                </option>
                            @endforeach
                        </select>
                        @error('agence_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                        <select name="statut" id="statut" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="disponible" {{ old('statut', $vehicule->statut) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="en_location" {{ old('statut', $vehicule->statut) == 'en_location' ? 'selected' : '' }}>En location</option>
                            <option value="en_maintenance" {{ old('statut', $vehicule->statut) == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="hors_service" {{ old('statut', $vehicule->statut) == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $vehicule->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Véhicule actif</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="landing_display" class="flex items-center">
                            <input type="checkbox" name="landing_display" id="landing_display" value="1" {{ old('landing_display', $vehicule->landing_display) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Afficher sur la page d'accueil</span>
                        </label>
                        @error('landing_display')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Image Display -->
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Image actuelle</h3>
                    </div>
                    <div class="w-48 h-32 rounded-lg overflow-hidden shadow-sm relative">
                        <img src="{{ $vehicule->image_url }}" 
                             alt="{{ $vehicule->name }}" 
                             class="w-full h-full object-cover">
                        <!-- Remove icon overlay -->
                        <button type="button" onclick="showRemoveImageModal()" 
                                class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200 shadow-lg"
                                title="Supprimer l'image">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                    @if(!$vehicule->image)
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Image par défaut affichée. Ajoutez une image personnalisée ci-dessous.
                    </p>
                    @endif
                </div>

                <!-- Images Section -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier les images du véhicule</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Nouvelle image principale</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Laisser vide pour conserver l'image actuelle</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Nouvelles images supplémentaires</label>
                            <input type="file" name="images[]" id="images" accept="image/*" multiple
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Laisser vide pour conserver les images actuelles</p>
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
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $vehicule->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le véhicule
                    </button>
                    <a href="{{ route('vehicules.show', $vehicule) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="button" onclick="showDeleteModal()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Image Confirmation Modal -->
<div id="removeImageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <i class="fas fa-image text-yellow-600 text-xl"></i>
            </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Supprimer l'image</h3>
                    <div class="mt-2 px-7 py-3">
                        @if($vehicule->image)
                        <p class="text-sm text-gray-500 mb-4">
                            Êtes-vous sûr de vouloir supprimer l'image personnalisée du véhicule <strong>{{ $vehicule->name }}</strong> ?
                        </p>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Note:</strong> Une image par défaut sera affichée après la suppression.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-500 mb-4">
                            Le véhicule <strong>{{ $vehicule->name }}</strong> utilise actuellement une image par défaut.
                        </p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Note:</strong> Aucune action n'est nécessaire. L'image par défaut sera conservée.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
            <div class="flex space-x-3 px-4 py-3">
                <button onclick="hideRemoveImageModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Annuler
                </button>
                @if($vehicule->image)
                <form method="POST" action="{{ route('vehicules.remove-image', $vehicule) }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="action" value="remove_image">
                    <input type="hidden" name="image_path" value="{{ $vehicule->image }}">
                    <button type="submit" 
                            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        Supprimer l'image
                    </button>
                </form>
                @else
                <button onclick="hideRemoveImageModal()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Fermer
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Êtes-vous sûr de vouloir supprimer le véhicule <strong>{{ $vehicule->name }}</strong> ?
                    Cette action est irréversible.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Note:</strong> Le véhicule ne peut pas être supprimé s'il a des réservations ou contrats actifs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3 px-4 py-3">
                <button onclick="hideDeleteModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Annuler
                </button>
                <form method="POST" action="{{ route('vehicules.destroy', $vehicule) }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showRemoveImageModal() {
    document.getElementById('removeImageModal').classList.remove('hidden');
}

function hideRemoveImageModal() {
    document.getElementById('removeImageModal').classList.add('hidden');
}

function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('removeImageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRemoveImageModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>
@endsection
