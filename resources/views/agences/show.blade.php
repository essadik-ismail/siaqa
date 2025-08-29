@extends('layouts.app')

@section('title', 'Détails Agence')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('agences.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Détails Agence</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Agency Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center">
                        @if($agence->logo)
                        <img src="{{ asset('storage/' . $agence->logo) }}" alt="Logo" class="w-16 h-16 rounded-lg mr-4">
                        @endif
                        <h2 class="text-xl font-semibold text-gray-900">{{ $agence->nom_agence }}</h2>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('agences.edit', $agence) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-edit mr-1"></i>Modifier
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom de l'agence</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->nom_agence }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->Adresse ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ville</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->ville ?? 'Non spécifié' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations légales</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">RC</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->rc ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Patente</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->patente ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">IF</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->IF ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">N° CNSS</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->n_cnss ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ICE</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->ICE ?? 'Non spécifié' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">N° Compte Bancaire</dt>
                                <dd class="text-sm text-gray-900">{{ $agence->n_compte_bancaire ?? 'Non spécifié' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Statut</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Actif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Créée le</span>
                        <span class="text-sm text-gray-900">{{ $agence->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dernière mise à jour</span>
                        <span class="text-sm text-gray-900">{{ $agence->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Véhicules</span>
                        <span class="text-sm font-medium text-gray-900">{{ $agence->vehicules->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Employés</span>
                        <span class="text-sm font-medium text-gray-900">{{ $agence->users->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Réservations</span>
                        <span class="text-sm font-medium text-gray-900">{{ $agence->reservations->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Section -->
    @if($agence->vehicules->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Véhicules de l'agence</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marque</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Immatriculation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($agence->vehicules->take(10) as $vehicule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $vehicule->marque->marque }} {{ $vehicule->modele }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $vehicule->marque->marque }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $vehicule->immatriculation }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($vehicule->statut == 'disponible') bg-green-100 text-green-800
                                    @elseif($vehicule->statut == 'en_location') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($vehicule->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('vehicules.show', $vehicule) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Employees Section -->
    @if($agence->users->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Employés de l'agence</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($agence->users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                        {{ $role->display_name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->is_active) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 