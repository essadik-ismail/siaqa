@extends('layouts.app')

@section('title', 'Tenant Car Selection Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tenant Car Selection Management</h1>
            <p class="text-gray-600">Manage which cars each tenant displays on their landing page</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, domain, company..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, domain, or company
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('status') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Tenants</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @if(request('status') !== '')
                <div class="mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sortFilter" name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('sort_by') && request('sort_by') !== 'name' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="domain" {{ request('sort_by') === 'domain' ? 'selected' : '' }}>Domain</option>
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="agencies" {{ request('sort_by') === 'agencies' ? 'selected' : '' }}>Agencies Count</option>
                    <option value="users" {{ request('sort_by') === 'users' ? 'selected' : '' }}>Users Count</option>
                </select>
                @if(request('sort_by') && request('sort_by') !== 'name')
                <div class="mt-1 text-xs text-purple-600">
                    <i class="fas fa-sort mr-1"></i>Custom sorting
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('status') !== '' || (request('sort_by') && request('sort_by') !== 'name'))
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <span>Active filters:</span>
                @if(request('search'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Search: "{{ request('search') }}"
                        <button type="button" onclick="clearFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('status') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Status: {{ ucfirst(request('status')) }}
                        <button type="button" onclick="clearFilter('status')" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('sort_by') && request('sort_by') !== 'name')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                        <button type="button" onclick="clearFilter('sort')" class="ml-1 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                <button type="button" onclick="clearAllFilters()" class="text-red-600 hover:text-red-800 text-xs underline">
                    Clear all
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Search Results Counter -->
    <div id="resultsCounter" class="mb-4 text-sm text-gray-600 hidden">
        <span id="resultsCount" class="font-medium">0</span> 
        <span id="resultsText">tenants found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="statusTerm" class="hidden">with status <span class="font-medium"></span></span>
    </div>

    <!-- Tenants Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tenants as $tenant)
        <div class="tenant-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" 
             data-name="{{ strtolower($tenant->name) }}" 
             data-domain="{{ strtolower($tenant->domain) }}" 
             data-company="{{ strtolower($tenant->company_name ?? '') }}" 
             data-status="{{ $tenant->is_active ? 'active' : 'inactive' }}" 
             data-agencies="{{ $tenant->agences->count() }}" 
             data-users="{{ $tenant->users->count() }}" 
             data-created="{{ $tenant->created_at->format('Y-m-d') }}">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-server text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tenant->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $tenant->domain }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Agencies:</span>
                        <span class="font-medium text-gray-900">{{ $tenant->agences->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Users:</span>
                        <span class="font-medium text-gray-900">{{ $tenant->users->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subscription:</span>
                        <span class="font-medium text-gray-900 capitalize">{{ $tenant->subscription_plan ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.car-selection.show', $tenant) }}" 
                       class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center text-sm font-medium">
                        <i class="fas fa-car mr-2"></i>
                        Manage Cars
                    </a>
                    <a href="{{ route('admin.agencies.index') }}?tenant={{ $tenant->id }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                        <i class="fas fa-building"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <div class="text-gray-500">
                    <i class="fas fa-server text-4xl mb-4"></i>
                    <p class="text-lg">No tenants found</p>
                    <p class="text-sm">Create a tenant to start managing car selections</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const tenantCards = document.querySelectorAll('.tenant-card');
    
    function showSearchIndicator() {
        document.getElementById('searchIndicator').classList.remove('hidden');
        document.getElementById('searchSuggestions').classList.add('hidden');
    }

    function hideSearchIndicator() {
        document.getElementById('searchIndicator').classList.add('hidden');
        if (searchInput.value.trim() === '') {
            document.getElementById('searchSuggestions').classList.remove('hidden');
        }
    }

    function updateClearSearchButton() {
        if (searchInput.value.trim()) {
            clearSearchBtn.classList.remove('hidden');
        } else {
            clearSearchBtn.classList.add('hidden');
        }
    }

    function updateFilterIndicators() {
        // Status filter indicator
        const statusIndicator = statusFilter.parentNode.querySelector('.text-green-600');
        if (statusFilter.value) {
            if (!statusIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-green-600';
                indicator.innerHTML = '<i class="fas fa-filter mr-1"></i>Filter active';
                statusFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (statusIndicator) {
                statusIndicator.remove();
            }
        }

        // Sort filter indicator
        const sortIndicator = sortFilter.parentNode.querySelector('.text-purple-600');
        if (sortFilter.value && sortFilter.value !== 'name') {
            if (!sortIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-purple-600';
                indicator.innerHTML = '<i class="fas fa-sort mr-1"></i>Custom sorting';
                sortFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (sortIndicator) {
                sortIndicator.remove();
            }
        }
    }

    function updateResultsCounter() {
        const resultsCounter = document.getElementById('resultsCounter');
        const searchValue = searchInput.value.trim();
        const statusValue = statusFilter.value;
        const sortValue = sortFilter.value;

        if (searchValue || statusValue || (sortValue && sortValue !== 'name')) {
            resultsCounter.classList.remove('hidden');
            
            const searchTerm = document.getElementById('searchTerm');
            const statusTerm = document.getElementById('statusTerm');
            
            if (searchValue) {
                searchTerm.querySelector('span').textContent = searchValue;
                searchTerm.classList.remove('hidden');
            } else {
                searchTerm.classList.add('hidden');
            }
            
            if (statusValue) {
                statusTerm.querySelector('span').textContent = statusValue.charAt(0).toUpperCase() + statusValue.slice(1);
                statusTerm.classList.remove('hidden');
            } else {
                statusTerm.classList.add('hidden');
            }
        } else {
            resultsCounter.classList.add('hidden');
        }
    }

    function filterTenants() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        const sortBy = sortFilter.value;

        let filteredCards = Array.from(tenantCards).filter(card => {
            const name = card.dataset.name;
            const domain = card.dataset.domain;
            const company = card.dataset.company;
            const status = card.dataset.status;

            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                domain.includes(searchTerm) || 
                company.includes(searchTerm);
            
            const matchesStatus = !selectedStatus || status === selectedStatus;

            return matchesSearch && matchesStatus;
        });

        // Sort the filtered results
        if (sortBy && sortBy !== 'name') {
            filteredCards.sort((a, b) => {
                let aValue, bValue;
                
                switch(sortBy) {
                    case 'domain':
                        aValue = a.dataset.domain;
                        bValue = b.dataset.domain;
                        break;
                    case 'created_at':
                        aValue = new Date(a.dataset.created);
                        bValue = new Date(b.dataset.created);
                        break;
                    case 'agencies':
                        aValue = parseInt(a.dataset.agencies);
                        bValue = parseInt(b.dataset.agencies);
                        break;
                    case 'users':
                        aValue = parseInt(a.dataset.users);
                        bValue = parseInt(b.dataset.users);
                        break;
                    default:
                        return 0;
                }
                
                if (aValue < bValue) return -1;
                if (aValue > bValue) return 1;
                return 0;
            });
        }

        // Hide all cards first
        tenantCards.forEach(card => {
            card.style.display = 'none';
        });

        // Show filtered cards
        filteredCards.forEach(card => {
            card.style.display = '';
        });

        // Update results counter
        const resultsCount = document.getElementById('resultsCount');
        const resultsText = document.getElementById('resultsText');
        resultsCount.textContent = filteredCards.length;
        resultsText.textContent = filteredCards.length === 1 ? 'tenant found' : 'tenants found';

        updateResultsCounter();
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        showSearchIndicator();
        updateClearSearchButton();
        debounce(filterTenants, 300)();
    });
    
    searchInput.addEventListener('blur', function() {
        setTimeout(hideSearchIndicator, 200);
    });
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        updateClearSearchButton();
        hideSearchIndicator();
        filterTenants();
    });
    
    statusFilter.addEventListener('change', function() {
        updateFilterIndicators();
        filterTenants();
    });
    
    sortFilter.addEventListener('change', function() {
        updateFilterIndicators();
        filterTenants();
    });

    // Initialize
    updateClearSearchButton();
    updateFilterIndicators();
    filterTenants();
});

// Global functions for filter clearing
function clearFilter(type) {
    switch(type) {
        case 'search':
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            break;
        case 'status':
            document.getElementById('statusFilter').value = '';
            break;
        case 'sort':
            document.getElementById('sortFilter').value = 'name';
            break;
    }
    
    // Trigger filter update
    const changeEvent = new Event('change');
    const inputEvent = new Event('input');
    
    document.getElementById('statusFilter').dispatchEvent(changeEvent);
    document.getElementById('sortFilter').dispatchEvent(changeEvent);
    
    if (type === 'search') {
        document.getElementById('searchInput').dispatchEvent(inputEvent);
    }
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'name';
    
    // Trigger all updates
    const changeEvent = new Event('change');
    const inputEvent = new Event('input');
    
    document.getElementById('searchInput').dispatchEvent(inputEvent);
    document.getElementById('statusFilter').dispatchEvent(changeEvent);
    document.getElementById('sortFilter').dispatchEvent(changeEvent);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endsection
