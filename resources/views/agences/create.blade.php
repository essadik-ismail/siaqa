@extends('layouts.app')

@section('title', 'Nouvelle Agence')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('agences.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Agence</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('agences.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo -->
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <input type="file" name="logo" id="logo" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom Agence -->
                    <div>
                        <label for="nom_agence" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'agence *</label>
                        <input type="text" name="nom_agence" id="nom_agence" value="{{ old('nom_agence') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('nom_agence')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                        <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('ville')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RC -->
                    <div>
                        <label for="rc" class="block text-sm font-medium text-gray-700 mb-2">Registre de Commerce</label>
                        <input type="text" name="rc" id="rc" value="{{ old('rc') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('rc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Patente -->
                    <div>
                        <label for="patente" class="block text-sm font-medium text-gray-700 mb-2">Patente</label>
                        <input type="text" name="patente" id="patente" value="{{ old('patente') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('patente')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- IF -->
                    <div>
                        <label for="IF" class="block text-sm font-medium text-gray-700 mb-2">Identifiant Fiscal</label>
                        <input type="text" name="IF" id="IF" value="{{ old('IF') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('IF')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CNSS -->
                    <div>
                        <label for="n_cnss" class="block text-sm font-medium text-gray-700 mb-2">N° CNSS</label>
                        <input type="text" name="n_cnss" id="n_cnss" value="{{ old('n_cnss') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('n_cnss')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICE -->
                    <div>
                        <label for="ICE" class="block text-sm font-medium text-gray-700 mb-2">ICE</label>
                        <input type="text" name="ICE" id="ICE" value="{{ old('ICE') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('ICE')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Compte Bancaire -->
                    <div class="md:col-span-2">
                        <label for="n_compte_bancaire" class="block text-sm font-medium text-gray-700 mb-2">N° Compte Bancaire</label>
                        <input type="text" name="n_compte_bancaire" id="n_compte_bancaire" value="{{ old('n_compte_bancaire') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('n_compte_bancaire')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Agence active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('agences.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Créer l'agence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 