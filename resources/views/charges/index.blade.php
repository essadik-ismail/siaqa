@extends('layouts.app')

@section('title', 'Gestion des Charges')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Charges</h1>
                <p class="text-gray-600">Suivi des dépenses et charges de la flotte</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('charges.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Charge</span>
                </a>
                <a href="{{ route('charges.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium flex items-center space-x-2">
                    <i class="fas fa-download"></i>
                    <span>Exporter</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('charges.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Désignation, description...">
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Par page</label>
                        <select id="per_page" name="per_page" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                            <option value="30" {{ request('per_page') == '30' ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-search mr-2"></i>Rechercher
                    </button>
                    <a href="{{ route('charges.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-undo mr-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        @if(request()->hasAny(['search', 'type', 'date_debut', 'date_fin']))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <span class="text-blue-800 font-medium">Filtres actifs :</span>
                </div>
                <span class="text-blue-600 text-sm">{{ $charges->total() }} résultat(s)</span>
            </div>
        </div>
        @endif

        <!-- Charges Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'designation', 'sort_order' => request('sort_by') == 'designation' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="group flex items-center space-x-1 hover:text-gray-700">
                                    <span>Désignation</span>
                                    @if(request('sort_by') == 'designation')
                                        <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_order' => request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="group flex items-center space-x-1 hover:text-gray-700">
                                    <span>Date</span>
                                    @if(request('sort_by') == 'date')
                                        <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'montant', 'sort_order' => request('sort_by') == 'montant' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="group flex items-center space-x-1 hover:text-gray-700">
                                    <span>Montant</span>
                                    @if(request('sort_by') == 'montant')
                                        <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                    @else
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($charges as $charge)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $charge->designation }}</div>
                                @if($charge->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($charge->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $charge->date ? $charge->date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($charge->montant, 2) }} €</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('charges.show', $charge) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('charges.edit', $charge) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Aucune charge trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($charges->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $charges->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-submit form when filter select boxes change
document.querySelectorAll('select[name="type"], select[name="per_page"]').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endsection
