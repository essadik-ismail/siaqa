@extends('layouts.app')

@section('title', 'Leçons')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Leçons</h1>
                    <p class="mt-2 text-gray-600 text-lg">Gérez les leçons de conduite de votre auto-école</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('lessons.create') }}" class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Programmer une Leçon
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="material-card p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher des leçons..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Statuts</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Programmée</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Cours</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Types</option>
                        <option value="theory" {{ request('type') == 'theory' ? 'selected' : '' }}>Théorique</option>
                        <option value="practical" {{ request('type') == 'practical' ? 'selected' : '' }}>Pratique</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full material-button">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Lessons List -->
        <div class="space-y-4">
            @forelse($lessons as $lesson)
                <div class="material-card p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                                <i class="fas fa-book text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title ?? 'Leçon Sans Titre' }}</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span><i class="fas fa-user mr-1"></i>{{ $lesson->student->name ?? 'Aucun Étudiant' }}</span>
                                    <span><i class="fas fa-chalkboard-teacher mr-1"></i>{{ $lesson->instructor->name ?? 'Aucun Instructeur' }}</span>
                                    <span><i class="fas fa-clock mr-1"></i>{{ $lesson->scheduled_at ? $lesson->scheduled_at->format('d M Y, H:i') : 'Non programmé' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                $lesson->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                ($lesson->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                ($lesson->status == 'cancelled' ? 'bg-red-100 text-red-800' : 
                                'bg-blue-100 text-blue-800')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $lesson->status ?? 'scheduled')) }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('lessons.show', $lesson) }}" 
                                   class="material-button px-3 py-1 text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir
                                </a>
                                <a href="{{ route('lessons.edit', $lesson) }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if($lesson->description)
                        <div class="mt-4 text-sm text-gray-600">
                            {{ $lesson->description }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-24 h-24 icon-container mx-auto mb-4" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                        <i class="fas fa-book text-4xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune leçon trouvée</h3>
                    <p class="text-gray-500 mb-6">Commencez par programmer votre première leçon.</p>
                    <a href="{{ route('lessons.create') }}" 
                       class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Programmer une Leçon
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($lessons->hasPages())
            <div class="mt-8">
                {{ $lessons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection