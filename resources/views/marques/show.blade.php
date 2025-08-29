@extends('layouts.app')

@section('title', 'Détails Marque')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('marques.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Détails Marque</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center">
                @if($marque->image)
                <img src="{{ asset('storage/' . $marque->image) }}" alt="Logo {{ $marque->marque }}" class="w-16 h-16 rounded-lg mr-4">
                @endif
                <h2 class="text-xl font-semibold text-gray-900">{{ $marque->marque }}</h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('marques.edit', $marque) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom de la marque</dt>
                        <dd class="text-sm text-gray-900">{{ $marque->marque }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre de véhicules</dt>
                        <dd class="text-sm text-gray-900">{{ $marque->vehicules->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                        <dd class="text-sm text-gray-900">{{ $marque->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('vehicules.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded text-sm">
                        <i class="fas fa-plus mr-2"></i>Ajouter un véhicule
                    </a>
                    <a href="{{ route('marques.edit', $marque) }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded text-sm">
                        <i class="fas fa-edit mr-2"></i>Modifier la marque
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($marque->vehicules->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Véhicules ({{ $marque->vehicules->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modèle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Immatriculation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($marque->vehicules->take(5) as $vehicule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $vehicule->modele }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $vehicule->immatriculation }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($vehicule->statut == 'disponible') bg-green-100 text-green-800
                                @elseif($vehicule->statut == 'en_location') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($vehicule->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('vehicules.show', $vehicule) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection 