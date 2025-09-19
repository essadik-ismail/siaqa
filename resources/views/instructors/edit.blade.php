@extends('layouts.app')

@section('title', 'Modifier l\'Instructeur')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Modifier l'Instructeur</h1>
            <p class="text-gray-600 text-lg">Modifiez les informations de l'instructeur</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('instructors.update', $instructor) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Personnelles</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom Complet *
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $instructor->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Entrez le nom complet">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" name="email" id="email" required
                                value="{{ old('email', $instructor->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="exemple@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone *
                            </label>
                            <input type="tel" name="phone" id="phone" required
                                value="{{ old('phone', $instructor->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="+212 6XX XXX XXX">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CIN -->
                        <div>
                            <label for="cin" class="block text-sm font-medium text-gray-700 mb-2">
                                CIN *
                            </label>
                            <input type="text" name="cin" id="cin" required
                                value="{{ old('cin', $instructor->cin) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="A123456">
                            @error('cin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de Naissance *
                            </label>
                            <input type="date" name="birth_date" id="birth_date" required
                                value="{{ old('birth_date', $instructor->birth_date ? $instructor->birth_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <input type="text" name="address" id="address"
                                value="{{ old('address', $instructor->address) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Adresse complète">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Professionnelles</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- License Categories -->
                        <div>
                            <label for="license_categories" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégories de Permis *
                            </label>
                            <div class="space-y-2">
                                @php
                                    $selectedCategories = old('license_categories', $instructor->license_categories ? explode(',', $instructor->license_categories) : []);
                                @endphp
                                <label class="flex items-center">
                                    <input type="checkbox" name="license_categories[]" value="A" 
                                        {{ in_array('A', $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Catégorie A (Moto)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="license_categories[]" value="B" 
                                        {{ in_array('B', $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Catégorie B (Voiture)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="license_categories[]" value="C" 
                                        {{ in_array('C', $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Catégorie C (Camion)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="license_categories[]" value="D" 
                                        {{ in_array('D', $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Catégorie D (Bus)</span>
                                </label>
                            </div>
                            @error('license_categories')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner le statut</option>
                                <option value="active" {{ old('status', $instructor->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="inactive" {{ old('status', $instructor->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspended" {{ old('status', $instructor->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                <option value="on_leave" {{ old('status', $instructor->status) == 'on_leave' ? 'selected' : '' }}>En Congé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Experience Years -->
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">
                                Années d'Expérience
                            </label>
                            <input type="number" name="experience_years" id="experience_years" min="0"
                                value="{{ old('experience_years', $instructor->experience_years) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('experience_years')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Tarif Horaire (DH)
                            </label>
                            <input type="number" name="hourly_rate" id="hourly_rate" min="0" step="0.01"
                                value="{{ old('hourly_rate', $instructor->hourly_rate) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('hourly_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Available -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" 
                                    {{ old('is_available', $instructor->is_available) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Disponible pour les leçons</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Specialties -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Spécialités</h3>
                    
                    <div>
                        <label for="specialties" class="block text-sm font-medium text-gray-700 mb-2">
                            Spécialités (une par ligne)
                        </label>
                        <textarea name="specialties" id="specialties" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Conduite défensive, Conduite économique, etc.">{{ old('specialties', $instructor->specialties) }}</textarea>
                        @error('specialties')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Notes</h3>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes Supplémentaires
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Notes sur l'instructeur...">{{ old('notes', $instructor->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('instructors.index') }}" 
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