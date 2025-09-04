@extends('layouts.app')

@section('title', 'User Details: ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm mr-2">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- User Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Profile</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="avatar-xl mx-auto mb-3">
                                <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center text-white text-3xl font-medium">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>
                            <h5 class="font-weight-bold">{{ $user->name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                            
                            <div class="mt-3">
                                @if($user->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>User ID:</strong> {{ $user->id }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Email:</strong> {{ $user->email }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Email Verified:</strong>
                                        @if($user->email_verified_at)
                                            <span class="text-success">Yes ({{ $user->email_verified_at->format('M d, Y H:i') }})</span>
                                        @else
                                            <span class="text-warning">No</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}
                                    </div>
                                    @if($user->last_login_at)
                                    <div class="mb-3">
                                        <strong>Last Login:</strong> {{ $user->last_login_at->format('M d, Y H:i') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($user->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong>
                                <p class="text-muted mt-1">{{ $user->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Roles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Assigned Roles</h6>
                    <a href="{{ route('admin.users.permissions', $user) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-key mr-2"></i>Manage Permissions
                    </a>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="row">
                            @foreach($user->roles as $role)
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="font-weight-bold text-primary">{{ $role->name }}</h6>
                                                <p class="text-muted small mb-1">{{ $role->description ?? 'No description' }}</p>
                                                <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $role->is_system ? 'info' : 'primary' }}">
                                                    {{ $role->is_system ? 'System' : 'Custom' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No roles assigned to this user</p>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-2"></i>Assign Roles
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Permissions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Effective Permissions</h6>
                </div>
                <div class="card-body">
                    @php
                        $effectivePermissions = $user->getAllPermissions();
                        $permissionsByModule = $effectivePermissions->groupBy('module');
                    @endphp
                    
                    @if($effectivePermissions->count() > 0)
                        @foreach($permissionsByModule as $module => $permissions)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-primary mb-3">{{ ucfirst($module) }}</h6>
                            <div class="row">
                                @foreach($permissions as $permission)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                        <span>{{ $permission->display_name }}</span>
                                        <small class="text-muted ml-2">({{ $permission->name }})</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No permissions assigned to this user</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Agency Information -->
            @if($user->agence)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Agency Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($user->agence->logo)
                            <img src="{{ $user->agence->logo_url }}" alt="Agency Logo" class="img-fluid mb-2" style="max-height: 60px;">
                        @else
                            <div class="w-16 h-16 bg-secondary rounded-lg flex items-center justify-center text-white text-xl font-medium mx-auto mb-2">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                        <h6 class="font-weight-bold">{{ $user->agence->nom_agence }}</h6>
                    </div>
                    
                    <div class="mb-2">
                        <strong>Address:</strong> {{ $user->agence->full_address }}
                    </div>
                    
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $user->agence->is_active ? 'success' : 'danger' }}">
                            {{ $user->agence->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.agencies.show', $user->agence) }}" class="btn btn-outline-info btn-sm w-100">
                            <i class="fas fa-building mr-2"></i>View Agency Details
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tenant Information -->
            @if($user->tenant)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tenant Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Tenant Name:</strong> {{ $user->tenant->name }}
                    </div>
                    
                    <div class="mb-2">
                        <strong>Domain:</strong> {{ $user->tenant->domain }}
                    </div>
                    
                    <div class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $user->tenant->is_active ? 'success' : 'danger' }}">
                            {{ $user->tenant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-2"></i>Edit User
                        </a>
                        
                        <a href="{{ route('admin.users.permissions', $user) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-key mr-2"></i>Manage Permissions
                        </a>
                        
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }} btn-sm w-100">
                                <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }} mr-2"></i>
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash mr-2"></i>Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <p class="text-sm">User profile updated</p>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <p class="text-sm">User account created</p>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-xl {
    width: 96px;
    height: 96px;
}

.avatar-xl > div {
    width: 96px;
    height: 96px;
    font-size: 3rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    margin-left: 10px;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
</style>
@endpush
