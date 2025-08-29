@extends('layouts.app')

@section('title', 'Bulk Create Permissions')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Bulk Create Permissions</h1>
            <p class="text-gray-600">Create multiple permissions at once using templates or custom definitions</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 mt-4 sm:mt-0">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Permissions
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Permission Creation</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.bulk-create.store') }}">
                        @csrf
                        
                        <!-- Template Selection -->
                        <div class="mb-6">
                            <label for="template" class="block text-sm font-medium text-gray-700 mb-2">Predefined Template</label>
                            <select id="template" name="template" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select a template (optional)</option>
                                <option value="crud">CRUD Operations</option>
                                <option value="user_management">User Management</option>
                                <option value="content_management">Content Management</option>
                                <option value="reporting">Reporting & Analytics</option>
                                <option value="system_admin">System Administration</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose a template to automatically populate permission fields</p>
                        </div>

                        <!-- Module and Tenant -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="module" class="block text-sm font-medium text-gray-700 mb-2">Module *</label>
                                <input type="text" id="module" name="module" value="{{ old('module') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('module') border-red-500 @enderror"
                                       placeholder="e.g., users, content, reports">
                                @error('module')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                                <select id="tenant_id" name="tenant_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror">
                                    <option value="">Select a tenant (optional)</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dynamic Permission Fields -->
                        <div id="permissions-container">
                            <div class="permission-item border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                                        <input type="text" name="permissions[0][name]" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="e.g., users.create">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                                        <input type="text" name="permissions[0][display_name]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="e.g., Create Users">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <input type="text" name="permissions[0][description]"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="e.g., Allow user to create new users">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add/Remove Buttons -->
                        <div class="flex space-x-3 mb-6">
                            <button type="button" id="add-permission" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Add Permission
                            </button>
                            <button type="button" id="remove-permission" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                <i class="fas fa-minus mr-2"></i>Remove Last
                            </button>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Create Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Help & Guidelines</h4>
                
                <div class="space-y-4">
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Permission Naming</h5>
                        <p class="text-sm text-gray-600">Use lowercase with dots: 'module.action' (e.g., 'users.create', 'content.edit')</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Display Names</h5>
                        <p class="text-sm text-gray-600">Human-readable names that appear in the UI</p>
                    </div>
                    
                    <div>
                        <h5 class="font-medium text-gray-800 mb-2">Templates</h5>
                        <p class="text-sm text-gray-600">Use predefined templates to quickly create common permission sets</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="font-medium text-gray-800 mb-3">Template Examples</h5>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div><strong>CRUD:</strong> create, read, update, delete</div>
                        <div><strong>User Management:</strong> users.view, users.create, users.edit, users.delete</div>
                        <div><strong>Content:</strong> content.publish, content.moderate, content.archive</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let permissionCount = 1;

document.getElementById('add-permission').addEventListener('click', function() {
    const container = document.getElementById('permissions-container');
    const newItem = document.createElement('div');
    newItem.className = 'permission-item border border-gray-200 rounded-lg p-4 mb-4';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                <input type="text" name="permissions[${permissionCount}][name]" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., users.create">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                <input type="text" name="permissions[${permissionCount}][display_name]"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Create Users">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <input type="text" name="permissions[${permissionCount}][description]"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Allow user to create new users">
            </div>
        </div>
    `;
    container.appendChild(newItem);
    permissionCount++;
});

document.getElementById('remove-permission').addEventListener('click', function() {
    const container = document.getElementById('permissions-container');
    const items = container.querySelectorAll('.permission-item');
    if (items.length > 1) {
        container.removeChild(items[items.length - 1]);
        permissionCount--;
    }
});

// Template selection handler
document.getElementById('template').addEventListener('change', function() {
    const template = this.value;
    if (template) {
        // Clear existing permissions
        const container = document.getElementById('permissions-container');
        container.innerHTML = '';
        permissionCount = 0;
        
        let permissions = [];
        switch(template) {
            case 'crud':
                permissions = [
                    { name: 'create', display_name: 'Create', description: 'Create new items' },
                    { name: 'read', display_name: 'Read', description: 'View items' },
                    { name: 'update', display_name: 'Update', description: 'Edit existing items' },
                    { name: 'delete', display_name: 'Delete', description: 'Remove items' }
                ];
                break;
            case 'user_management':
                permissions = [
                    { name: 'users.view', display_name: 'View Users', description: 'View user information' },
                    { name: 'users.create', display_name: 'Create Users', description: 'Create new users' },
                    { name: 'users.edit', display_name: 'Edit Users', description: 'Modify user details' },
                    { name: 'users.delete', display_name: 'Delete Users', description: 'Remove users' }
                ];
                break;
            // Add more templates as needed
        }
        
        permissions.forEach((perm, index) => {
            const newItem = document.createElement('div');
            newItem.className = 'permission-item border border-gray-200 rounded-lg p-4 mb-4';
            newItem.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Permission Name *</label>
                        <input type="text" name="permissions[${index}][name]" value="${perm.name}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                        <input type="text" name="permissions[${index}][display_name]" value="${perm.display_name}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" name="permissions[${index}][description]" value="${perm.description}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            permissionCount = index + 1;
        });
    }
});
</script>
@endpush
@endsection
