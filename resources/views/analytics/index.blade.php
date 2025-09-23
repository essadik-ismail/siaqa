@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analytics Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Total Students</h4>
                                            <h2 id="total-students">-</h2>
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
                                            <h4 class="card-title">Active Lessons</h4>
                                            <h2 id="active-lessons">-</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-book fa-2x"></i>
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
                                            <h4 class="card-title">Completed Exams</h4>
                                            <h2 id="completed-exams">-</h2>
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
                                            <h4 class="card-title">Total Revenue</h4>
                                            <h2 id="total-revenue">-</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Student Progress Over Time</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="progressChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">License Categories</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="categoryChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    <div id="recent-activity">
                                        <!-- Recent activity will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Top Instructors</h5>
                                </div>
                                <div class="card-body">
                                    <div id="top-instructors">
                                        <!-- Top instructors will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load analytics data
    loadAnalyticsData();
    
    // Set up auto-refresh every 5 minutes
    setInterval(loadAnalyticsData, 300000);
});

function loadAnalyticsData() {
    // Load dashboard metrics
    fetch('/analytics/dashboard')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-students').textContent = data.data.total_students || 0;
                document.getElementById('active-lessons').textContent = data.data.active_lessons || 0;
                document.getElementById('completed-exams').textContent = data.data.completed_exams || 0;
                document.getElementById('total-revenue').textContent = '$' + (data.data.total_revenue || 0).toLocaleString();
            }
        })
        .catch(error => {
            console.error('Error loading analytics data:', error);
        });
    
    // Load charts
    loadProgressChart();
    loadCategoryChart();
    loadRecentActivity();
    loadTopInstructors();
}

function loadProgressChart() {
    fetch('/analytics/progress-chart')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ctx = document.getElementById('progressChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.data.labels || [],
                        datasets: [{
                            label: 'Students',
                            data: data.data.values || [],
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading progress chart:', error);
        });
}

function loadCategoryChart() {
    fetch('/analytics/category-chart')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.data.labels || [],
                        datasets: [{
                            data: data.data.values || [],
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF'
                            ]
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading category chart:', error);
        });
}

function loadRecentActivity() {
    fetch('/analytics/recent-activity')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('recent-activity');
                container.innerHTML = '';
                
                data.data.forEach(activity => {
                    const activityDiv = document.createElement('div');
                    activityDiv.className = 'd-flex align-items-center mb-3';
                    activityDiv.innerHTML = `
                        <div class="flex-shrink-0">
                            <i class="fas fa-${activity.icon} text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">${activity.title}</h6>
                            <small class="text-muted">${activity.time}</small>
                        </div>
                    `;
                    container.appendChild(activityDiv);
                });
            }
        })
        .catch(error => {
            console.error('Error loading recent activity:', error);
        });
}

function loadTopInstructors() {
    fetch('/analytics/top-instructors')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('top-instructors');
                container.innerHTML = '';
                
                data.data.forEach(instructor => {
                    const instructorDiv = document.createElement('div');
                    instructorDiv.className = 'd-flex align-items-center mb-3';
                    instructorDiv.innerHTML = `
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                ${instructor.name.charAt(0)}
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">${instructor.name}</h6>
                            <small class="text-muted">${instructor.lessons} lessons</small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-success">${instructor.rating}★</span>
                        </div>
                    `;
                    container.appendChild(instructorDiv);
                });
            }
        })
        .catch(error => {
            console.error('Error loading top instructors:', error);
        });
}
</script>
@endpush
