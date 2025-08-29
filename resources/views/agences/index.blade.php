@extends('layouts.app')

@section('title', 'Agences')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Agences</h1>
        <a href="{{ route('agences.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>Nouvelle Agence
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('agences.index') }}" class="space-y-4">
            <!-- Search Row -->
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par nom, ville, adresse..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-search mr-2"></i>Rechercher
                    </button>
                    <a href="{{ route('agences.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- City Filter -->
                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <select name="ville" id="ville" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les villes</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('ville') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RC Filter -->
                <div>
                    <label for="rc" class="block text-sm font-medium text-gray-700 mb-1">Registre de Commerce</label>
                    <select name="rc" id="rc" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les RC</option>
                        @foreach($rcValues as $rc)
                            <option value="{{ $rc }}" {{ request('rc') == $rc ? 'selected' : '' }}>
                                {{ $rc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <!-- Per Page Filter -->
                <div>
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">Par page</label>
                    <select name="per_page" id="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Agencies Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nom_agence', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Nom Agence
                                @if(request('sort_by') == 'nom_agence')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'ville', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-gray-700">
                                Ville
                                @if(request('sort_by') == 'ville')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($agences as $agence)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $agence->nom_agence }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agence->adresse }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agence->ville }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agence->rc ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agence->patente ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $agence->IF ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $agence->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $agence->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('agences.show', $agence) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('agences.edit', $agence) }}" class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('agences.toggle-status', $agence) }}" class="text-yellow-600 hover:text-yellow-900" title="Changer le statut">
                                    <i class="fas fa-toggle-{{ $agence->is_active ? 'on' : 'off' }}"></i>
                                </a>
                                <form method="POST" action="{{ route('agences.destroy', $agence) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette agence ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Aucune agence trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $agences->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Results Summary -->
    <div class="mt-4 text-sm text-gray-600">
        Affichage de {{ $agences->firstItem() ?? 0 }} à {{ $agences->lastItem() ?? 0 }} sur {{ $agences->total() }} agence(s)
        @if(request('search') || request('ville') || request('rc') || request('status'))
            <span class="ml-2 text-blue-600">
                (Filtres appliqués)
            </span>
        @endif
    </div>
</div>

<script>
// Auto-submit form when filters change
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = ['ville', 'rc', 'status', 'per_page'];
    
    filterSelects.forEach(function(filterName) {
        const select = document.getElementById(filterName);
        if (select) {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
});
</script>
@endsection 