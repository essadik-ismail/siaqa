@extends('layouts.app')

@section('title', 'Add New Client')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Add New Client</h2>
                    <p class="text-gray-600">Create a new client account for vehicle rentals</p>
                </div>
                <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Clients
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror"
                                   placeholder="Enter last name">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror"
                                   placeholder="Enter first name">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="Enter email address">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                                   placeholder="Enter phone number">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror"
                                   placeholder="Enter address">
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- License Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Driver's License Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="numero_permis" class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                            <input type="text" id="numero_permis" name="numero_permis" value="{{ old('numero_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_permis') border-red-500 @enderror"
                                   placeholder="Enter license number">
                            @error('numero_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_expiration_permis" class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                            <input type="date" id="date_expiration_permis" name="date_expiration_permis" value="{{ old('date_expiration_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_expiration_permis') border-red-500 @enderror">
                            @error('date_expiration_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="numero_piece_identite" class="block text-sm font-medium text-gray-700 mb-2">ID Number *</label>
                            <input type="text" id="numero_piece_identite" name="numero_piece_identite" value="{{ old('numero_piece_identite') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('numero_piece_identite') border-red-500 @enderror"
                                   placeholder="Enter ID number">
                            @error('numero_piece_identite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="type_piece_identite" class="block text-sm font-medium text-gray-700 mb-2">ID Type *</label>
                            <select id="type_piece_identite" name="type_piece_identite" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type_piece_identite') border-red-500 @enderror">
                                <option value="">Select ID type</option>
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
                            <label for="date_expiration_piece" class="block text-sm font-medium text-gray-700 mb-2">ID Expiry Date *</label>
                            <input type="date" id="date_expiration_piece" name="date_expiration_piece" value="{{ old('date_expiration_piece') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_expiration_piece') border-red-500 @enderror">
                            @error('date_expiration_piece')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_obtention_permis" class="block text-sm font-medium text-gray-700 mb-2">License Issue Date *</label>
                            <input type="date" id="date_obtention_permis" name="date_obtention_permis" value="{{ old('date_obtention_permis') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_obtention_permis') border-red-500 @enderror">
                            @error('date_obtention_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                            <input type="text" id="pays" name="pays" value="{{ old('pays') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pays') border-red-500 @enderror"
                                   placeholder="Enter country">
                            @error('pays')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Professional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" id="profession" name="profession" value="{{ old('profession') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('profession') border-red-500 @enderror"
                                   placeholder="Enter profession">
                            @error('profession')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="employeur" class="block text-sm font-medium text-gray-700 mb-2">Employer</label>
                            <input type="text" id="employeur" name="employeur" value="{{ old('employeur') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employeur') border-red-500 @enderror"
                                   placeholder="Enter employer">
                            @error('employeur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="revenu_mensuel" class="block text-sm font-medium text-gray-700 mb-2">Monthly Income</label>
                            <input type="number" id="revenu_mensuel" name="revenu_mensuel" value="{{ old('revenu_mensuel') }}" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('revenu_mensuel') border-red-500 @enderror"
                                   placeholder="Enter monthly income">
                            @error('revenu_mensuel')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Upload -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Document Upload</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Upload CIN, passport, or other ID document (max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('images') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Upload additional documents (max 2MB each)</p>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" id="ville" name="ville" value="{{ old('ville') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('ville') border-red-500 @enderror"
                                   placeholder="Enter city">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code_postal') border-red-500 @enderror"
                                   placeholder="Enter postal code">
                            @error('code_postal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                      placeholder="Enter any additional notes about the client">{{ old('notes') }}</textarea>
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
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Create Client
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('sidebar')
    <!-- Form Tips -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Form Tips</h3>
        <div class="space-y-3">
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium">Required Fields</p>
                        <p class="text-xs text-blue-600 mt-1">Fields marked with * are required for client registration.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-green-800 font-medium">License Validation</p>
                        <p class="text-xs text-green-600 mt-1">Ensure the license expiry date is in the future.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium">Data Privacy</p>
                        <p class="text-xs text-yellow-600 mt-1">All client information is securely stored and protected.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('clients.index') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-list mr-2"></i>View All Clients
            </a>
            <a href="{{ route('clients.statistics') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-lg font-medium text-center">
                <i class="fas fa-chart-bar mr-2"></i>Client Statistics
            </a>
        </div>
    </div>
@endsection 