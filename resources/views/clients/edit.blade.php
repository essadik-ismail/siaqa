@extends('layouts.app')

@section('title', 'Modifier Client')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('clients.show', $client) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Modifier Client</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('clients.update', $client) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $client->nom) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $client->prenom) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Company Information (for societe type) -->
                    <div id="company-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ $client->type == 'societe' ? 'grid' : 'none' }};">
                        <div>
                            <label for="nom_societe" class="block text-sm font-medium text-gray-700 mb-2">Nom de la société</label>
                            <input type="text" name="nom_societe" id="nom_societe" value="{{ old('nom_societe', $client->nom_societe) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom_societe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ice_societe" class="block text-sm font-medium text-gray-700 mb-2">ICE</label>
                            <input type="text" name="ice_societe" id="ice_societe" value="{{ old('ice_societe', $client->ice_societe) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ice_societe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Personal Information (for client type) -->
                    <div id="personal-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ $client->type == 'client' ? 'grid' : 'none' }};">
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance', $client->date_naissance ? $client->date_naissance->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="lieu_de_naissance" class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance</label>
                            <input type="text" name="lieu_de_naissance" id="lieu_de_naissance" value="{{ old('lieu_de_naissance', $client->lieu_de_naissance) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('lieu_de_naissance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nationalite" class="block text-sm font-medium text-gray-700 mb-2">Nationalité</label>
                            <input type="text" name="nationalite" id="nationalite" value="{{ old('nationalite', $client->nationalite) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nationalite')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $client->telephone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('telephone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $client->adresse) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('adresse')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $client->ville) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $client->postal_code) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Identity Documents (for client type) -->
                    <div id="identity-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ $client->type == 'client' ? 'grid' : 'none' }};">
                        <div>
                            <label for="numero_cin" class="block text-sm font-medium text-gray-700 mb-2">Numéro CIN</label>
                            <input type="text" name="numero_cin" id="numero_cin" value="{{ old('numero_cin', $client->numero_cin) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('numero_cin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_cin_expiration" class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration CIN</label>
                            <input type="date" name="date_cin_expiration" id="date_cin_expiration" value="{{ old('date_cin_expiration', $client->date_cin_expiration ? $client->date_cin_expiration->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_cin_expiration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="numero_permis" class="block text-sm font-medium text-gray-700 mb-2">Numéro de permis</label>
                            <input type="text" name="numero_permis" id="numero_permis" value="{{ old('numero_permis', $client->numero_permis) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('numero_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_permis" class="block text-sm font-medium text-gray-700 mb-2">Date d'obtention permis</label>
                            <input type="date" name="date_permis" id="date_permis" value="{{ old('date_permis', $client->date_permis ? $client->date_permis->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_permis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passport" class="block text-sm font-medium text-gray-700 mb-2">Passeport</label>
                            <input type="text" name="passport" id="passport" value="{{ old('passport', $client->passport) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('passport')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_passport" class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration passeport</label>
                            <input type="date" name="date_passport" id="date_passport" value="{{ old('date_passport', $client->date_passport ? $client->date_passport->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('date_passport')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $client->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('clients.show', $client) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const companyFields = document.getElementById('company-fields');
    const personalFields = document.getElementById('personal-fields');
    const identityFields = document.getElementById('identity-fields');

    function toggleFields() {
        const selectedType = typeSelect.value;
        
        if (selectedType === 'societe') {
            companyFields.style.display = 'grid';
            personalFields.style.display = 'none';
            identityFields.style.display = 'none';
        } else {
            companyFields.style.display = 'none';
            personalFields.style.display = 'grid';
            identityFields.style.display = 'grid';
        }
    }

    typeSelect.addEventListener('change', toggleFields);
});
</script>
@endsection 