@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Role Management</h1>
            <p class="text-gray-600">Manage user roles and their permissions across all agencies</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-user-shield mr-2"></i>
            Add New Role
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, description..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, description, or display name
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select id="typeFilter" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('type') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Types</option>
                    <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System</option>
                    <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
                @if(request('type') !== '')
                <div class="mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                <select id="tenantFilter" name="tenant" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('tenant') !== '' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="">All Tenants</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('tenant') !== '')
                <div class="mt-1 text-xs text-purple-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('type') !== '' || request('tenant') !== '')
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
                @if(request('type') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Type: {{ ucfirst(request('type')) }}
                        <button type="button" onclick="clearFilter('type')" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('tenant') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Tenant: {{ $tenants->firstWhere('id', request('tenant'))->name ?? 'Unknown' }}
                        <button type="button" onclick="clearFilter('tenant')" class="ml-1 text-purple-600 hover:text-purple-800">
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
        <span id="resultsText">roles found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="typeTerm" class="hidden">with type <span class="font-medium"></span></span>
        <span id="tenantTerm" class="hidden">for tenant <span class="font-medium"></span></span>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Roles ({{ $roles->total() }})</h3>
                <div class="text-sm text-gray-600">
                    Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }} results
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user-shield text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $role->display_name ?: $role->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                    @if($role->description)
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit($role->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role->tenant_id ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $role->tenant_id ? 'Custom' : 'System' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($role->tenant)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $role->tenant->name }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">System-wide</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $role->permissions->count() }}</div>
                            <div class="text-xs text-gray-500">permissions</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $role->users->count() }}</div>
                            <div class="text-xs text-gray-500">users</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $role->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.roles.show', $role) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.roles.permissions', $role) }}" class="text-gray-600 hover:text-gray-900 p-1 rounded" title="Permissions">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="{{ route('admin.roles.users', $role) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded" title="Users">
                                    <i class="fas fa-users"></i>
                                </a>
                                @if($role->name !== 'super_admin' && $role->users->count() == 0)
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-user-shield text-4xl mb-4"></i>
                                <p class="text-lg">No roles found</p>
                                <p class="text-sm">Get started by creating your first role</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($roles->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex justify-center">
                {{ $roles->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const typeFilter = document.getElementById('typeFilter');
    const tenantFilter = document.getElementById('tenantFilter');
    
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
        // Type filter indicator
        const typeIndicator = typeFilter.parentNode.querySelector('.text-green-600');
        if (typeFilter.value) {
            if (!typeIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-green-600';
                indicator.innerHTML = '<i class="fas fa-filter mr-1"></i>Filter active';
                typeFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (typeIndicator) {
                typeIndicator.remove();
            }
        }

        // Tenant filter indicator
        const tenantIndicator = tenantFilter.parentNode.querySelector('.text-purple-600');
        if (tenantFilter.value) {
            if (!tenantIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-purple-600';
                indicator.innerHTML = '<i class="fas fa-filter mr-1"></i>Filter active';
                tenantFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (tenantIndicator) {
                tenantIndicator.remove();
            }
        }
    }

    function updateResultsCounter() {
        const resultsCounter = document.getElementById('resultsCounter');
        const searchValue = searchInput.value.trim();
        const typeValue = typeFilter.value;
        const tenantValue = tenantFilter.value;

        if (searchValue || typeValue || tenantValue) {
            resultsCounter.classList.remove('hidden');
            
            const searchTerm = document.getElementById('searchTerm');
            const typeTerm = document.getElementById('typeTerm');
            const tenantTerm = document.getElementById('tenantTerm');
            
            if (searchValue) {
                searchTerm.querySelector('span').textContent = searchValue;
                searchTerm.classList.remove('hidden');
            } else {
                searchTerm.classList.add('hidden');
            }
            
            if (typeValue) {
                typeTerm.querySelector('span').textContent = typeValue.charAt(0).toUpperCase() + typeValue.slice(1);
                typeTerm.classList.remove('hidden');
            } else {
                typeTerm.classList.add('hidden');
            }
            
            if (tenantValue) {
                const tenantName = tenantFilter.options[tenantFilter.selectedIndex].text;
                tenantTerm.querySelector('span').textContent = tenantName;
                tenantTerm.classList.remove('hidden');
            } else {
                tenantTerm.classList.add('hidden');
            }
        } else {
            resultsCounter.classList.add('hidden');
        }
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        showSearchIndicator();
        updateClearSearchButton();
        debounce(updateResultsCounter, 300)();
    });
    
    searchInput.addEventListener('blur', function() {
        setTimeout(hideSearchIndicator, 200);
    });
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        updateClearSearchButton();
        hideSearchIndicator();
        updateResultsCounter();
    });
    
    typeFilter.addEventListener('change', function() {
        updateFilterIndicators();
        updateResultsCounter();
    });
    
    tenantFilter.addEventListener('change', function() {
        updateFilterIndicators();
        updateResultsCounter();
    });

    // Initialize
    updateClearSearchButton();
    updateFilterIndicators();
    updateResultsCounter();
});

// Global functions for filter clearing
function clearFilter(type) {
    switch(type) {
        case 'search':
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            break;
        case 'type':
            document.getElementById('typeFilter').value = '';
            break;
        case 'tenant':
            document.getElementById('tenantFilter').value = '';
            break;
    }
    
    // Trigger filter update
    const changeEvent = new Event('change');
    const inputEvent = new Event('input');
    
    document.getElementById('typeFilter').dispatchEvent(changeEvent);
    document.getElementById('tenantFilter').dispatchEvent(changeEvent);
    
    if (type === 'search') {
        document.getElementById('searchInput').dispatchEvent(inputEvent);
    }
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('tenantFilter').value = '';
    
    // Trigger all updates
    const changeEvent = new Event('change');
    const inputEvent = new Event('input');
    
    document.getElementById('searchInput').dispatchEvent(inputEvent);
    document.getElementById('typeFilter').dispatchEvent(changeEvent);
    document.getElementById('tenantFilter').dispatchEvent(changeEvent);
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

@push('styles')
<style>
/* Custom pagination styling to match Tailwind */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0 2px;
}

.pagination .page-link {
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #3b82f6;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    text-decoration: none;
}

.pagination .page-link:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.pagination .active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.pagination .disabled .page-link {
    color: #9ca3af;
    pointer-events: none;
    background-color: #fff;
    border-color: #d1d5db;
}
</style>
@endpush
