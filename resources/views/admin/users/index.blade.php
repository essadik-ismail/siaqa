@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">User Management</h1>
            <p class="text-gray-600">Manage users, roles, and permissions across all agencies</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-user-plus mr-2"></i>
            Add New User
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email..." 
                           class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="button" id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ request('search') ? '' : 'hidden' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="searchIndicator" class="hidden mt-1 text-xs text-blue-600">
                    <i class="fas fa-spinner fa-spin mr-1"></i>Searching...
                </div>
                <div id="searchSuggestions" class="hidden mt-1 text-xs text-gray-500">
                    <i class="fas fa-lightbulb mr-1"></i>Try searching by name, email, or ID
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="roleFilter" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('role') !== '' ? 'border-green-300 bg-green-50' : '' }}">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->display_name ?? $role->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('role') !== '')
                <div class="mt-1 text-xs text-green-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ request('status') !== '' ? 'border-purple-300 bg-purple-50' : '' }}">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @if(request('status') !== '')
                <div class="mt-1 text-xs text-purple-600">
                    <i class="fas fa-filter mr-1"></i>Filter active
                </div>
                @endif
            </div>
        </div>
        
        <!-- Active Filters Display -->
        @if(request('search') || request('role') !== '' || request('status') !== '')
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
                @if(request('role') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Role: {{ $roles->firstWhere('name', request('role'))->display_name ?? request('role') }}
                        <button type="button" onclick="clearFilter('role')" class="ml-1 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
                @if(request('status') !== '')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Status: {{ ucfirst(request('status')) }}
                        <button type="button" onclick="clearFilter('status')" class="ml-1 text-purple-600 hover:text-purple-800">
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
        <span id="resultsText">users found</span>
        <span id="searchTerm" class="hidden">for "<span class="font-medium text-blue-600"></span>"</span>
        <span id="roleTerm" class="hidden">with role <span class="font-medium"></span></span>
        <span id="statusTerm" class="hidden">with status <span class="font-medium"></span></span>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 sm:mb-0">Users (<span id="total-users">{{ $users->count() }}</span>)</h3>
                <div class="text-sm text-gray-600" id="pagination-info">
                    Showing <span id="showing-from">1</span> to <span id="showing-to">{{ min(10, $users->count()) }}</span> of <span id="total-results">{{ $users->count() }}</span> results
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->email_verified_at)
                                        <div class="text-sm text-green-600 flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i>Verified
                                        </div>
                                    @else
                                        <div class="text-sm text-yellow-600 flex items-center">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-sm text-gray-500">No role assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->agence)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $user->agence->nom_agence }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">No agency</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 p-1 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.users.permissions', $user) }}" class="text-gray-600 hover:text-gray-900 p-1 rounded" title="Permissions">
                                    <i class="fas fa-key"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }} p-1 rounded" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                @if($user->id !== auth()->id())
                                <button type="button" 
                                        onclick="showDeleteModal('{{ $user->name }}', '{{ route('admin.users.destroy', $user) }}')"
                                        class="text-red-600 hover:text-red-900 p-1 rounded" 
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg">No users found</p>
                                <p class="text-sm">Get started by creating your first user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    <!-- Dynamic no results message for filtering -->
                    <tr id="no-results-row" style="display: none;">
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-search text-4xl mb-4"></i>
                                <p class="text-lg">No users found</p>
                                <p class="text-sm">Try adjusting your search criteria or clear the filters</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Frontend Pagination -->
        <div class="px-6 py-4 border-t border-gray-200" id="pagination-wrapper" style="display: none;">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button id="prev-btn" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Previous
                    </button>
                    <button id="next-btn" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium" id="showing-from-lg">1</span>
                            to
                            <span class="font-medium" id="showing-to-lg">10</span>
                            of
                            <span class="font-medium" id="total-results-lg">0</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button id="prev-btn-lg" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="page-numbers" class="flex">
                                <!-- Page numbers will be generated by JavaScript -->
                            </div>
                            <button id="next-btn-lg" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
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
                    <h3 class="text-lg font-semibold text-gray-900">Delete User</h3>
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
                        <i class="fas fa-user text-gray-600 text-lg"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-gray-700 mb-2">
                        Are you sure you want to delete the user <span class="font-semibold text-gray-900" id="deleteUserName"></span>?
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-red-700">
                                <p class="font-medium">Warning:</p>
                                <p>This will permanently delete the user account and all associated data. This action cannot be reversed.</p>
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
                    Delete User
                </button>
            </form>
        </div>
    </div>
</div>

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const userRows = document.querySelectorAll('.user-row');
    
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
        // Role filter indicator
        const roleIndicator = roleFilter.parentNode.querySelector('.text-green-600');
        if (roleFilter.value) {
            if (!roleIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-green-600';
                indicator.innerHTML = '<i class="fas fa-filter mr-1"></i>Filter active';
                roleFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (roleIndicator) {
                roleIndicator.remove();
            }
        }

        // Status filter indicator
        const statusIndicator = statusFilter.parentNode.querySelector('.text-purple-600');
        if (statusFilter.value) {
            if (!statusIndicator) {
                const indicator = document.createElement('div');
                indicator.className = 'mt-1 text-xs text-purple-600';
                indicator.innerHTML = '<i class="fas fa-filter mr-1"></i>Filter active';
                statusFilter.parentNode.appendChild(indicator);
            }
        } else {
            if (statusIndicator) {
                statusIndicator.remove();
            }
        }
    }

    function updateResultsCounter() {
        const resultsCounter = document.getElementById('resultsCounter');
        const searchValue = searchInput.value.trim();
        const roleValue = roleFilter.value;
        const statusValue = statusFilter.value;

        if (searchValue || roleValue || statusValue) {
            resultsCounter.classList.remove('hidden');
            
            const searchTerm = document.getElementById('searchTerm');
            const roleTerm = document.getElementById('roleTerm');
            const statusTerm = document.getElementById('statusTerm');
            
            if (searchValue) {
                searchTerm.querySelector('span').textContent = searchValue;
                searchTerm.classList.remove('hidden');
            } else {
                searchTerm.classList.add('hidden');
            }
            
            if (roleValue) {
                const roleName = roleFilter.options[roleFilter.selectedIndex].text;
                roleTerm.querySelector('span').textContent = roleName;
                roleTerm.classList.remove('hidden');
            } else {
                roleTerm.classList.add('hidden');
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
    
    // Pagination elements
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const prevBtnLg = document.getElementById('prev-btn-lg');
    const nextBtnLg = document.getElementById('next-btn-lg');
    const pageNumbers = document.getElementById('page-numbers');
    const showingFrom = document.getElementById('showing-from');
    const showingTo = document.getElementById('showing-to');
    const totalResults = document.getElementById('total-results');
    const totalUsers = document.getElementById('total-users');
    const showingFromLg = document.getElementById('showing-from-lg');
    const showingToLg = document.getElementById('showing-to-lg');
    const totalResultsLg = document.getElementById('total-results-lg');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleSelect.value;
        const selectedStatus = statusSelect.value;
        
        filteredUsers = allUserRows.filter(row => {
            // Get user data - column structure: ID, Name, Email, Role, Agency, Status, Created, Actions
            const nameElement = row.querySelector('td:nth-child(2) .text-sm.font-medium');
            const emailElement = row.querySelector('td:nth-child(3)');
            const roleElement = row.querySelector('td:nth-child(4) .inline-flex');
            const statusElement = row.querySelector('td:nth-child(6) .inline-flex');
            
            const name = nameElement ? nameElement.textContent.toLowerCase() : '';
            const email = emailElement ? emailElement.textContent.toLowerCase() : '';
            const role = roleElement ? roleElement.textContent.toLowerCase() : '';
            const status = statusElement ? statusElement.textContent.toLowerCase() : '';
            
            // Check search criteria
            const matchesSearch = searchTerm === '' || 
                name.includes(searchTerm) || 
                email.includes(searchTerm);
            
            // Check role criteria
            let matchesRole = true;
            if (selectedRole !== '') {
                matchesRole = role.includes(selectedRole.toLowerCase());
            }
            
            // Check status criteria
            let matchesStatus = true;
            if (selectedStatus !== '') {
                if (selectedStatus === 'active') {
                    matchesStatus = status.includes('active');
                } else if (selectedStatus === 'inactive') {
                    matchesStatus = status.includes('inactive');
                }
            }
            
            return matchesSearch && matchesRole && matchesStatus;
        });
        
        // Reset to first page when filtering
        currentPage = 1;
        totalPages = Math.ceil(filteredUsers.length / ITEMS_PER_PAGE);
        
        // Show/hide pagination
        if (filteredUsers.length > ITEMS_PER_PAGE) {
            paginationWrapper.style.display = '';
        } else {
            paginationWrapper.style.display = 'none';
        }
        
        // Show/hide no results message
        if (filteredUsers.length === 0) {
            noResultsRow.style.display = '';
        } else {
            noResultsRow.style.display = 'none';
        }
        
        // Update counts
        totalUsers.textContent = filteredUsers.length;
        totalResults.textContent = filteredUsers.length;
        totalResultsLg.textContent = filteredUsers.length;
        
        // Display users for current page
        displayUsers();
    }
    
    function displayUsers() {
        // Hide all users first
        allUserRows.forEach(row => {
            row.style.display = 'none';
        });
        
        // Hide no-results row by default
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
        
        // Show users for current page
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const usersToShow = filteredUsers.slice(startIndex, endIndex);
        
        usersToShow.forEach(row => {
            row.style.display = '';
        });
        
        // Update pagination info
        const startIndexDisplay = filteredUsers.length > 0 ? startIndex + 1 : 0;
        const endIndexDisplay = Math.min(endIndex, filteredUsers.length);
        
        showingFrom.textContent = startIndexDisplay;
        showingTo.textContent = endIndexDisplay;
        showingFromLg.textContent = startIndexDisplay;
        showingToLg.textContent = endIndexDisplay;
        
        // Update pagination
        updatePagination();
    }
    
    function updatePagination() {
        // Update pagination buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
        prevBtnLg.disabled = currentPage === 1;
        nextBtnLg.disabled = currentPage === totalPages;
        
        // Generate page numbers
        pageNumbers.innerHTML = '';
        
        if (totalPages <= 7) {
            // Show all pages if 7 or fewer
            for (let i = 1; i <= totalPages; i++) {
                createPageButton(i);
            }
        } else {
            // Show first page
            createPageButton(1);
            
            if (currentPage > 4) {
                createEllipsis();
            }
            
            // Show pages around current page
            const start = Math.max(2, currentPage - 1);
            const end = Math.min(totalPages - 1, currentPage + 1);
            
            for (let i = start; i <= end; i++) {
                createPageButton(i);
            }
            
            if (currentPage < totalPages - 3) {
                createEllipsis();
            }
            
            // Show last page
            if (totalPages > 1) {
                createPageButton(totalPages);
            }
        }
    }
    
    function createPageButton(pageNum) {
        const button = document.createElement('button');
        button.textContent = pageNum;
        button.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
            pageNum === currentPage
                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
        }`;
        
        button.addEventListener('click', () => {
            currentPage = pageNum;
            displayUsers();
        });
        
        pageNumbers.appendChild(button);
    }
    
    function createEllipsis() {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700';
        pageNumbers.appendChild(ellipsis);
    }
    
    // Event listeners - all work automatically without button clicks
    searchInput.addEventListener('input', debounce(filterUsers, 150));
    searchInput.addEventListener('keyup', debounce(filterUsers, 150));
    roleSelect.addEventListener('change', filterUsers);
    statusSelect.addEventListener('change', filterUsers);
    
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        roleSelect.value = '';
        statusSelect.value = '';
        filterUsers();
    });
    
    // Pagination event listeners
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayUsers();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            displayUsers();
        }
    });
    
    prevBtnLg.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayUsers();
        }
    });
    
    nextBtnLg.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            displayUsers();
        }
    });
    
    // Debounce function for search input
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
    
    // Initial setup
    filterUsers();
});

// Delete Modal Functions
function showDeleteModal(userName, deleteUrl) {
    document.getElementById('deleteUserName').textContent = userName;
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
@endpush
