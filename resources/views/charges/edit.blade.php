@extends('layouts.app')

@section('title', 'Modifier la Charge')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier la Charge</h1>
                <p class="text-gray-600">Modifier les informations de la charge</p>
            </div>
            <a href="{{ route('charges.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form method="POST" action="{{ route('charges.update', $charge) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Designation -->
                    <div>
                        <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Désignation de la charge *</label>
                        <select id="designation" name="designation" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('designation') border-red-500 @enderror">
                            <option value="">Sélectionner une désignation</option>
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ old('designation', $charge->designation) == $key ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('designation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date de la charge *</label>
                        <input type="date" id="date" name="date"
                               value="{{ old('date', $charge->date ? $charge->date->format('Y-m-d') : '') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                        @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Montant -->
                    <div>
                        <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">Montant (€) *</label>
                        <input type="number" id="montant" name="montant" value="{{ old('montant', $charge->montant) }}" required min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('montant') border-red-500 @enderror" placeholder="0.00">
                        @error('montant') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fichier -->
                    <div>
                        <label for="fichier" class="block text-sm font-medium text-gray-700 mb-2">Fichier</label>
                        <input type="text" id="fichier" name="fichier" value="{{ old('fichier', $charge->fichier) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fichier') border-red-500 @enderror" placeholder="Nom du fichier">
                        @error('fichier') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Description détaillée de la charge...">{{ old('description', $charge->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-3 pt-6">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('charges.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
