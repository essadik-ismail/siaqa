@extends('layouts.app')

@section('title', 'Vehicle Assignments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Vehicle Assignments</h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                        <i class="fas fa-plus"></i> Add Assignment
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="vehicle-filter">
                                <option value="">All Vehicles</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->marque }} {{ $vehicle->modele }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="instructor-filter">
                                <option value="">All Instructors</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="status-filter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>

                    <!-- Assignments Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Instructor</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($assignment->vehicle->marque, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $assignment->vehicle->marque }} {{ $assignment->vehicle->modele }}</h6>
                                                    <small class="text-muted">{{ $assignment->vehicle->immatriculation }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($assignment->instructor->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $assignment->instructor->name }}</h6>
                                                    <small class="text-muted">{{ $assignment->instructor->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $assignment->start_date ? \Carbon\Carbon::parse($assignment->start_date)->format('M d, Y') : '-' }}</td>
                                        <td>{{ $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date)->format('M d, Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($assignment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $assignment->notes ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="editAssignment({{ $assignment->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAssignment({{ $assignment->id }})">
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
                                                <p>No vehicle assignments found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $assignments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Assignment Modal -->
<div class="modal fade" id="addAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Vehicle Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAssignmentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
                        <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                            <option value="">Select a vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->marque }} {{ $vehicle->modele }} ({{ $vehicle->immatriculation }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                        <select class="form-select" id="instructor_id" name="instructor_id" required>
                            <option value="">Select an instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up filters
    setupFilters();
    
    // Set up form submission
    setupFormSubmission();
});

function setupFilters() {
    document.getElementById('vehicle-filter').addEventListener('change', applyFilters);
    document.getElementById('instructor-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);
}

function setupFormSubmission() {
    document.getElementById('addAssignmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/vehicle-assignments', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addAssignmentModal')).hide();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'An error occurred'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the assignment.');
        });
    });
}

function applyFilters() {
    const vehicleId = document.getElementById('vehicle-filter').value;
    const instructorId = document.getElementById('instructor-filter').value;
    const status = document.getElementById('status-filter').value;
    
    const url = new URL('{{ route("vehicle-assignments.index") }}');
    if (vehicleId) url.searchParams.set('vehicle_id', vehicleId);
    if (instructorId) url.searchParams.set('instructor_id', instructorId);
    if (status) url.searchParams.set('status', status);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('vehicle-filter').value = '';
    document.getElementById('instructor-filter').value = '';
    document.getElementById('status-filter').value = '';
    window.location.href = '{{ route("vehicle-assignments.index") }}';
}

function editAssignment(id) {
    // Implement edit functionality
    console.log('Edit assignment:', id);
}

function deleteAssignment(id) {
    if (confirm('Are you sure you want to delete this assignment?')) {
        fetch(`/vehicle-assignments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'An error occurred'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the assignment.');
        });
    }
}
</script>
@endpush
