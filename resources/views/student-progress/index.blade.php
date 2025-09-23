@extends('layouts.app')

@section('title', 'Student Progress')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Progress</h3>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="exportProgress()">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn btn-outline-secondary" onclick="refreshProgress()">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
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
                            <select class="form-select" id="status-filter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="progress-filter">
                                <option value="">All Progress Levels</option>
                                <option value="beginner">Beginner (0-25%)</option>
                                <option value="intermediate">Intermediate (26-75%)</option>
                                <option value="advanced">Advanced (76-100%)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>

                    <!-- Progress Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Total Students</h4>
                                            <h2 id="total-students">{{ $students->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Active Students</h4>
                                            <h2 id="active-students">{{ $students->where('status', 'active')->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Completed</h4>
                                            <h2 id="completed-students">{{ $students->where('status', 'graduated')->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-graduation-cap fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Average Progress</h4>
                                            <h2 id="average-progress">{{ number_format($students->avg('progress_percentage') ?? 0, 1) }}%</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Theory Progress</th>
                                    <th>Practical Progress</th>
                                    <th>Overall Progress</th>
                                    <th>Lessons</th>
                                    <th>Exams</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $student->name }}</h6>
                                                    <small class="text-muted">{{ $student->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'graduated' ? 'info' : 'warning') }}">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-info" role="progressbar" 
                                                     style="width: {{ $student->theory_completion_percentage ?? 0 }}%"
                                                     aria-valuenow="{{ $student->theory_completion_percentage ?? 0 }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($student->theory_completion_percentage ?? 0, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $student->practical_completion_percentage ?? 0 }}%"
                                                     aria-valuenow="{{ $student->practical_completion_percentage ?? 0 }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($student->practical_completion_percentage ?? 0, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $student->progress_percentage ?? 0 }}%"
                                                     aria-valuenow="{{ $student->progress_percentage ?? 0 }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($student->progress_percentage ?? 0, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $student->lessons_count ?? 0 }} / {{ $student->required_lessons ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $student->exams_count ?? 0 }} / {{ $student->required_exams ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('students.progress', $student) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No students found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up filters
    setupFilters();
});

function setupFilters() {
    document.getElementById('student-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('progress-filter').addEventListener('change', applyFilters);
}

function applyFilters() {
    const studentId = document.getElementById('student-filter').value;
    const status = document.getElementById('status-filter').value;
    const progress = document.getElementById('progress-filter').value;
    
    const url = new URL('{{ route("student-progress.index") }}');
    if (studentId) url.searchParams.set('student_id', studentId);
    if (status) url.searchParams.set('status', status);
    if (progress) url.searchParams.set('progress', progress);
    
    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('student-filter').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('progress-filter').value = '';
    window.location.href = '{{ route("student-progress.index") }}';
}

function exportProgress() {
    const studentId = document.getElementById('student-filter').value;
    const status = document.getElementById('status-filter').value;
    const progress = document.getElementById('progress-filter').value;
    
    const url = new URL('{{ route("student-progress.export") }}');
    if (studentId) url.searchParams.set('student_id', studentId);
    if (status) url.searchParams.set('status', status);
    if (progress) url.searchParams.set('progress', progress);
    
    window.open(url.toString(), '_blank');
}

function refreshProgress() {
    location.reload();
}
</script>
@endpush
