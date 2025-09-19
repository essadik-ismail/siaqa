@extends('layouts.app')

@section('title', 'Modifier l\'Étudiant')

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
            <h1 class="text-4xl font-bold gradient-text mb-4">Modifier l'Étudiant</h1>
            <p class="text-gray-600 text-lg">Modifiez les informations de l'étudiant</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-6">
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
                                value="{{ old('name', $student->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Entrez le nom complet">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name in Arabic -->
                        <div>
                            <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom en Arabe
                            </label>
                            <input type="text" name="name_ar" id="name_ar"
                                value="{{ old('name_ar', $student->name_ar) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="الاسم بالعربية">
                            @error('name_ar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" name="email" id="email" required
                                value="{{ old('email', $student->email) }}"
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
                                value="{{ old('phone', $student->phone) }}"
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
                                value="{{ old('cin', $student->cin) }}"
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
                                value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place -->
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                                Lieu de Naissance
                            </label>
                            <input type="text" name="birth_place" id="birth_place"
                                value="{{ old('birth_place', $student->birth_place) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Ville, Pays">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Adresse complète">{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Driving School Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Auto-École</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- License Category -->
                        <div>
                            <label for="license_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie de Permis *
                            </label>
                            <select name="license_category" id="license_category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner la catégorie</option>
                                <option value="A" {{ old('license_category', $student->license_category) == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                                <option value="B" {{ old('license_category', $student->license_category) == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                                <option value="C" {{ old('license_category', $student->license_category) == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                                <option value="D" {{ old('license_category', $student->license_category) == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
                            </select>
                            @error('license_category')
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
                                <option value="registered" {{ old('status', $student->status) == 'registered' ? 'selected' : '' }}>Inscrit</option>
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Diplômé</option>
                                <option value="dropped" {{ old('status', $student->status) == 'dropped' ? 'selected' : '' }}>Abandonné</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Required Theory Hours -->
                        <div>
                            <label for="required_theory_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Heures Théoriques Requises
                            </label>
                            <input type="number" name="required_theory_hours" id="required_theory_hours" min="0"
                                value="{{ old('required_theory_hours', $student->required_theory_hours) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('required_theory_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Required Practical Hours -->
                        <div>
                            <label for="required_practical_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Heures Pratiques Requises
                            </label>
                            <input type="number" name="required_practical_hours" id="required_practical_hours" min="0"
                                value="{{ old('required_practical_hours', $student->required_practical_hours) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('required_practical_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Contact d'Urgence</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du Contact d'Urgence
                            </label>
                            <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Nom du contact">
                            @error('emergency_contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone du Contact d'Urgence
                            </label>
                            <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="+212 6XX XXX XXX">
                            @error('emergency_contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                            placeholder="Notes sur l'étudiant...">{{ old('notes', $student->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('students.index') }}" 
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