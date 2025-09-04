@extends('layouts.app')

@section('title', 'Détails de l\'Assurance')

@section('content')
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Détails de l'Assurance</h2>
            <p class="text-gray-600 text-lg">{{ $assurance->numero_assurance }} - {{ $assurance->numero_police }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('assurances.edit', $assurance) }}" class="btn-primary flex items-center space-x-3 px-6 py-3">
                <i class="fas fa-edit"></i>
                <span>Modifier</span>
            </a>
            <a href="{{ route('assurances.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-3 transition-all duration-200 hover:scale-105">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>

    <!-- Insurance Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <div class="content-card p-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Informations de l'Assurance</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Numéro d'assurance</label>
                        <p class="text-lg font-medium text-gray-900">{{ $assurance->numero_assurance }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Numéro de police</label>
                        <p class="text-lg font-medium text-gray-900">{{ $assurance->numero_police }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Prix</label>
                        <p class="text-lg font-medium text-gray-900">{{ number_format($assurance->prix, 2) }} €</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Période</label>
                        <p class="text-lg font-medium text-gray-900">{{ $assurance->periode ?? 'Non spécifié' }}</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">Dates importantes</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Date</label>
                            <p class="text-lg font-medium text-gray-900">{{ $assurance->date->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Date prochaine</label>
                            <p class="text-lg font-medium text-gray-900">{{ $assurance->date_prochaine->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Date de règlement</label>
                            <p class="text-lg font-medium text-gray-900">{{ $assurance->date_reglement->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Files -->
                @if($assurance->hasFiles())
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">Fichiers</h4>
                    <div class="space-y-2">
                        @foreach($assurance->file_urls as $url)
                            <a href="{{ $url }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file mr-2"></i>
                                {{ basename($url) }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Description -->
                @if($assurance->description)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-800 mb-4">Description</h4>
                    <p class="text-gray-700">{{ $assurance->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Vehicle Information -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Véhicule assuré</h4>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-car text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $assurance->vehicule->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $assurance->vehicule->immatriculation ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $assurance->vehicule->marque->marque ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($assurance->vehicule)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('vehicules.show', $assurance->vehicule) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-eye mr-2"></i>Voir le véhicule
                    </a>
                </div>
                @endif
            </div>

            <!-- Status Information -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Statut de l'assurance</h4>
                
                @php
                    $daysUntilExpiry = $assurance->date_prochaine->diffInDays(now(), false);
                @endphp
                
                <div class="space-y-3">
                    @if($daysUntilExpiry > 30)
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="text-sm">Valide pour {{ $daysUntilExpiry }} jours</span>
                        </div>
                    @elseif($daysUntilExpiry > 0)
                        <div class="flex items-center text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="text-sm">Expire dans {{ $daysUntilExpiry }} jours</span>
                        </div>
                    @else
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span class="text-sm">Expirée depuis {{ abs($daysUntilExpiry) }} jours</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        <span class="text-sm">Créée le {{ $assurance->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    @if($assurance->updated_at != $assurance->created_at)
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-edit mr-2"></i>
                        <span class="text-sm">Modifiée le {{ $assurance->updated_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card p-6">
                <h4 class="text-lg font-medium text-gray-800 mb-4">Actions rapides</h4>
                <div class="space-y-3">
                    <a href="{{ route('assurances.edit', $assurance) }}" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    
                    <form method="POST" action="{{ route('assurances.destroy', $assurance) }}" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assurance ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
