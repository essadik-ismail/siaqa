@extends('layouts.app')

@section('title', 'Manage Permissions: ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Permissions: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-eye mr-2"></i>View User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Permissions Management Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Direct Permission Assignment</h6>
                    <small class="text-muted">Assign permissions directly to this user (in addition to role-based permissions)</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}">
                        @csrf
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note:</strong> Permissions assigned directly to users will be combined with permissions from their assigned roles. 
                            Direct permissions take precedence over role permissions.
                        </div>

                        @php
                            $userPermissions = $user->permissions->pluck('id')->toArray();
                            $permissionsByModule = $permissions->groupBy('module');
                        @endphp

                        @foreach($permissionsByModule as $module => $modulePermissions)
                        <div class="permission-module mb-4">
                            <h6 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-folder mr-2"></i>{{ ucfirst($module) }}
                                <span class="badge badge-secondary ml-2">{{ $modulePermissions->count() }} permissions</span>
                            </h6>
                            
                            <div class="row">
                                @foreach($modulePermissions as $permission)
                                <div class="col-md-6 mb-3">
                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="permission_{{ $permission->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-key text-primary mr-2"></i>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $permission->display_name }}</div>
                                                        <small class="text-muted">{{ $permission->name }}</small>
                                                        @if($permission->description)
                                                            <div class="text-muted small mt-1">{{ $permission->description }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Update Permissions
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center text-white text-2xl font-medium">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="font-weight-bold">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Roles:</strong>
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge badge-primary mr-1">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No roles assigned</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Permission Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Permission Summary</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalPermissions = $permissions->count();
                        $assignedPermissions = count($userPermissions);
                        $rolePermissions = $user->getPermissionsViaRoles()->count();
                        $effectivePermissions = $user->getAllPermissions()->count();
                    @endphp

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="permission-stat">
                                <div class="stat-number text-primary">{{ $assignedPermissions }}</div>
                                <div class="stat-label">Direct</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="permission-stat">
                                <div class="stat-number text-info">{{ $rolePermissions }}</div>
                                <div class="stat-label">Via Roles</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <div class="permission-stat">
                            <div class="stat-number text-success">{{ $effectivePermissions }}</div>
                            <div class="stat-label">Total Effective</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $totalPermissions > 0 ? ($effectivePermissions / $totalPermissions) * 100 : 0 }}%">
                                {{ $totalPermissions > 0 ? round(($effectivePermissions / $totalPermissions) * 100) : 0 }}%
                            </div>
                        </div>
                        <small class="text-muted">Permission coverage</small>
                    </div>
                </div>
            </div>

            <!-- Help Information -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Help & Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-primary">Direct vs Role Permissions</h6>
                        <p class="text-muted small">
                            Direct permissions are assigned specifically to this user and will override any conflicting role permissions.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-primary">Permission Inheritance</h6>
                        <p class="text-muted small">
                            Users inherit permissions from their assigned roles. Direct permissions are added to these inherited permissions.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-primary">Best Practices</h6>
                        <ul class="text-muted small">
                            <li>Use roles for common permission sets</li>
                            <li>Use direct permissions for exceptions</li>
                            <li>Regularly review user permissions</li>
                            <li>Document permission changes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.permission-module {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 1rem;
    background-color: #f8f9fc;
}

.permission-item {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 0.75rem;
    transition: all 0.2s ease;
}

.permission-item:hover {
    border-color: #4e73df;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-check-input:checked + .form-check-label .permission-item {
    border-color: #4e73df;
    background-color: #f8f9ff;
}

.form-actions {
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-lg > div {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.permission-stat {
    padding: 0.5rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress {
    height: 0.5rem;
    border-radius: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Permission selection counter
    function updatePermissionCount() {
        const checkedPermissions = $('input[name="permissions[]"]:checked').length;
        const totalPermissions = $('input[name="permissions[]"]').length;
        
        // Update the direct permissions count in the summary
        $('.permission-stat .stat-number.text-primary').text(checkedPermissions);
        
        // Update the total effective count (direct + role permissions)
        const rolePermissions = {{ $user->getPermissionsViaRoles()->count() }};
        const effectiveTotal = checkedPermissions + rolePermissions;
        $('.permission-stat .stat-number.text-success').text(effectiveTotal);
        
        // Update progress bar
        const percentage = totalPermissions > 0 ? (effectiveTotal / totalPermissions) * 100 : 0;
        $('.progress-bar').css('width', percentage + '%').text(Math.round(percentage) + '%');
    }

    // Update counts when permissions are checked/unchecked
    $('input[name="permissions[]"]').on('change', updatePermissionCount);

    // Initialize counts on page load
    updatePermissionCount();

    // Module collapse/expand functionality
    $('.permission-module h6').on('click', function() {
        const module = $(this).closest('.permission-module');
        const permissions = module.find('.permission-item').parent();
        
        if (permissions.is(':visible')) {
            permissions.slideUp();
            $(this).find('i').removeClass('fa-folder-open').addClass('fa-folder');
        } else {
            permissions.slideDown();
            $(this).find('i').removeClass('fa-folder').addClass('fa-folder-open');
        }
    });

    // Add cursor pointer to module headers
    $('.permission-module h6').css('cursor', 'pointer');
});
</script>
@endpush
