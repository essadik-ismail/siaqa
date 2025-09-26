@extends('layouts.app')

@section('title', 'Véhicules')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Véhicules</h1>
                    <p class="mt-2 text-gray-600 text-lg">Gérez la flotte de véhicules de votre auto-école</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('vehicules.create') }}" class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un Véhicule
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="material-card p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher des véhicules..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Statuts</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>En Location</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>En Maintenance</option>
                        <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>Hors Service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Types</option>
                        <option value="car" {{ request('type') == 'car' ? 'selected' : '' }}>Voiture</option>
                        <option value="motorcycle" {{ request('type') == 'motorcycle' ? 'selected' : '' }}>Moto</option>
                        <option value="truck" {{ request('type') == 'truck' ? 'selected' : '' }}>Camion</option>
                        <option value="bus" {{ request('type') == 'bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full material-button">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vehicules as $vehicule)
                <div class="material-card overflow-hidden">
                    <!-- Vehicle Header -->
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="icon-container w-12 h-12">
                                <i class="fas fa-car text-white text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $vehicule->marque }} {{ $vehicule->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $vehicule->immatriculation }}</p>
                                <p class="text-xs text-gray-400">{{ $vehicule->categorie_vehicule ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                    $vehicule->status == 'available' ? 'bg-green-100 text-green-800' : 
                                    ($vehicule->status == 'rented' ? 'bg-blue-100 text-blue-800' : 
                                    ($vehicule->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-red-100 text-red-800')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $vehicule->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details -->
                    <div class="px-6 pb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Catégorie:</span>
                                <p class="font-medium">{{ $vehicule->categorie_vehicule ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Kilométrage:</span>
                                <p class="font-medium">{{ number_format($vehicule->kilometrage_actuel ?? 0) }} km</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Carburant:</span>
                                <p class="font-medium">{{ ucfirst($vehicule->type_carburant ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Couleur:</span>
                                <p class="font-medium">{{ ucfirst($vehicule->couleur ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Maintenance -->
                    @if($vehicule->next_maintenance_date)
                        <div class="px-6 pb-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Prochaine Maintenance</span>
                                <span>{{ \Carbon\Carbon::parse($vehicule->next_maintenance_date)->format('d M Y') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $daysUntilMaintenance = \Carbon\Carbon::parse($vehicule->next_maintenance_date)->diffInDays(now());
                                    $maintenancePercentage = max(0, min(100, (30 - $daysUntilMaintenance) / 30 * 100));
                                @endphp
                                <div class="bg-gradient-to-r from-yellow-500 to-red-500 h-2 rounded-full" 
                                     style="width: {{ $maintenancePercentage }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('vehicules.show', $vehicule) }}" 
                               class="flex-1 material-button text-center px-3 py-2 text-sm">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                            <a href="{{ route('vehicules.edit', $vehicule) }}" 
                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('vehicules.destroy', $vehicule) }}" 
                                  class="flex-1" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-24 h-24 icon-container mx-auto mb-4">
                            <i class="fas fa-car text-4xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun véhicule trouvé</h3>
                        <p class="text-gray-500 mb-6">Commencez par ajouter votre premier véhicule.</p>
                        <a href="{{ route('vehicules.create') }}" 
                           class="material-button">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter un Véhicule
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($vehicules->hasPages())
            <div class="mt-8">
                {{ $vehicules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection