@extends('layouts.app')

@section('title', __('app.global_users'))

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('saas.global-users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Add New User
        </a>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       id="search" 
                       placeholder="Search by name, email..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select id="role" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="user">User</option>
                    <option value="consultant">Consultant</option>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="button" id="clearFilters" class="btn btn-secondary w-full">
                    <i class="fas fa-times mr-2"></i>
                    Clear
                </button>
            </div>
        </div>
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tenant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Login
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->tenant)
                                <div class="text-sm text-gray-900">{{ $user->tenant->company_name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->tenant->domain }}</div>
                            @else
                                <span class="text-gray-400">No tenant</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $role->name === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                           ($role->name === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400">No role</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('saas.global-users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900"
                                   title="View User Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('saas.global-users.edit', $user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->is_active && $user->tenant)
                                <form action="{{ route('saas.global-users.launch', $user) }}" 
                                      method="POST" 
                                      target="_blank"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900"
                                            title="Launch as User (Opens in private window)"
                                            onclick="return confirm('Are you sure you want to launch as {{ $user->name }}? This will open in a new window.')">
                                        <i class="fas fa-rocket"></i>
                                    </button>
                                </form>
                                @endif
                                <button type="button" 
                                        onclick="showToggleStatusModal('{{ $user->name }}', '{{ $user->is_active ? 'deactivate' : 'activate' }}', '{{ route('saas.global-users.toggle-status', $user) }}')"
                                            class="text-{{ $user->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $user->is_active ? 'yellow' : 'green' }}-900"
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                        <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                <button type="button" 
                                        onclick="showDeleteModal('{{ $user->name }}', '{{ route('saas.global-users.destroy', $user) }}')"
                                            class="text-red-600 hover:text-red-900"
                                        title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResultsRow">
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="text-sm">Try adjusting your search criteria or add a new user.</p>
                                <a href="{{ route('saas.global-users.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    <i class="fas fa-plus mr-2"></i>Add New User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Frontend Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6" id="pagination-wrapper" style="display: none;">
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
                            <span class="font-medium" id="showing-from">1</span>
                            to
                            <span class="font-medium" id="showing-to">10</span>
                            of
                            <span class="font-medium" id="total-results">0</span>
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

    <!-- Toggle Status Confirmation Modal -->
    <div id="toggleStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Confirmer le changement de statut</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Êtes-vous sûr de vouloir <span id="toggleStatusAction"></span> l'utilisateur "<span id="toggleStatusUserName"></span>" ?
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="toggleStatusForm" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Confirmer
                        </button>
                        <button type="button" onclick="hideToggleStatusModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Frontend Filtering, Search and Pagination
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const roleSelect = document.getElementById('role');
    const statusSelect = document.getElementById('status');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const tableBody = document.querySelector('tbody');
    const noResultsRow = document.getElementById('noResultsRow');
    const paginationWrapper = document.getElementById('pagination-wrapper');
    
    // Store all user rows for filtering
    const allUserRows = Array.from(tableBody.querySelectorAll('tr')).filter(row => row.id !== 'noResultsRow');
    let filteredUsers = [...allUserRows];
    let currentPage = 1;
    const ITEMS_PER_PAGE = 10;
    let totalPages = Math.ceil(allUserRows.length / ITEMS_PER_PAGE);
    
    // Pagination elements
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const prevBtnLg = document.getElementById('prev-btn-lg');
    const nextBtnLg = document.getElementById('next-btn-lg');
    const pageNumbers = document.getElementById('page-numbers');
    const showingFrom = document.getElementById('showing-from');
    const showingTo = document.getElementById('showing-to');
    const totalResults = document.getElementById('total-results');
    
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleSelect.value;
        const selectedStatus = statusSelect.value;
        
        filteredUsers = allUserRows.filter(row => {
            // Get user data - updated selectors to match actual HTML structure
            // Column 1: ID, Column 2: User (name + email), Column 3: Tenant, Column 4: Role, Column 5: Status
            const nameElement = row.querySelector('td:nth-child(2) .text-sm.font-medium');
            const emailElement = row.querySelector('td:nth-child(2) .text-sm.text-gray-500');
            const tenantElement = row.querySelector('td:nth-child(3) .text-sm.text-gray-900');
            const roleElement = row.querySelector('td:nth-child(4) .inline-flex');
            const statusElement = row.querySelector('td:nth-child(5) .inline-flex');
            
            const name = nameElement ? nameElement.textContent.toLowerCase() : '';
            const email = emailElement ? emailElement.textContent.toLowerCase() : '';
            const tenant = tenantElement ? tenantElement.textContent.toLowerCase() : '';
            const role = roleElement ? roleElement.textContent.toLowerCase() : '';
            const status = statusElement ? statusElement.textContent.toLowerCase() : '';
            
            // Check search criteria
            const matchesSearch = searchTerm === '' || 
                name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                tenant.includes(searchTerm);
            
            // Check role criteria - normalize role names
            let matchesRole = true;
            if (selectedRole !== '') {
                if (selectedRole === 'admin') {
                    matchesRole = role.includes('admin') && !role.includes('super');
                } else if (selectedRole === 'super_admin') {
                    matchesRole = role.includes('super') && role.includes('admin');
                } else if (selectedRole === 'consultant') {
                    matchesRole = role.includes('consultant');
                } else if (selectedRole === 'user') {
                    matchesRole = role.includes('user') || role === 'no role';
                }
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
        
        // Show/hide no results message
        if (filteredUsers.length === 0) {
            if (noResultsRow) {
                noResultsRow.style.display = '';
            }
            paginationWrapper.style.display = 'none';
        } else {
            if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
            paginationWrapper.style.display = '';
        }
        
        // Update results counter
        updateResultsCounter(searchTerm, selectedRole, selectedStatus, filteredUsers.length);
        
        // Display users for current page
        displayUsers();
    }
    
    function displayUsers() {
        // Hide all users first
        allUserRows.forEach(row => {
            row.style.display = 'none';
        });
        
        // Show users for current page
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = startIndex + ITEMS_PER_PAGE;
        const usersToShow = filteredUsers.slice(startIndex, endIndex);
        
        usersToShow.forEach(row => {
            row.style.display = '';
        });
        
        // Update pagination
        updatePagination();
    }
    
    function updatePagination() {
        // Update pagination info
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const endIndex = Math.min(currentPage * ITEMS_PER_PAGE, filteredUsers.length);
        
        showingFrom.textContent = filteredUsers.length > 0 ? startIndex : 0;
        showingTo.textContent = endIndex;
        totalResults.textContent = filteredUsers.length;
        
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
    
    // Update results counter
    function updateResultsCounter(searchTerm, selectedRole, selectedStatus, count) {
        const resultsCounter = document.getElementById('resultsCounter');
        const resultsCount = document.getElementById('resultsCount');
        const resultsText = document.getElementById('resultsText');
        const searchTermSpan = document.getElementById('searchTerm');
        const roleTermSpan = document.getElementById('roleTerm');
        const statusTermSpan = document.getElementById('statusTerm');

        resultsCount.textContent = count;
        resultsText.textContent = count === 1 ? 'user found' : 'users found';

        // Show search term if searching
        if (searchTerm) {
            searchTermSpan.querySelector('span').textContent = searchTerm;
            searchTermSpan.classList.remove('hidden');
        } else {
            searchTermSpan.classList.add('hidden');
        }

        // Show role term if filtering by role
        if (selectedRole !== '') {
            const roleText = selectedRole.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const roleClass = selectedRole === 'super_admin' ? 'text-purple-600' : 
                             selectedRole === 'admin' ? 'text-blue-600' : 
                             selectedRole === 'consultant' ? 'text-green-600' : 'text-gray-600';
            roleTermSpan.querySelector('span').textContent = roleText;
            roleTermSpan.querySelector('span').className = `font-medium ${roleClass}`;
            roleTermSpan.classList.remove('hidden');
        } else {
            roleTermSpan.classList.add('hidden');
        }

        // Show status term if filtering by status
        if (selectedStatus !== '') {
            const statusText = selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1);
            const statusClass = selectedStatus === 'active' ? 'text-green-600' : 'text-red-600';
            statusTermSpan.querySelector('span').textContent = statusText;
            statusTermSpan.querySelector('span').className = `font-medium ${statusClass}`;
            statusTermSpan.classList.remove('hidden');
        } else {
            statusTermSpan.classList.add('hidden');
        }

        // Show/hide counter
        if (searchTerm || selectedRole !== '' || selectedStatus !== '') {
            resultsCounter.classList.remove('hidden');
        } else {
            resultsCounter.classList.add('hidden');
        }
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

// Toggle Status Modal Functions
function showToggleStatusModal(userName, action, toggleUrl) {
    document.getElementById('toggleStatusUserName').textContent = userName;
    document.getElementById('toggleStatusAction').textContent = action;
    document.getElementById('toggleStatusForm').action = toggleUrl;
    document.getElementById('toggleStatusModal').classList.remove('hidden');
}

function hideToggleStatusModal() {
    document.getElementById('toggleStatusModal').classList.add('hidden');
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const deleteModal = document.getElementById('deleteModal');
        const toggleStatusModal = document.getElementById('toggleStatusModal');
        
        if (deleteModal && !deleteModal.classList.contains('hidden')) {
            hideDeleteModal();
        }
        if (toggleStatusModal && !toggleStatusModal.classList.contains('hidden')) {
            hideToggleStatusModal();
        }
    }
});

// Close delete modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>

@endsection
