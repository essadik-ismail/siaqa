@extends('layouts.app')

@section('title', 'Étudiants')

@section('content')
<div class="min-h-screen">
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold gradient-text">Étudiants</h1>
                    <p class="mt-2 text-gray-600 text-lg">Gérez les étudiants de votre auto-école</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('students.create') }}" class="material-button">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un Étudiant
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
                           placeholder="Rechercher des étudiants..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les Statuts</option>
                        <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>Inscrit</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Diplômé</option>
                        <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Abandonné</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie de Permis</label>
                    <select name="license_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les Catégories</option>
                        <option value="A" {{ request('license_category') == 'A' ? 'selected' : '' }}>Catégorie A</option>
                        <option value="B" {{ request('license_category') == 'B' ? 'selected' : '' }}>Catégorie B</option>
                        <option value="C" {{ request('license_category') == 'C' ? 'selected' : '' }}>Catégorie C</option>
                        <option value="D" {{ request('license_category') == 'D' ? 'selected' : '' }}>Catégorie D</option>
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

        <!-- Students Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($students as $student)
                <div class="material-card overflow-hidden">
                    <!-- Student Header -->
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="icon-container w-12 h-12">
                                <span class="text-white font-semibold text-lg">{{ substr($student->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $student->email }}</p>
                                <p class="text-xs text-gray-400">{{ $student->student_number }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                    $student->status == 'active' ? 'bg-green-100 text-green-800' : 
                                    ($student->status == 'graduated' ? 'bg-blue-100 text-blue-800' : 
                                    ($student->status == 'suspended' ? 'bg-red-100 text-red-800' : 
                                    'bg-gray-100 text-gray-800')) 
                                }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Student Details -->
                    <div class="px-6 pb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Téléphone:</span>
                                <p class="font-medium">{{ $student->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Permis:</span>
                                <p class="font-medium">{{ $student->license_category ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Heures Théoriques:</span>
                                <p class="font-medium">{{ $student->theory_hours_completed ?? 0 }}/{{ $student->required_theory_hours ?? 0 }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Heures Pratiques:</span>
                                <p class="font-medium">{{ $student->practical_hours_completed ?? 0 }}/{{ $student->required_practical_hours ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="px-6 pb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Progrès</span>
                            <span>{{ $student->theory_completion_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" 
                                 style="width: {{ $student->theory_completion_percentage ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('students.show', $student) }}" 
                               class="flex-1 material-button text-center px-3 py-2 text-sm">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                            <a href="{{ route('students.edit', $student) }}" 
                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" 
                                  class="flex-1" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
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
                            <i class="fas fa-user-graduate text-4xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun étudiant trouvé</h3>
                        <p class="text-gray-500 mb-6">Commencez par ajouter votre premier étudiant.</p>
                        <a href="{{ route('students.create') }}" 
                           class="material-button">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter un Étudiant
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="mt-8">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection