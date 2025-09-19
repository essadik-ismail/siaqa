@extends('layouts.app')

@section('title', 'Instructeurs')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Instructeurs</h1>
                    <p class="mt-2 text-gray-600 text-lg">Gérez les instructeurs de votre auto-école</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('instructors.create') }}" class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un Instructeur
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
                           placeholder="Rechercher des instructeurs..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>En Congé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                    <select name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les Spécialités</option>
                        <option value="A" {{ request('specialization') == 'A' ? 'selected' : '' }}>Catégorie A (Moto)</option>
                        <option value="B" {{ request('specialization') == 'B' ? 'selected' : '' }}>Catégorie B (Voiture)</option>
                        <option value="C" {{ request('specialization') == 'C' ? 'selected' : '' }}>Catégorie C (Camion)</option>
                        <option value="D" {{ request('specialization') == 'D' ? 'selected' : '' }}>Catégorie D (Bus)</option>
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

        <!-- Instructors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($instructors as $instructor)
                <div class="material-card overflow-hidden">
                    <!-- Instructor Header -->
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="icon-container w-12 h-12" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                                <span class="text-white font-semibold text-lg">{{ substr($instructor->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $instructor->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $instructor->email }}</p>
                                <p class="text-xs text-gray-400">{{ $instructor->employee_id }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                    $instructor->status == 'active' ? 'bg-green-100 text-green-800' : 
                                    ($instructor->status == 'inactive' ? 'bg-red-100 text-red-800' : 
                                    'bg-yellow-100 text-yellow-800') 
                                }}">
                                    {{ ucfirst($instructor->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Instructor Details -->
                    <div class="px-6 pb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Téléphone:</span>
                                <p class="font-medium">{{ $instructor->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Spécialité:</span>
                                <p class="font-medium">{{ $instructor->specialization ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Expérience:</span>
                                <p class="font-medium">{{ $instructor->experience_years ?? 0 }} ans</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Étudiants:</span>
                                <p class="font-medium">{{ $instructor->students_count ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="px-6 pb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Évaluation</span>
                            <span>{{ $instructor->rating ?? 0 }}/5</span>
                        </div>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($instructor->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('instructors.show', $instructor) }}" 
                               class="flex-1 material-button text-center px-3 py-2 text-sm">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                            <a href="{{ route('instructors.edit', $instructor) }}" 
                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('instructors.destroy', $instructor) }}" 
                                  class="flex-1" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet instructeur ?')">
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
                        <div class="w-24 h-24 icon-container mx-auto mb-4" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                            <i class="fas fa-chalkboard-teacher text-4xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun instructeur trouvé</h3>
                        <p class="text-gray-500 mb-6">Commencez par ajouter votre premier instructeur.</p>
                        <a href="{{ route('instructors.create') }}" 
                           class="material-button">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter un Instructeur
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($instructors->hasPages())
            <div class="mt-8">
                {{ $instructors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection