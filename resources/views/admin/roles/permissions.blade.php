@extends('layouts.app')

@section('title', 'Role Permissions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Role Permissions</h1>
                <p class="text-gray-600 mt-1">Manage permissions for {{ $role->display_name }}</p>
            </div>
        </div>
    </div>

    <!-- Role Info Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $role->display_name }}</h2>
                <p class="text-gray-600 mb-4">{{ $role->description }}</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span><i class="fas fa-tag mr-1"></i>{{ $role->name }}</span>
                    <span><i class="fas fa-building mr-1"></i>{{ $role->tenant->name ?? 'No Tenant' }}</span>
                    <span><i class="fas fa-calendar mr-1"></i>Created {{ $role->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $role->name === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($role->name) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Permissions Form -->
    <form action="{{ route('admin.roles.update-permissions', $role) }}" method="POST" class="bg-white rounded-xl shadow-lg">
        @csrf
        @method('PUT')
        
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Assign Permissions</h3>
            <p class="text-sm text-gray-600 mt-1">Select the permissions you want to assign to this role</p>
        </div>

        <div class="p-6">
            @if($allPermissions->count() > 0)
                <div class="space-y-6">
                    @foreach($allPermissions as $module => $permissions)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900 capitalize">{{ $module }}</h4>
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="selectAllInModule('{{ $module }}')" 
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Select All
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="deselectAllInModule('{{ $module }}')" 
                                            class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                        Deselect All
                                    </button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               data-module="{{ $module }}"
                                               {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <div class="ml-3 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shield-alt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Permissions Available</h3>
                    <p class="text-gray-600">There are no permissions configured in the system.</p>
                </div>
            @endif
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span id="selectedCount">0</span> permissions selected
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Permissions
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    const selectedCount = document.getElementById('selectedCount');
    
    function updateSelectedCount() {
        const checked = document.querySelectorAll('input[name="permissions[]"]:checked');
        selectedCount.textContent = checked.length;
    }
    
    function selectAllInModule(module) {
        const moduleCheckboxes = document.querySelectorAll(`input[data-module="${module}"]`);
        moduleCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    }
    
    function deselectAllInModule(module) {
        const moduleCheckboxes = document.querySelectorAll(`input[data-module="${module}"]`);
        moduleCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    }
    
    // Add event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Initialize count
    updateSelectedCount();
    
    // Make functions global
    window.selectAllInModule = selectAllInModule;
    window.deselectAllInModule = deselectAllInModule;
});
</script>
@endsection


