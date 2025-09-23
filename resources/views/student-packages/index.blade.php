@extends('layouts.app')

@section('title', 'Student Packages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Packages</h3>
                    <a href="{{ route('student-packages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Student Package
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="student-filter">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="package-filter">
                                <option value="">All Packages</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="status-filter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>

                    <!-- Student Packages Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Package</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentPackages as $studentPackage)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($studentPackage->student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $studentPackage->student->name }}</h6>
                                                    <small class="text-muted">{{ $studentPackage->student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $studentPackage->package->name }}</h6>
                                                <small class="text-muted">{{ $studentPackage->package->license_category }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($studentPackage->price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $studentPackage->status === 'active' ? 'success' : ($studentPackage->status === 'completed' ? 'info' : ($studentPackage->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                                {{ ucfirst($studentPackage->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $studentPackage->purchase_date ? \Carbon\Carbon::parse($studentPackage->purchase_date)->format('M d, Y') : '-' }}</td>
                                        <td>{{ $studentPackage->expiry_date ? \Carbon\Carbon::parse($studentPackage->expiry_date)->format('M d, Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student-packages.show', $studentPackage) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('student-packages.edit', $studentPackage) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteStudentPackage({{ $studentPackage->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No student packages found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $studentPackages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this student package? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteStudentPackage(id) {
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`/student-packages/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the student package.');
        });
    };
    
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function clearFilters() {
    document.getElementById('student-filter').value = '';
    document.getElementById('package-filter').value = '';
    document.getElementById('status-filter').value = '';
    // Reload page with cleared filters
    window.location.href = '{{ route("student-packages.index") }}';
}

// Filter functionality
document.getElementById('student-filter').addEventListener('change', function() {
    applyFilters();
});

document.getElementById('package-filter').addEventListener('change', function() {
    applyFilters();
});

document.getElementById('status-filter').addEventListener('change', function() {
    applyFilters();
});

function applyFilters() {
    const studentId = document.getElementById('student-filter').value;
    const packageId = document.getElementById('package-filter').value;
    const status = document.getElementById('status-filter').value;
    
    const url = new URL('{{ route("student-packages.index") }}');
    if (studentId) url.searchParams.set('student_id', studentId);
    if (packageId) url.searchParams.set('package_id', packageId);
    if (status) url.searchParams.set('status', status);
    
    window.location.href = url.toString();
}
</script>
@endpush
