@extends('layouts.app')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User: {{ $user->name }}</h1>
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
            <!-- User Edit Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit User Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave blank to keep current password. Minimum 8 characters if changing.</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agency_id" class="form-label">Agency</label>
                                    <select class="form-control @error('agency_id') is-invalid @enderror" 
                                            id="agency_id" name="agency_id">
                                        <option value="">Select Agency</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->id }}" {{ old('agency_id', $user->agency_id) == $agency->id ? 'selected' : '' }}>
                                                {{ $agency->nom_agence }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roles" class="form-label">Roles *</label>
                                    <select class="form-control @error('roles') is-invalid @enderror" 
                                            id="roles" name="roles[]" multiple required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple roles</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tenant_id" class="form-label">Tenant</label>
                                    <select class="form-control @error('tenant_id') is-invalid @enderror" 
                                            id="tenant_id" name="tenant_id">
                                        <option value="">Select Tenant</option>
                                        @foreach($tenants as $tenant)
                                            <option value="{{ $tenant->id }}" {{ old('tenant_id', $user->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                                {{ $tenant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tenant_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active User
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive users cannot log in</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $user->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Cancel</a>
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
                        <strong>User ID:</strong> {{ $user->id }}
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Email Verified:</strong>
                        @if($user->email_verified_at)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-warning">No</span>
                        @endif
                    </div>

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

            <!-- Current Roles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Current Roles</h6>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        @foreach($user->roles as $role)
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge badge-primary">{{ $role->name }}</span>
                            <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center small">No roles assigned</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.permissions', $user) }}" class="btn btn-outline-secondary btn-sm">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-label {
    font-weight: 600;
    color: #374151;
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

select[multiple] {
    min-height: 120px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Password confirmation validation (only if password is being changed)
    $('#password').on('input', function() {
        const password = $(this).val();
        const confirmPassword = $('#password_confirmation').val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            $('#password_confirmation').addClass('is-invalid');
            if (!$('#password_confirmation').next('.invalid-feedback').length) {
                $('#password_confirmation').after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else if (password && confirmPassword && password === confirmPassword) {
            $('#password_confirmation').removeClass('is-invalid');
            $('#password_confirmation').next('.invalid-feedback').remove();
        }
    });

    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Form submission validation
    $('form').on('submit', function(e) {
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
    });
});
</script>
@endpush
