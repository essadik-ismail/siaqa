@extends('layouts.app')

@section('title', 'Nouvelle Marque')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Nouvelle Marque</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('marques.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="marque" class="block text-sm font-medium text-gray-700 mb-2">Nom de la marque *</label>
                        <input type="text" name="marque" id="marque" value="{{ old('marque') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Ex: Renault">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('marques.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Créer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 