@extends('layouts.app')

@section('title', __('app.agency_management'))

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Agency Management</h1>
            <p class="text-gray-600">Manage rental agencies and their configurations</p>
        </div>
        <a href="{{ route('admin.agencies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-building mr-2"></i>
            Add New Agency
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Agencies</p>
                    <p class="text-2xl font-semibold text-gray-900" id="totalAgencies">{{ $agencies->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Agencies</p>
                    <p class="text-2xl font-semibold text-gray-900" id="activeAgencies">{{ $agencies->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Inactive Agencies</p>
                    <p class="text-2xl font-semibold text-gray-900" id="inactiveAgencies">{{ $agencies->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900" id="totalUsers">{{ $agencies->sum(function($agency) { return $agency->users->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="search" 
                           placeholder="Name, address, city, RC..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
        </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, address, city, or RC number
                </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Agencies</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    </select>
                <div id="statusFilterIndicator" class="hidden mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                </div>
                <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select id="sortBy" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="name">Name</option>
                    <option value="created_at">Date Created</option>
                    <option value="users">Users Count</option>
                    <option value="status">Status</option>
                    </select>
                <div id="sortIndicator" class="hidden mt-1 text-xs text-purple-600">
                    <i class="fas fa-sort mr-1"></i>Custom sorting
                </div>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div id="activeFilters" class="hidden mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <span>Active filters:</span>
                <div id="activeFiltersList"></div>
                <button type="button" id="clearAllFilters" class="text-red-600 hover:text-red-800 text-xs underline">
                    Clear all
                    </button>
                </div>
        </div>
    </div>

    <!-- Search Results Counter -->
    <div id="resultsCounter" class="mb-4 text-sm text-gray-600 hidden">
        <span id="resultsCount" class="font-medium">0</span> 
        <span id="resultsText">agencies found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="statusTerm" class="hidden">with status <span class="font-medium"></span></span>
    </div>

    <!-- Agencies Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Agencies (<span id="agenciesCount">{{ $agencies->count() }}</span>)</h3>
                <div class="text-sm text-gray-600">
                    Showing <span id="showingFrom">1</span> to <span id="showingTo">{{ $agencies->count() }}</span> of <span id="totalResults">{{ $agencies->count() }}</span> results
                </div>
            </div>
        </div>
        
        @if($agencies->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="agenciesTableBody">
                        @foreach($agencies as $agency)
                            <tr class="agency-row hover:bg-gray-50" 
                                data-name="{{ strtolower($agency->nom_agence) }}" 
                                data-address="{{ strtolower($agency->adresse) }}"
                                data-city="{{ strtolower($agency->ville) }}"
                                data-rc="{{ strtolower($agency->rc) }}"
                                data-status="{{ $agency->is_active ? 'active' : 'inactive' }}"
                                data-tenant="{{ $agency->tenant_id }}"
                                data-users="{{ $agency->users->count() }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agency->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($agency->logo)
                                        <img src="{{ $agency->logo_url }}" alt="{{ $agency->nom_agence }}" 
                                             class="h-10 w-10 rounded-lg object-cover border border-gray-200">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-building text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $agency->nom_agence }}</div>
                                    @if($agency->rc)
                                        <div class="text-sm text-gray-500">RC: {{ $agency->rc }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($agency->tenant)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $agency->tenant->name }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">No tenant</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="text-gray-900">{{ $agency->adresse }}</div>
                                <div class="text-gray-500">{{ $agency->ville }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-center">
                                <div class="text-sm font-medium text-gray-900">{{ $agency->users->count() }}</div>
                                <div class="text-xs text-gray-500">users</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $agency->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $agency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $agency->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.agencies.show', $agency) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.agencies.edit', $agency) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.agencies.users', $agency) }}" class="text-gray-600 hover:text-gray-900 p-1 rounded" title="Users">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{ route('admin.agencies.statistics', $agency) }}" class="text-green-600 hover:text-green-900 p-1 rounded" title="Statistics">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.agencies.toggle-status', $agency) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $agency->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }} p-1 rounded" title="{{ $agency->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $agency->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                @if($agency->users->count() == 0)
                                        <button type="button" 
                                                onclick="showDeleteModal('{{ $agency->nom_agence }}', '{{ route('admin.agencies.destroy', $agency) }}')"
                                                class="text-red-600 hover:text-red-900 p-1 rounded" 
                                                title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                        @endforeach
                </tbody>
            </table>
        </div>

            <!-- Frontend Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <label for="itemsPerPage" class="text-sm text-gray-700">Show:</label>
                        <select id="itemsPerPage" class="px-2 py-1 border border-gray-300 rounded text-sm">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-700">per page</span>
                    </div>
                    
                    <div class="flex items-center space-x-2" id="paginationControls">
                        <!-- Pagination buttons will be generated here -->
                    </div>
                </div>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-building text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No agencies found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first agency</p>
                <div class="mt-6">
                    <a href="{{ route('admin.agencies.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-building mr-2"></i>
                        Add First Agency
                    </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform transition-all duration-300 scale-95 opacity-0" id="deleteModalContent">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Delete Agency</h3>
                    <p class="text-sm text-gray-500">This action cannot be undone</p>
                </div>
            </div>
            <button type="button" onclick="hideDeleteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-gray-600 text-lg"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-gray-700 mb-2">
                        Are you sure you want to delete the agency <span class="font-semibold text-gray-900" id="deleteAgencyName"></span>?
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-red-700">
                                <p class="font-medium">Warning:</p>
                                <p>This will permanently delete the agency and all associated data. This action cannot be reversed.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete Agency
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const clearSearchBtn = document.getElementById('clearSearch');
    const statusSelect = document.getElementById('status');
    const sortBySelect = document.getElementById('sortBy');
    const clearAllFiltersBtn = document.getElementById('clearAllFilters');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const agencyRows = document.querySelectorAll('.agency-row');
    
    let currentPage = 1;
    let itemsPerPage = 25;
    let filteredAgencies = Array.from(agencyRows);
    let sortDirection = 'asc';

    function filterAgencies() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusSelect.value.toLowerCase();
        const sortBy = sortBySelect.value;

        filteredAgencies = Array.from(agencyRows).filter(row => {
            const name = row.dataset.name;
            const address = row.dataset.address;
            const city = row.dataset.city;
            const rc = row.dataset.rc;
            const status = row.dataset.status;

            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                address.includes(searchTerm) || 
                city.includes(searchTerm) || 
                rc.includes(searchTerm);
            
            const matchesStatus = !selectedStatus || status === selectedStatus;

            return matchesSearch && matchesStatus;
        });

        // Sort the filtered results
        sortAgencies(sortBy);

        updateStatistics();
        updateActiveFilters();
        updateResultsCounter();
        currentPage = 1;
        displayAgencies();
    }

    function sortAgencies(sortBy) {
        filteredAgencies.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortBy) {
                case 'name':
                    aValue = a.dataset.name;
                    bValue = b.dataset.name;
                    break;
                case 'created_at':
                    aValue = new Date(a.querySelector('td:nth-child(7)').textContent);
                    bValue = new Date(b.querySelector('td:nth-child(7)').textContent);
                    break;
                case 'users':
                    aValue = parseInt(a.dataset.users);
                    bValue = parseInt(b.dataset.users);
                    break;
                case 'status':
                    aValue = a.dataset.status;
                    bValue = b.dataset.status;
                    break;
                default:
                    return 0;
            }
            
            if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
            if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
    }

    function updateActiveFilters() {
        const activeFiltersDiv = document.getElementById('activeFilters');
        const activeFiltersList = document.getElementById('activeFiltersList');
        const searchTerm = searchInput.value.trim();
        const selectedStatus = statusSelect.value;
        const selectedSort = sortBySelect.value;
        
        let hasActiveFilters = false;
        let filtersHTML = '';

        if (searchTerm) {
            hasActiveFilters = true;
            filtersHTML += `
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Search: "${searchTerm}"
                    <button type="button" onclick="clearFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            `;
        }

        if (selectedStatus) {
            hasActiveFilters = true;
            filtersHTML += `
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Status: ${selectedStatus === 'active' ? 'Active' : 'Inactive'}
                    <button type="button" onclick="clearFilter('status')" class="ml-1 text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            `;
        }

        if (selectedSort && selectedSort !== 'name') {
            hasActiveFilters = true;
            filtersHTML += `
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Sort: ${selectedSort.charAt(0).toUpperCase() + selectedSort.slice(1)}
                    <button type="button" onclick="clearFilter('sort')" class="ml-1 text-purple-600 hover:text-purple-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            `;
        }

        activeFiltersList.innerHTML = filtersHTML;
        activeFiltersDiv.classList.toggle('hidden', !hasActiveFilters);
    }

    function updateResultsCounter() {
        const resultsCounter = document.getElementById('resultsCounter');
        const resultsCount = document.getElementById('resultsCount');
        const searchTerm = document.getElementById('searchTerm');
        const statusTerm = document.getElementById('statusTerm');
        const searchValue = searchInput.value.trim();
        const statusValue = statusSelect.value;

        resultsCount.textContent = filteredAgencies.length;
        
        if (searchValue || statusValue) {
            resultsCounter.classList.remove('hidden');
            
            if (searchValue) {
                searchTerm.querySelector('span').textContent = searchValue;
                searchTerm.classList.remove('hidden');
            } else {
                searchTerm.classList.add('hidden');
            }
            
            if (statusValue) {
                statusTerm.querySelector('span').textContent = statusValue === 'active' ? 'Active' : 'Inactive';
                statusTerm.classList.remove('hidden');
            } else {
                statusTerm.classList.add('hidden');
            }
        } else {
            resultsCounter.classList.add('hidden');
        }
    }

    function updateStatistics() {
        const totalAgencies = filteredAgencies.length;
        const activeAgencies = filteredAgencies.filter(row => row.dataset.status === 'active').length;
        const inactiveAgencies = filteredAgencies.filter(row => row.dataset.status === 'inactive').length;
        const totalUsers = filteredAgencies.reduce((sum, row) => sum + parseInt(row.dataset.users), 0);

        document.getElementById('totalAgencies').textContent = totalAgencies;
        document.getElementById('activeAgencies').textContent = activeAgencies;
        document.getElementById('inactiveAgencies').textContent = inactiveAgencies;
        document.getElementById('totalUsers').textContent = totalUsers;
        document.getElementById('agenciesCount').textContent = totalAgencies;
    }

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
        const statusFilterIndicator = document.getElementById('statusFilterIndicator');
        if (statusSelect.value) {
            statusFilterIndicator.classList.remove('hidden');
        } else {
            statusFilterIndicator.classList.add('hidden');
        }

        // Sort indicator
        const sortIndicator = document.getElementById('sortIndicator');
        if (sortBySelect.value && sortBySelect.value !== 'name') {
            sortIndicator.classList.remove('hidden');
        } else {
            sortIndicator.classList.add('hidden');
        }
    }

    function displayAgencies() {
        // Hide all rows first
        agencyRows.forEach(row => {
            row.style.display = 'none';
        });

        // Calculate pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedAgencies = filteredAgencies.slice(startIndex, endIndex);

        // Show filtered and paginated rows
        paginatedAgencies.forEach(agency => {
            agency.style.display = '';
        });

        // Update pagination info
        const totalResults = filteredAgencies.length;
        const showingFrom = totalResults > 0 ? startIndex + 1 : 0;
        const showingTo = Math.min(endIndex, totalResults);

        document.getElementById('showingFrom').textContent = showingFrom;
        document.getElementById('showingTo').textContent = showingTo;
        document.getElementById('totalResults').textContent = totalResults;

        // Generate pagination controls
        generatePaginationControls(totalResults);
    }

    function generatePaginationControls(totalResults) {
        const totalPages = Math.ceil(totalResults / itemsPerPage);
        const paginationControls = document.getElementById('paginationControls');
        
        if (totalPages <= 1) {
            paginationControls.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Previous button
        paginationHTML += `
            <button onclick="goToPage(${currentPage - 1})" 
                    ${currentPage === 1 ? 'disabled' : ''} 
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;

        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) {
            paginationHTML += `<button onclick="goToPage(1)" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border-t border-b border-gray-300 hover:bg-gray-50">1</button>`;
            if (startPage > 2) {
                paginationHTML += `<span class="px-3 py-2 text-sm text-gray-500">...</span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button onclick="goToPage(${i})" 
                        class="px-3 py-2 text-sm font-medium ${i === currentPage ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-500 bg-white hover:bg-gray-50'} border-t border-b border-gray-300">
                    ${i}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHTML += `<span class="px-3 py-2 text-sm text-gray-500">...</span>`;
            }
            paginationHTML += `<button onclick="goToPage(${totalPages})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border-t border-b border-gray-300 hover:bg-gray-50">${totalPages}</button>`;
        }

        // Next button
        paginationHTML += `
            <button onclick="goToPage(${currentPage + 1})" 
                    ${currentPage === totalPages ? 'disabled' : ''} 
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;

        paginationControls.innerHTML = paginationHTML;
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        showSearchIndicator();
        updateClearSearchButton();
        debounce(filterAgencies, 300)();
    });
    
    searchInput.addEventListener('blur', function() {
        setTimeout(hideSearchIndicator, 200);
    });
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        updateClearSearchButton();
        hideSearchIndicator();
        filterAgencies();
    });
    
    statusSelect.addEventListener('change', function() {
        updateFilterIndicators();
        filterAgencies();
    });
    
    sortBySelect.addEventListener('change', function() {
        updateFilterIndicators();
        filterAgencies();
    });
    
    itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        displayAgencies();
    });
    
    clearAllFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusSelect.value = '';
        sortBySelect.value = 'name';
        updateClearSearchButton();
        updateFilterIndicators();
        filterAgencies();
    });

    // Initialize
    updateClearSearchButton();
    updateFilterIndicators();
    displayAgencies();
});

