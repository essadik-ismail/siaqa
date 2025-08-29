@extends('layouts.app')

@section('title', 'Détails de la Visite Technique')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Détails de la Visite Technique</h2>
            <p class="text-gray-600 text-lg">Informations complètes sur la visite technique</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('visites.edit', $visite) }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('visites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Visite Details -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Informations de la Visite</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date de Visite</label>
                        <p class="text-sm text-gray-900">{{ $visite->date_visite ? $visite->date_visite->format('d/m/Y') : 'Non définie' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Type de Visite</label>
                        <p class="text-sm text-gray-900">{{ ucfirst($visite->type_visite ?? 'Non spécifié') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Statut</label>
                        @php
                            $statusClasses = [
                                'planifiee' => 'bg-yellow-100 text-yellow-800',
                                'en_cours' => 'bg-blue-100 text-blue-800',
                                'terminee' => 'bg-green-100 text-green-800',
                                'annulee' => 'bg-red-100 text-red-800'
                            ];
                            $statusClass = $statusClasses[$visite->statut] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($visite->statut) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Centre d'Inspection</label>
                        <p class="text-sm text-gray-900">{{ $visite->centre_inspection ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Inspecteur</label>
                        <p class="text-sm text-gray-900">{{ $visite->inspecteur ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Coût</label>
                        <p class="text-sm text-gray-900">{{ $visite->cout ? number_format($visite->cout, 2) . ' €' : 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Résultats</label>
                        <p class="text-sm text-gray-900">{{ $visite->resultats ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Prochaine Visite</label>
                        <p class="text-sm text-gray-900">{{ $visite->prochaine_visite ? $visite->prochaine_visite->format('d/m/Y') : 'Non définie' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Kilométrage</label>
                        <p class="text-sm text-gray-900">{{ $visite->kilometrage ? number_format($visite->kilometrage) . ' km' : 'Non spécifié' }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($visite->notes)
                <div class="content-card p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Notes</h3>
                    <p class="text-sm text-gray-700">{{ $visite->notes }}</p>
                </div>
            @endif

            <!-- Actions -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    @if($visite->statut === 'planifiee')
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-play mr-2"></i>Commencer
                        </button>
                    @endif
                    @if($visite->statut === 'en_cours')
                        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-check mr-2"></i>Terminer
                        </button>
                    @endif
                    @if(in_array($visite->statut, ['planifiee', 'en_cours']))
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Vehicle Info -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Véhicule</h3>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-car text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $visite->vehicule->marque->nom }} {{ $visite->vehicule->modele }}</p>
                        <p class="text-sm text-gray-500">{{ $visite->vehicule->immatriculation }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Année:</span>
                        <span class="text-gray-900">{{ $visite->vehicule->annee ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Carburant:</span>
                        <span class="text-gray-900">{{ ucfirst($visite->vehicule->carburant ?? 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kilométrage:</span>
                        <span class="text-gray-900">{{ number_format($visite->vehicule->kilometrage ?? 0) }} km</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">
                            @php
                                $daysUntil = $visite->date_visite ? now()->diffInDays($visite->date_visite, false) : 0;
                            @endphp
                            {{ $daysUntil > 0 ? $daysUntil : 0 }}
                        </p>
                        <p class="text-sm text-gray-600">
                            @if($daysUntil > 0)
                                Jours restants
                            @elseif($daysUntil < 0)
                                Jours de retard
                            @else
                                Aujourd'hui
                            @endif
                        </p>
                    </div>
                    @if($visite->prochaine_visite)
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">
                                @php
                                    $nextVisitDays = now()->diffInDays($visite->prochaine_visite, false);
                                @endphp
                                {{ $nextVisitDays > 0 ? $nextVisitDays : 0 }}
                            </p>
                            <p class="text-sm text-gray-600">Jours jusqu'à la prochaine</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('vehicules.show', $visite->vehicule) }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-car mr-2"></i>Voir Véhicule
                    </a>
                    <a href="{{ route('visites.create', ['vehicule_id' => $visite->vehicule->id]) }}" class="w-full bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Nouvelle Visite
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
