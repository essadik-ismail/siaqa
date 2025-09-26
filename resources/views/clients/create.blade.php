@extends('layouts.app')

@section('title', 'Ajouter un Nouveau Client')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Ajouter un Nouveau Client</h2>
                    <p class="text-gray-600">Créer un nouveau compte client pour la location de véhicules</p>
                </div>
                <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux Clients
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informations Personnelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror"
                                   placeholder="Entrez le prénom">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de famille *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror"
                                   placeholder="Entrez le nom de famille">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="Entrez l'adresse email">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                                   placeholder="Entrez le numéro de téléphone">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                            <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror"
                                   placeholder="Entrez l'adresse">
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- License Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informations du Permis de Conduire</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="numero_permis" class="block text-sm font-medium text-gray-700 mb-2">Numéro de permis *</label>
                            <input type="text" id="numero_permis" name="numero_permis" value="{{ old('numero_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_permis') border-red-500 @enderror"
                                   placeholder="Entrez le numéro de permis">
                            @error('numero_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_expiration_permis" class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration du permis *</label>
                            <input type="date" id="date_expiration_permis" name="date_expiration_permis" value="{{ old('date_expiration_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_expiration_permis') border-red-500 @enderror">
                            @error('date_expiration_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="numero_piece_identite" class="block text-sm font-medium text-gray-700 mb-2">Numéro de pièce d'identité *</label>
                            <input type="text" id="numero_piece_identite" name="numero_piece_identite" value="{{ old('numero_piece_identite') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_piece_identite') border-red-500 @enderror"
                                   placeholder="Entrez le numéro de pièce d'identité">
                            @error('numero_piece_identite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="type_piece_identite" class="block text-sm font-medium text-gray-700 mb-2">Type de pièce d'identité *</label>
                            <select id="type_piece_identite" name="type_piece_identite" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_piece_identite') border-red-500 @enderror">
                                <option value="">Sélectionnez le type de pièce d'identité</option>
                                <option value="carte_nationale" {{ old('type_piece_identite') === 'carte_nationale' ? 'selected' : '' }}>Carte Nationale</option>
                                <option value="passeport" {{ old('type_piece_identite') === 'passeport' ? 'selected' : '' }}>Passeport</option>
                                <option value="permis_conduire" {{ old('type_piece_identite') === 'permis_conduire' ? 'selected' : '' }}>Permis de Conduire</option>
                                <option value="carte_sejour" {{ old('type_piece_identite') === 'carte_sejour' ? 'selected' : '' }}>Carte de Séjour</option>
                            </select>
                            @error('type_piece_identite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_expiration_piece" class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration de la pièce *</label>
                            <input type="date" id="date_expiration_piece" name="date_expiration_piece" value="{{ old('date_expiration_piece') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_expiration_piece') border-red-500 @enderror">
                            @error('date_expiration_piece')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_obtention_permis" class="block text-sm font-medium text-gray-700 mb-2">Date d'obtention du permis *</label>
                            <input type="date" id="date_obtention_permis" name="date_obtention_permis" value="{{ old('date_obtention_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_obtention_permis') border-red-500 @enderror">
                            @error('date_obtention_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays *</label>
                            <input type="text" id="pays" name="pays" value="{{ old('pays') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pays') border-red-500 @enderror"
                                   placeholder="Entrez le pays">
                            @error('pays')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informations Professionnelles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" id="profession" name="profession" value="{{ old('profession') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profession') border-red-500 @enderror"
                                   placeholder="Entrez la profession">
                            @error('profession')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="employeur" class="block text-sm font-medium text-gray-700 mb-2">Employeur</label>
                            <input type="text" id="employeur" name="employeur" value="{{ old('employeur') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employeur') border-red-500 @enderror"
                                   placeholder="Entrez l'employeur">
                            @error('employeur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="revenu_mensuel" class="block text-sm font-medium text-gray-700 mb-2">Revenu mensuel</label>
                            <input type="number" id="revenu_mensuel" name="revenu_mensuel" value="{{ old('revenu_mensuel') }}" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('revenu_mensuel') border-red-500 @enderror"
                                   placeholder="Entrez le revenu mensuel">
                            @error('revenu_mensuel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Téléchargement de Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image principale</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Téléchargez CIN, passeport ou autre document d'identité (max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Images supplémentaires</label>
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('images') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Téléchargez des documents supplémentaires (max 2MB chacun)</p>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Informations Supplémentaires</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                            <input type="text" id="ville" name="ville" value="{{ old('ville') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ville') border-red-500 @enderror"
                                   placeholder="Entrez la ville">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                            <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_postal') border-red-500 @enderror"
                                   placeholder="Entrez le code postal">
                            @error('code_postal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                      placeholder="Entrez des notes supplémentaires sur le client">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('clients.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Créer le Client
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('sidebar')
    <!-- Form Tips -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Conseils du Formulaire</h3>
        <div class="space-y-3">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Champs Obligatoires</p>
                        <p class="text-xs text-blue-600 mt-1">Les champs marqués d'un * sont obligatoires pour l'enregistrement du client.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-green-800 font-medium">Validation du Permis</p>
                        <p class="text-xs text-green-600 mt-1">Assurez-vous que la date d'expiration du permis est dans le futur.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">Confidentialité des Données</p>
                        <p class="text-xs text-yellow-600 mt-1">Toutes les informations client sont stockées et protégées de manière sécurisée.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
        <div class="space-y-3">
            <a href="{{ route('clients.index') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-list mr-2"></i>Voir Tous les Clients
            </a>
            <a href="{{ route('students.statistics') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-chart-bar mr-2"></i>Statistiques des Étudiants
            </a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    // Validation rules
    const validationRules = {
        prenom: {
            required: true,
            minLength: 2,
            pattern: /^[a-zA-ZÀ-ÿ\s'-]+$/,
            message: 'Le prénom doit contenir au moins 2 caractères et uniquement des lettres'
        },
        nom: {
            required: true,
            minLength: 2,
            pattern: /^[a-zA-ZÀ-ÿ\s'-]+$/,
            message: 'Le nom de famille doit contenir au moins 2 caractères et uniquement des lettres'
        },
        email: {
            required: true,
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Veuillez entrer une adresse email valide'
        },
        telephone: {
            required: true,
            pattern: /^(\+212|0)[5-7][0-9]{8}$/,
            message: 'Veuillez entrer un numéro de téléphone marocain valide (ex: +212612345678 ou 0612345678)'
        },
        numero_permis: {
            required: true,
            minLength: 5,
            message: 'Le numéro de permis doit contenir au moins 5 caractères'
        },
        numero_piece_identite: {
            required: true,
            minLength: 5,
            message: 'Le numéro de pièce d\'identité doit contenir au moins 5 caractères'
        },
        type_piece_identite: {
            required: true,
            message: 'Veuillez sélectionner un type de pièce d\'identité'
        },
        pays: {
            required: true,
            minLength: 2,
            message: 'Le pays doit contenir au moins 2 caractères'
        },
        revenu_mensuel: {
            pattern: /^\d+(\.\d{1,2})?$/,
            message: 'Veuillez entrer un montant valide (ex: 5000 ou 5000.50)'
        },
        ville: {
            minLength: 2,
            message: 'La ville doit contenir au moins 2 caractères'
        },
        code_postal: {
            pattern: /^\d{5}$/,
            message: 'Le code postal doit contenir exactement 5 chiffres'
        }
    };

    // Add validation styles
    function addValidationStyles(input, isValid, message) {
        const errorElement = input.parentNode.querySelector('.validation-error');
        
        // Remove existing error styling
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
        
        if (errorElement) {
            errorElement.remove();
        }
        
        if (!isValid) {
            input.classList.remove('border-gray-300');
            input.classList.add('border-red-500');
            
            // Add error message
            const errorDiv = document.createElement('p');
            errorDiv.className = 'mt-1 text-sm text-red-600 validation-error';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        } else {
            input.classList.remove('border-gray-300');
            input.classList.add('border-green-500');
        }
    }

    // Validate individual field
    function validateField(input) {
        const fieldName = input.name;
        const value = input.value.trim();
        const rules = validationRules[fieldName];
        
        if (!rules) return true;
        
        let isValid = true;
        let message = '';
        
        // Required validation
        if (rules.required && !value) {
            isValid = false;
            message = 'Ce champ est obligatoire';
        }
        // Pattern validation
        else if (value && rules.pattern && !rules.pattern.test(value)) {
            isValid = false;
            message = rules.message;
        }
        // Min length validation
        else if (value && rules.minLength && value.length < rules.minLength) {
            isValid = false;
            message = rules.message;
        }
        
        addValidationStyles(input, isValid, message);
        return isValid;
    }

    // Validate date fields
    function validateDates() {
        const dateNaissance = document.getElementById('date_naissance');
        const dateExpirationPermis = document.getElementById('date_expiration_permis');
        const dateExpirationPiece = document.getElementById('date_expiration_piece');
        const dateObtentionPermis = document.getElementById('date_obtention_permis');
        
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0];
        
        // Validate birth date (must be in the past)
        if (dateNaissance.value && dateNaissance.value > todayStr) {
            addValidationStyles(dateNaissance, false, 'La date de naissance doit être dans le passé');
            return false;
        } else if (dateNaissance.value) {
            addValidationStyles(dateNaissance, true, '');
        }
        
        // Validate license expiry date (must be in the future)
        if (dateExpirationPermis.value && dateExpirationPermis.value <= todayStr) {
            addValidationStyles(dateExpirationPermis, false, 'La date d\'expiration du permis doit être dans le futur');
            return false;
        } else if (dateExpirationPermis.value) {
            addValidationStyles(dateExpirationPermis, true, '');
        }
        
        // Validate ID expiry date (must be in the future)
        if (dateExpirationPiece.value && dateExpirationPiece.value <= todayStr) {
            addValidationStyles(dateExpirationPiece, false, 'La date d\'expiration de la pièce doit être dans le futur');
            return false;
        } else if (dateExpirationPiece.value) {
            addValidationStyles(dateExpirationPiece, true, '');
        }
        
        // Validate license issue date (must be in the past)
        if (dateObtentionPermis.value && dateObtentionPermis.value > todayStr) {
            addValidationStyles(dateObtentionPermis, false, 'La date d\'obtention du permis doit être dans le passé');
            return false;
        } else if (dateObtentionPermis.value) {
            addValidationStyles(dateObtentionPermis, true, '');
        }
        
        return true;
    }

    // Validate file uploads
    function validateFileUploads() {
        const mainImage = document.getElementById('image');
        const additionalImages = document.getElementById('images');
        let isValid = true;
        
        // Validate main image
        if (mainImage.files.length > 0) {
            const file = mainImage.files[0];
            if (file.size > 2 * 1024 * 1024) { // 2MB
                addValidationStyles(mainImage, false, 'La taille du fichier ne doit pas dépasser 2MB');
                isValid = false;
            } else if (!file.type.startsWith('image/')) {
                addValidationStyles(mainImage, false, 'Veuillez sélectionner un fichier image valide');
                isValid = false;
            } else {
                addValidationStyles(mainImage, true, '');
            }
        }
        
        // Validate additional images
        if (additionalImages.files.length > 0) {
            Array.from(additionalImages.files).forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    addValidationStyles(additionalImages, false, `Le fichier ${index + 1} ne doit pas dépasser 2MB`);
                    isValid = false;
                } else if (!file.type.startsWith('image/')) {
                    addValidationStyles(additionalImages, false, `Le fichier ${index + 1} doit être une image valide`);
                    isValid = false;
                } else {
                    addValidationStyles(additionalImages, true, '');
                }
            });
        }
        
        return isValid;
    }

    // Add event listeners
    inputs.forEach(input => {
        // Real-time validation on input
        input.addEventListener('input', function() {
            validateField(this);
            if (this.type === 'date') {
                validateDates();
            }
        });
        
        // Validation on blur
        input.addEventListener('blur', function() {
            validateField(this);
            if (this.type === 'date') {
                validateDates();
            }
        });
    });

    // File upload validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateFileUploads();
        });
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        
        // Validate all fields
        inputs.forEach(input => {
            if (!validateField(input)) {
                isFormValid = false;
            }
        });
        
        // Validate dates
        if (!validateDates()) {
            isFormValid = false;
        }
        
        // Validate file uploads
        if (!validateFileUploads()) {
            isFormValid = false;
        }
        
        if (isFormValid) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création en cours...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
        } else {
            // Scroll to first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            errorAlert.innerHTML = '<strong>Erreur:</strong> Veuillez corriger les erreurs ci-dessous avant de soumettre le formulaire.';
            
            const formContainer = document.querySelector('.bg-white.rounded-xl.shadow-lg.p-6');
            formContainer.insertBefore(errorAlert, form);
            
            // Remove error alert after 5 seconds
            setTimeout(() => {
                if (errorAlert.parentNode) {
                    errorAlert.remove();
                }
            }, 5000);
        }
    });

    // Add visual feedback for required fields
    const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
    requiredFields.forEach(field => {
        const label = field.parentNode.querySelector('label');
        if (label && !label.textContent.includes('*')) {
            label.innerHTML = label.innerHTML + ' <span class="text-red-500">*</span>';
        }
    });
});
</script>
@endsection 