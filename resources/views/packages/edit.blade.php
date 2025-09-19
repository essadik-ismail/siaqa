@extends('layouts.app')

@section('title', 'Modifier le Forfait')

@section('content')
<div class="min-h-screen">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <h1 class="text-4xl font-bold gradient-text mb-4">Modifier le Forfait</h1>
            <p class="text-gray-600 text-lg">Modifiez les informations du forfait</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('packages.update', $package) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations du Forfait</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Package Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du Forfait *
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $package->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="ex: Cours de Conduite de Base">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Category -->
                        <div>
                            <label for="license_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie de Permis *
                            </label>
                            <select name="license_category" id="license_category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner la catégorie</option>
                                <option value="A" {{ old('license_category', $package->license_category) == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                                <option value="B" {{ old('license_category', $package->license_category) == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                                <option value="C" {{ old('license_category', $package->license_category) == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                                <option value="D" {{ old('license_category', $package->license_category) == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
                            </select>
                            @error('license_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Prix (DH) *
                            </label>
                            <input type="number" name="price" id="price" required min="0" step="0.01"
                                value="{{ old('price', $package->price) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validity -->
                        <div>
                            <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-2">
                                Validité (Jours)
                            </label>
                            <input type="number" name="validity_days" id="validity_days" min="0"
                                value="{{ old('validity_days', $package->validity_days) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('validity_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Décrivez ce que ce forfait comprend">{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Package Contents -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Contenu du Forfait</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Theory Hours -->
                        <div>
                            <label for="theory_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Heures Théoriques
                            </label>
                            <input type="number" name="theory_hours" id="theory_hours" min="0"
                                value="{{ old('theory_hours', $package->theory_hours) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('theory_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Practical Hours -->
                        <div>
                            <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Heures Pratiques
                            </label>
                            <input type="number" name="practical_hours" id="practical_hours" min="0"
                                value="{{ old('practical_hours', $package->practical_hours) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('practical_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Exams Included -->
                        <div>
                            <label for="exams_included" class="block text-sm font-medium text-gray-700 mb-2">
                                Examens Inclus
                            </label>
                            <input type="number" name="exams_included" id="exams_included" min="0"
                                value="{{ old('exams_included', $package->exams_included) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('exams_included')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Students -->
                        <div>
                            <label for="max_students" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Maximum d'Étudiants
                            </label>
                            <input type="number" name="max_students" id="max_students" min="0"
                                value="{{ old('max_students', $package->max_students) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('max_students')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Package Features -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Caractéristiques du Forfait</h3>
                    
                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-2">
                            Caractéristiques (une par ligne)
                        </label>
                        <textarea name="features" id="features" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Entrez les caractéristiques, une par ligne...">{{ old('features', $package->features) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Entrez chaque caractéristique sur une nouvelle ligne</p>
                        @error('features')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Package Settings -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Paramètres du Forfait</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Active Package -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                    {{ old('is_active', $package->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Forfait Actif</span>
                            </label>
                        </div>

                        <!-- Popular Package -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_popular" value="1" 
                                    {{ old('is_popular', $package->is_popular) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Forfait Populaire</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('packages.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="material-button px-6 py-3">
                        Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection