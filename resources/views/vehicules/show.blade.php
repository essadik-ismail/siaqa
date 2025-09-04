@extends('layouts.app')

@section('title', 'Détails du Véhicule')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $vehicule->name }}</h2>
            <p class="text-gray-600 text-lg">{{ $vehicule->immatriculation }} - {{ $vehicule->marque->marque }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('vehicules.edit', $vehicule) }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('vehicules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Vehicle Status Badge -->
    <div class="mb-6">
        @if($vehicule->statut == 'disponible')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                <i class="fas fa-check mr-2"></i>Disponible
            </span>
        @elseif($vehicule->statut == 'en_location')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                <i class="fas fa-key mr-2"></i>En location
            </span>
        @elseif($vehicule->statut == 'en_maintenance')
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                <i class="fas fa-tools mr-2"></i>En maintenance
            </span>
        @else
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                <i class="fas fa-ban mr-2"></i>Hors service
            </span>
        @endif
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('vehicle-info')" id="tab-vehicle-info" class="tab-button active border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600">
                <i class="fas fa-car mr-2"></i>Informations Véhicule
            </button>
            <button onclick="showTab('assurances')" id="tab-assurances" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-shield-alt mr-2"></i>Assurances
            </button>
            <button onclick="showTab('vidanges')" id="tab-vidanges" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-oil-can mr-2"></i>Vidanges
            </button>
            <button onclick="showTab('visites')" id="tab-visites" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-clipboard-check mr-2"></i>Visites
            </button>
            <button onclick="showTab('interventions')" id="tab-interventions" class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <i class="fas fa-tools mr-2"></i>Interventions
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    
    <!-- Vehicle Information Tab -->
    <div id="tab-content-vehicle-info" class="tab-content active">
        <div class="content-card p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Vehicle Details -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations Générales</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nom du véhicule</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Immatriculation</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->immatriculation }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Marque</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->marque->marque }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Modèle</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->modele ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Année</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->annee ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Couleur</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->couleur ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Technical Details -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Détails Techniques</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Carburant</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->carburant ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Catégorie</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->categorie ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Kilométrage</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($vehicule->kilometrage ?? 0) }} km</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Agence</label>
                            <p class="text-lg font-medium text-gray-900">{{ $vehicule->agence->nom_agence ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Statut</label>
                            <p class="text-lg font-medium text-gray-900">{{ ucfirst($vehicule->statut) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Prix de location/jour</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($vehicule->prix_location ?? 0, 2) }} €</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($vehicule->description)
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                <p class="text-gray-700">{{ $vehicule->description }}</p>
            </div>
            @endif

            <!-- Vehicle Image -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Image du Véhicule</h3>
                <div class="flex justify-center">
                    <div class="w-80 h-60 rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ $vehicule->image_url }}" 
                             alt="{{ $vehicule->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assurances Tab -->
    <div id="tab-content-assurances" class="tab-content hidden">
        <div class="content-card p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Assurances du Véhicule</h3>
                <a href="{{ route('assurances.create', ['vehicule_id' => $vehicule->id]) }}" class="btn-primary flex items-center space-x-3 px-4 py-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Assurance</span>
                </a>
            </div>

            @if($vehicule->assurances && $vehicule->assurances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compagnie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro Police</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicule->assurances as $assurance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $assurance->compagnie }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $assurance->numero_police }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $assurance->type_assurance)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Du {{ \Carbon\Carbon::parse($assurance->date_debut)->format('d/m/Y') }}<br>
                                        Au {{ \Carbon\Carbon::parse($assurance->date_expiration)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($assurance->statut == 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        @elseif($assurance->statut == 'expiree')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Expirée</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($assurance->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('assurances.show', $assurance) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('assurances.edit', $assurance) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('assurances.destroy', $assurance) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assurance ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-400">Aucune assurance trouvée</p>
                    <p class="text-sm text-gray-400 mt-1">Ajoutez la première assurance pour ce véhicule</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Vidanges Tab -->
    <div id="tab-content-vidanges" class="tab-content hidden">
        <div class="content-card p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Vidanges du Véhicule</h3>
                <a href="{{ route('vidanges.create', ['vehicule_id' => $vehicule->id]) }}" class="btn-primary flex items-center space-x-3 px-4 py-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Vidange</span>
                </a>
            </div>

            @if($vehicule->vidanges && $vehicule->vidanges->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Prévue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kilométrage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Huile</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicule->vidanges as $vidange)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($vidange->date_prevue)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($vidange->kilometrage_actuel) }} → {{ number_format($vidange->kilometrage_prochaine) }} km
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vidange->type_huile ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($vidange->statut == 'planifiee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Planifiée</span>
                                        @elseif($vidange->statut == 'en_cours')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En cours</span>
                                        @elseif($vidange->statut == 'terminee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Terminée</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($vidange->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vidange->cout_estime ? number_format($vidange->cout_estime, 2) . ' €' : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('vidanges.show', $vidange) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('vidanges.edit', $vidange) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('vidanges.destroy', $vidange) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidange ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-oil-can text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-400">Aucune vidange trouvée</p>
                    <p class="text-sm text-gray-400 mt-1">Planifiez la première vidange pour ce véhicule</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Visites Tab -->
    <div id="tab-content-visites" class="tab-content hidden">
        <div class="content-card p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Visites du Véhicule</h3>
                <a href="{{ route('visites.create', ['vehicule_id' => $vehicule->id]) }}" class="btn-primary flex items-center space-x-3 px-4 py-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Visite</span>
                </a>
            </div>

            @if($vehicule->visites && $vehicule->visites->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Visite</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résultat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicule->visites as $visite)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($visite->date_visite)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $visite->type_visite)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $visite->centre_visite ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visite->statut == 'planifiée')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Planifiée</span>
                                        @elseif($visite->statut == 'en_cours')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En cours</span>
                                        @elseif($visite->statut == 'terminée')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Terminée</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($visite->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($visite->resultat == 'favorable')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Favorable</span>
                                        @elseif($visite->resultat == 'defavorable')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Défavorable</span>
                                        @elseif($visite->resultat == 'avec_reserves')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Avec réserves</span>
                                        @else
                                            <span class="text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('visites.show', $visite) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('visites.edit', $visite) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('visites.destroy', $visite) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette visite ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-400">Aucune visite trouvée</p>
                    <p class="text-sm text-gray-400 mt-1">Planifiez la première visite pour ce véhicule</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Interventions Tab -->
    <div id="tab-content-interventions" class="tab-content hidden">
        <div class="content-card p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Interventions du Véhicule</h3>
                <a href="{{ route('interventions.create', ['vehicule_id' => $vehicule->id]) }}" class="btn-primary flex items-center space-x-3 px-4 py-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Intervention</span>
                </a>
            </div>

            @if($vehicule->interventions && $vehicule->interventions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technicien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($vehicule->interventions as $intervention)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $intervention->type_intervention)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Du {{ \Carbon\Carbon::parse($intervention->date_debut)->format('d/m/Y') }}<br>
                                        @if($intervention->date_fin)
                                            Au {{ \Carbon\Carbon::parse($intervention->date_fin)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($intervention->statut == 'planifiée')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Planifiée</span>
                                        @elseif($intervention->statut == 'en_cours')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En cours</span>
                                        @elseif($intervention->statut == 'terminée')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Terminée</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($intervention->statut) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $intervention->technicien ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $intervention->cout ? number_format($intervention->cout, 2) . ' €' : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('interventions.show', $intervention) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('interventions.edit', $intervention) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('interventions.destroy', $intervention) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-tools text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-400">Aucune intervention trouvée</p>
                    <p class="text-sm text-gray-400 mt-1">Planifiez la première intervention pour ce véhicule</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
        content.classList.remove('active');
    });

    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Show selected tab content
    const selectedTabContent = document.getElementById('tab-content-' + tabName);
    if (selectedTabContent) {
        selectedTabContent.classList.remove('hidden');
        selectedTabContent.classList.add('active');
    }

    // Activate selected tab button
    const selectedTabButton = document.getElementById('tab-' + tabName);
    if (selectedTabButton) {
        selectedTabButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        selectedTabButton.classList.remove('border-transparent', 'text-gray-500');
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('vehicle-info');
});
</script>

<style>
.tab-button.active {
    border-bottom-color: #3b82f6;
    color: #2563eb;
}

.tab-content.active {
    display: block;
}

.tab-content {
    display: none;
}
</style>
@endsection 