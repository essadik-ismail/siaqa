@extends('layouts.app')

@section('title', 'Détails de la Leçon')

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
                    <h1 class="text-4xl font-bold gradient-text mb-4">Détails de la Leçon</h1>
                    <p class="text-gray-600 text-lg">Informations complètes sur la leçon</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('lessons.edit', $lesson) }}" 
                        class="material-button px-6 py-3">
                        Modifier
                    </a>
                    <a href="{{ route('lessons.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Lesson Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type de Leçon</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                @switch($lesson->lesson_type)
                                    @case('theory')
                                        Théorique
                                        @break
                                    @case('practical')
                                        Pratique
                                        @break
                                    @case('simulation')
                                        Simulation
                                        @break
                                    @default
                                        {{ $lesson->lesson_type }}
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date et Heure</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->scheduled_at ? $lesson->scheduled_at->format('d/m/Y à H:i') : 'Non programmé' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Durée</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->duration }} minutes
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Statut</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @switch($lesson->status)
                                    @case('scheduled')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('in_progress')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('completed')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('cancelled')
                                        bg-gray-100 text-gray-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                @switch($lesson->status)
                                    @case('scheduled')
                                        Programmé
                                        @break
                                    @case('in_progress')
                                        En Cours
                                        @break
                                    @case('completed')
                                        Terminé
                                        @break
                                    @case('cancelled')
                                        Annulé
                                        @break
                                    @default
                                        {{ $lesson->status }}
                                @endswitch
                            </span>
                        </div>
                        @if($lesson->started_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Heure de Début</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->started_at->format('H:i') }}
                            </p>
                        </div>
                        @endif
                        @if($lesson->completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Heure de Fin</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->completed_at->format('H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($lesson->description)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Description</h3>
                    <p class="text-gray-700">{{ $lesson->description }}</p>
                </div>
                @endif

                <!-- Notes -->
                @if($lesson->notes)
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Notes</h3>
                    <p class="text-gray-700">{{ $lesson->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Student Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Étudiant</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->student->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        @if($lesson->student)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->student->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Instructor Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Instructeur</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nom</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->instructor->user->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        @if($lesson->instructor)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->instructor->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Expérience</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->instructor->years_experience }} ans</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Vehicle Information -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Véhicule</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Véhicule</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $lesson->vehicle ? $lesson->vehicle->marque . ' ' . $lesson->vehicle->modele : 'Non assigné' }}
                            </p>
                        </div>
                        @if($lesson->vehicle)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Plaque d'Immatriculation</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->vehicle->plaque }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Année</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $lesson->vehicle->annee }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="material-card p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($lesson->status === 'scheduled')
                        <form action="{{ route('lessons.start', $lesson) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors">
                                Commencer la Leçon
                            </button>
                        </form>
                        @endif

                        @if($lesson->status === 'in_progress')
                        <form action="{{ route('lessons.complete', $lesson) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                Terminer la Leçon
                            </button>
                        </form>
                        @endif

                        @if(in_array($lesson->status, ['scheduled', 'in_progress']))
                        <form action="{{ route('lessons.cancel', $lesson) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                                Annuler la Leçon
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" class="w-full"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection