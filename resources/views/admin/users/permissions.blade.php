@extends('layouts.app')

@section('title', 'Gestion des Permissions: ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center mb-6">
            @if(request('agency_id'))
                <a href="{{ route('admin.agencies.users', request('agency_id')) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
            @else
                <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
            @endif
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Permissions</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Permissions Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Permissions Directes</h2>
                        <p class="text-gray-600 text-sm">
                            Assignez des permissions directement à cet utilisateur (en plus des permissions basées sur les rôles).
                            Les permissions directes ont la priorité sur les permissions des rôles.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}">
                        @csrf
                        
                        @if(request('agency_id'))
                            <input type="hidden" name="agency_id" value="{{ request('agency_id') }}">
                        @endif
                        
                        @php
                            $userPermissions = $user->permissions->pluck('id')->toArray();
                            $permissionsByModule = $permissions->groupBy('module');
                        @endphp

                        @foreach($permissionsByModule as $module => $modulePermissions)
                        <div class="permission-module mb-6">
                            <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-folder text-blue-600 mr-3"></i>
                                    {{ ucfirst($module) }}
                                </h3>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $modulePermissions->count() }} permissions
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($modulePermissions as $permission)
                                <div class="permission-item">
                                    <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all duration-200 {{ in_array($permission->id, $userPermissions) ? 'border-blue-500 bg-blue-50' : '' }}">
                                        <input type="checkbox" 
                                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               id="permission_{{ $permission->id }}" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <i class="fas fa-key text-blue-600 mr-2"></i>
                                                <span class="font-medium text-gray-900">{{ $permission->display_name }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">{{ $permission->name }}</p>
                                            @if($permission->description)
                                                <p class="text-xs text-gray-400 mt-1">{{ $permission->description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.users.show', $user) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Annuler
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-save mr-2"></i>Mettre à jour les permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Utilisateur</h3>
                    
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-700">Rôles:</span>
                            <div class="mt-1">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-500">Aucun rôle assigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Résumé des Permissions</h3>
                    
                    @php
                        $totalPermissions = $permissions->count();
                        $assignedPermissions = count($userPermissions);
                        $rolePermissions = $user->getPermissionsViaRoles()->count();
                        $effectivePermissions = $user->getAllPermissions()->count();
                    @endphp

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600" id="direct-count">{{ $assignedPermissions }}</div>
                            <div class="text-xs text-gray-600 uppercase tracking-wide">Directes</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600" id="role-count">{{ $rolePermissions }}</div>
                            <div class="text-xs text-gray-600 uppercase tracking-wide">Via Rôles</div>
                        </div>
                    </div>

                    <div class="text-center p-3 bg-gray-50 rounded-lg mb-4">
                        <div class="text-2xl font-bold text-gray-900" id="total-count">{{ $effectivePermissions }}</div>
                        <div class="text-xs text-gray-600 uppercase tracking-wide">Total Effectif</div>
                    </div>

                    <div class="mb-2">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Couverture</span>
                            <span id="coverage-percentage">{{ $totalPermissions > 0 ? round(($effectivePermissions / $totalPermissions) * 100) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $totalPermissions > 0 ? ($effectivePermissions / $totalPermissions) * 100 : 0 }}%"
                                 id="coverage-bar"></div>
                        </div>
                    </div>
                </div>

                <!-- Help Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aide & Conseils</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm mb-2">Permissions Directes vs Rôles</h4>
                            <p class="text-xs text-gray-600">
                                Les permissions directes sont assignées spécifiquement à cet utilisateur et remplaceront toute permission de rôle conflictuelle.
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm mb-2">Héritage des Permissions</h4>
                            <p class="text-xs text-gray-600">
                                Les utilisateurs héritent des permissions de leurs rôles assignés. Les permissions directes s'ajoutent à ces permissions héritées.
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm mb-2">Bonnes Pratiques</h4>
                            <ul class="text-xs text-gray-600 space-y-1">
                                <li>• Utilisez les rôles pour les ensembles de permissions courants</li>
                                <li>• Utilisez les permissions directes pour les exceptions</li>
                                <li>• Révisez régulièrement les permissions des utilisateurs</li>
                                <li>• Documentez les changements de permissions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Permission selection counter
    function updatePermissionCount() {
        const checkedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        const totalPermissions = document.querySelectorAll('input[name="permissions[]"]').length;
        const rolePermissions = {{ $user->getPermissionsViaRoles()->count() }};
        const effectiveTotal = checkedPermissions + rolePermissions;
        
        // Update counts
        document.getElementById('direct-count').textContent = checkedPermissions;
        document.getElementById('total-count').textContent = effectiveTotal;
        
        // Update progress bar
        const percentage = totalPermissions > 0 ? (effectiveTotal / totalPermissions) * 100 : 0;
        document.getElementById('coverage-bar').style.width = percentage + '%';
        document.getElementById('coverage-percentage').textContent = Math.round(percentage) + '%';
    }

    // Update counts when permissions are checked/unchecked
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updatePermissionCount);
    });

    // Initialize counts on page load
    updatePermissionCount();

    // Module collapse/expand functionality
    document.querySelectorAll('.permission-module h3').forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const module = this.closest('.permission-module');
            const permissions = module.querySelector('.grid');
            const icon = this.querySelector('i');
            
            if (permissions.style.display === 'none') {
                permissions.style.display = 'grid';
                icon.classList.remove('fa-folder');
                icon.classList.add('fa-folder-open');
            } else {
                permissions.style.display = 'none';
                icon.classList.remove('fa-folder-open');
                icon.classList.add('fa-folder');
            }
        });
    });
});
</script>
@endsection