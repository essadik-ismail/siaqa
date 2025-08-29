@extends('layouts.app')

@section('title', 'Modifier Agence')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('agences.show', $agence) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Modifier Agence</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('agences.update', $agence) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center space-x-4">
                            @if($agence->logo)
                            <img src="{{ asset('storage/' . $agence->logo) }}" alt="Logo actuel" class="w-16 h-16 rounded-lg">
                            @endif
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom_agence" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'agence *</label>
                            <input type="text" name="nom_agence" id="nom_agence" value="{{ old('nom_agence', $agence->nom_agence) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nom_agence')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $agence->ville) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ville')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="Adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <input type="text" name="Adresse" id="Adresse" value="{{ old('Adresse', $agence->Adresse) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('Adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Legal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rc" class="block text-sm font-medium text-gray-700 mb-2">RC</label>
                            <input type="text" name="rc" id="rc" value="{{ old('rc', $agence->rc) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('rc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="patente" class="block text-sm font-medium text-gray-700 mb-2">Patente</label>
                            <input type="text" name="patente" id="patente" value="{{ old('patente', $agence->patente) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('patente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="IF" class="block text-sm font-medium text-gray-700 mb-2">IF</label>
                            <input type="text" name="IF" id="IF" value="{{ old('IF', $agence->IF) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('IF')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="n_cnss" class="block text-sm font-medium text-gray-700 mb-2">N° CNSS</label>
                            <input type="text" name="n_cnss" id="n_cnss" value="{{ old('n_cnss', $agence->n_cnss) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('n_cnss')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ICE" class="block text-sm font-medium text-gray-700 mb-2">ICE</label>
                            <input type="text" name="ICE" id="ICE" value="{{ old('ICE', $agence->ICE) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('ICE')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="n_compte_bancaire" class="block text-sm font-medium text-gray-700 mb-2">N° Compte Bancaire</label>
                            <input type="text" name="n_compte_bancaire" id="n_compte_bancaire" value="{{ old('n_compte_bancaire', $agence->n_compte_bancaire) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('n_compte_bancaire')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('agences.show', $agence) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
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
@endsection 