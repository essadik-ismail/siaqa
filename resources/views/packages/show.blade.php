@extends('layouts.app')

@section('title', 'Détails du Forfait')

@section('content')
<div class="min-h-screen">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold gradient-text mb-4">Détails du Forfait</h1>
                    <p class="text-gray-600 text-lg">Informations complètes sur le forfait</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('packages.edit', $package) }}" 
                        class="material-button px-6 py-3">
                        Modifier
                    </a>
                    <a href="{{ route('packages.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Package Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations du Forfait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom du Forfait</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $package->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Catégorie de Permis</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                Catégorie {{ $package->license_category }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Prix</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($package->price, 2) }} DH
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Validité</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->validity_days ? $package->validity_days . ' jours' : 'Illimitée' }}
                            </p>
                        </div>
                        @if($package->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-gray-700">{{ $package->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Package Contents -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Contenu du Forfait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Heures Théoriques</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->theory_hours ?? 0 }} heures
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Heures Pratiques</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->practical_hours ?? 0 }} heures
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Examens Inclus</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->exams_included ?? 0 }} examens
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nombre Maximum d'Étudiants</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->max_students ?? 'Illimité' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if($package->features)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Caractéristiques</h3>
                    <div class="space-y-2">
                        @foreach(explode("\n", $package->features) as $feature)
                            @if(trim($feature))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ trim($feature) }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Status Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Statut du Forfait</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $package->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Forfait Populaire</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $package->is_popular ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $package->is_popular ? 'Oui' : 'Non' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de Création</label>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $package->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dernière Mise à Jour</label>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $package->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Étudiants Inscrits</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $package->studentPackages->count() }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Revenus Générés</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ number_format($package->studentPackages->sum('price'), 2) }} DH
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('packages.edit', $package) }}" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-center block">
                            Modifier le Forfait
                        </a>
                        
                        <a href="{{ route('packages.index') }}" 
                            class="w-full px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors text-center block">
                            Voir Tous les Forfaits
                        </a>

                        <form action="{{ route('packages.destroy', $package) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce forfait ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Students -->
                @if($package->studentPackages->count() > 0)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Étudiants Récents</h3>
                    <div class="space-y-3">
                        @foreach($package->studentPackages->take(5) as $studentPackage)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $studentPackage->student->name ?? 'Étudiant supprimé' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $studentPackage->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-green-600">
                                {{ number_format($studentPackage->price, 2) }} DH
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection