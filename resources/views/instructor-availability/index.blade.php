@extends('layouts.app')

@section('title', 'Instructor Availability')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Instructor Availability</h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
                        <i class="fas fa-plus"></i> Add Availability
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="instructor-filter">
                                <option value="">All Instructors</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="date-filter" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="status-filter">
                                <option value="">All Statuses</option>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>

                    <!-- Availability Calendar -->
                    <div class="row">
                        <div class="col-12">
                            <div id="availability-calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Availability Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAvailabilityForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="instructor_id" class="form-label">Instructor <span class="text-danger">*</span></label>
                        <select class="form-select" id="instructor_id" name="instructor_id" required>
                            <option value="">Select an instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="start_time" name="start_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="end_time" name="end_time" required>
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
                    <button type="submit" class="btn btn-primary">Add Availability</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    initializeCalendar();
    
    // Set up filters
    setupFilters();
    
    // Set up form submission
    setupFormSubmission();
});

function initializeCalendar() {
    // This would integrate with a calendar library like FullCalendar
    // For now, we'll show a simple table view
    loadAvailabilityData();
}

function loadAvailabilityData() {
    const instructorId = document.getElementById('instructor-filter').value;
    const date = document.getElementById('date-filter').value;
    const status = document.getElementById('status-filter').value;
    
    fetch('/instructor-availability/data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            instructor_id: instructorId,
            date: date,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayAvailabilityData(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading availability data:', error);
    });
}

function displayAvailabilityData(data) {
    const container = document.getElementById('availability-calendar');
    container.innerHTML = '';
    
    if (data.length === 0) {
        container.innerHTML = '<div class="text-center py-4"><p class="text-muted">No availability data found</p></div>';
        return;
    }
    
    const table = document.createElement('table');
    table.className = 'table table-striped table-hover';
    table.innerHTML = `
        <thead>
            <tr>
                <th>Instructor</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            ${data.map(item => `
                <tr>
                    <td>${item.instructor_name}</td>
                    <td>${item.date}</td>
                    <td>${item.start_time}</td>
                    <td>${item.end_time}</td>
                    <td>
                        <span class="badge bg-${item.status === 'available' ? 'success' : 'danger'}">
                            ${item.status}
                        </span>
                    </td>
                    <td>${item.notes || '-'}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary" onclick="editAvailability(${item.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAvailability(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    
    container.appendChild(table);
}

function setupFilters() {
    document.getElementById('instructor-filter').addEventListener('change', loadAvailabilityData);
    document.getElementById('date-filter').addEventListener('change', loadAvailabilityData);
    document.getElementById('status-filter').addEventListener('change', loadAvailabilityData);
}

function setupFormSubmission() {
    document.getElementById('addAvailabilityForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/instructor-availability', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addAvailabilityModal')).hide();
                loadAvailabilityData();
                this.reset();
            } else {
                alert('Error: ' + (data.message || 'An error occurred'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding availability.');
        });
    });
}

function clearFilters() {
    document.getElementById('instructor-filter').value = '';
    document.getElementById('date-filter').value = '{{ date('Y-m-d') }}';
    document.getElementById('status-filter').value = '';
    loadAvailabilityData();
}

function editAvailability(id) {
    // Implement edit functionality
    console.log('Edit availability:', id);
}

function deleteAvailability(id) {
    if (confirm('Are you sure you want to delete this availability?')) {
        fetch(`/instructor-availability/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAvailabilityData();
            } else {
                alert('Error: ' + (data.message || 'An error occurred'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting availability.');
        });
    }
}
</script>
@endpush