// Global functions for filter clearing
function clearFilter(type) {
    switch(type) {
        case 'search':
            document.getElementById('search').value = '';
            document.getElementById('clearSearch').classList.add('hidden');
            break;
        case 'status':
            document.getElementById('status').value = '';
            break;
        case 'sort':
            document.getElementById('sortBy').value = 'name';
            break;
    }
    
    // Trigger filter update
    const event = new Event('change');
    document.getElementById('status').dispatchEvent(event);
    document.getElementById('sortBy').dispatchEvent(event);
    
    if (type === 'search') {
        const inputEvent = new Event('input');
        document.getElementById('search').dispatchEvent(inputEvent);
    }
}

function clearAllFilters() {
    document.getElementById('search').value = '';
    document.getElementById('status').value = '';
    document.getElementById('sortBy').value = 'name';
    
    // Trigger all updates
    const changeEvent = new Event('change');
    const inputEvent = new Event('input');
    
    document.getElementById('search').dispatchEvent(inputEvent);
    document.getElementById('status').dispatchEvent(changeEvent);
    document.getElementById('sortBy').dispatchEvent(changeEvent);
}

function goToPage(page) {
    const totalPages = Math.ceil(filteredAgencies.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        displayAgencies();
    }
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

// Delete Modal Functions
function showDeleteModal(agencyName, deleteUrl) {
    document.getElementById('deleteAgencyName').textContent = agencyName;
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        const modalContent = document.getElementById('deleteModalContent');
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideDeleteModal() {
    const modalContent = document.getElementById('deleteModalContent');
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        document.getElementById('deleteModal').classList.add('hidden');
    }, 300);
}

// Close delete modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal && !deleteModal.classList.contains('hidden')) {
            hideDeleteModal();
        }
    }
});
</script>
@endsection