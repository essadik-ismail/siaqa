@extends('layouts.app')

@section('title', 'Créer un Nouveau Forfait')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('packages.index') }}" 
                   class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Créer un Nouveau Forfait</h1>
                    <p class="mt-2 text-gray-600 text-lg">Créez un nouveau forfait d'auto-école</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('packages.store') }}" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="material-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations du Forfait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du Forfait *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="ex: Cours de Conduite de Base"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="license_category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie de Permis *</label>
                        <select id="license_category" name="license_category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('license_category') border-red-500 @enderror">
                            <option value="">Sélectionner une Catégorie</option>
                            <option value="A" {{ old('license_category') == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                            <option value="B" {{ old('license_category') == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                            <option value="C" {{ old('license_category') == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                            <option value="D" {{ old('license_category') == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
                        </select>
                        @error('license_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Prix (DH) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                               placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-2">Validité (Jours)</label>
                        <input type="number" id="validity_days" name="validity_days" value="{{ old('validity_days', 365) }}" min="1"
                               placeholder="365"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('validity_days') border-red-500 @enderror">
                        @error('validity_days')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="Décrivez ce que ce forfait comprend"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Package Contents -->
            <div class="material-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Contenu du Forfait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="theory_hours" class="block text-sm font-medium text-gray-700 mb-2">Heures Théoriques</label>
                        <input type="number" id="theory_hours" name="theory_hours" value="{{ old('theory_hours', 0) }}" min="0"
                               placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('theory_hours') border-red-500 @enderror">
                        @error('theory_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-2">Heures Pratiques</label>
                        <input type="number" id="practical_hours" name="practical_hours" value="{{ old('practical_hours', 0) }}" min="0"
                               placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('practical_hours') border-red-500 @enderror">
                        @error('practical_hours')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="exams_included" class="block text-sm font-medium text-gray-700 mb-2">Examens Inclus</label>
                        <input type="number" id="exams_included" name="exams_included" value="{{ old('exams_included', 0) }}" min="0"
                               placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('exams_included') border-red-500 @enderror">
                        @error('exams_included')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Package Features -->
            <div class="material-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Caractéristiques du Forfait</h3>
                <div>
                    <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Caractéristiques (une par ligne)</label>
                    <textarea id="features" name="features" rows="4"
                              placeholder="Entrez les caractéristiques, une par ligne&#10;ex:&#10;Examen de rattrapage gratuit&#10;Matériel d'étude inclus&#10;Planification flexible"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('features') border-red-500 @enderror">{{ old('features') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Entrez chaque caractéristique sur une nouvelle ligne</p>
                    @error('features')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Package Settings -->
            <div class="material-card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Paramètres du Forfait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Forfait Actif
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_popular" class="ml-2 block text-sm text-gray-900">
                            Forfait Populaire
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('packages.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" 
                        class="material-button">
                    <i class="fas fa-save mr-2"></i>
                    Créer le Forfait
                </button>
            </div>
        </form>
    </div>
</div>
@endsection