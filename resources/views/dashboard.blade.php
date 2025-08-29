@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-green-500 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Clients</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_clients'] ?? 1251) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-blue-600 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Reservations</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_reservations'] ?? 2870) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold">${{ number_format($stats['total_revenue'] ?? 95540) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Management Section -->
    @if(auth()->user()->isAdmin())
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Admin Management</h2>
            <div class="text-sm text-gray-600">SaaS Administration Panel</div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- User Management -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.users.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-blue-100 text-sm">Users</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">User Management</h3>
                <p class="text-blue-100 text-sm">Manage users, roles, and permissions across all agencies</p>
                <div class="mt-4 flex items-center text-blue-100 text-sm">
                    <span>Manage Users</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Agency Management -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.agencies.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_agencies'] ?? 0 }}</div>
                        <div class="text-green-100 text-sm">Agencies</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Agency Management</h3>
                <p class="text-green-100 text-sm">Manage rental agencies and their configurations</p>
                <div class="mt-4 flex items-center text-green-100 text-sm">
                    <span>Manage Agencies</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Role Management -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.roles.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_roles'] ?? 0 }}</div>
                        <div class="text-purple-100 text-sm">Roles</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Role Management</h3>
                <p class="text-purple-100 text-sm">Define and manage user roles and access levels</p>
                <div class="mt-4 flex items-center text-purple-100 text-sm">
                    <span>Manage Roles</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>

            <!-- Permission Management -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('admin.permissions.index') }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $stats['total_permissions'] ?? 0 }}</div>
                        <div class="text-orange-100 text-sm">Permissions</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold mb-2">Permission Management</h3>
                <p class="text-orange-100 text-sm">Configure system permissions and access controls</p>
                <div class="mt-4 flex items-center text-orange-100 text-sm">
                    <span>Manage Permissions</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add New User
                </a>
                <a href="{{ route('admin.agencies.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-building mr-2"></i>
                    Add New Agency
                </a>
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                    <i class="fas fa-user-shield mr-2"></i>
                    Create New Role
                </a>
                <a href="{{ route('admin.bulk-create') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <i class="fas fa-key mr-2"></i>
                    Bulk Create Permissions
                </a>
                <a href="{{ route('admin.car-selection.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                    <i class="fas fa-car mr-2"></i>
                    Manage Landing Cars
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex space-x-1 mb-6">
            <button class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium">Vehicles</button>
            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Reservations</button>
            <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Contracts</button>
        </div>

        <!-- Vehicle Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Total Estimated Revenue</h3>
                    <div class="w-16 h-16 bg-blue-400 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold">88%</span>
                    </div>
                </div>
                <p class="text-3xl font-bold">${{ number_format($stats['estimated_revenue'] ?? 325975) }}</p>
                <div class="mt-4">
                    <div class="w-full bg-blue-400 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 88%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Total Actual Revenue</h3>
                    <div class="w-16 h-16 bg-green-400 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold">59%</span>
                    </div>
                </div>
                <p class="text-3xl font-bold">${{ number_format($stats['actual_revenue'] ?? 302754) }}</p>
                <div class="mt-4">
                    <div class="w-full bg-green-400 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 59%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Monthly Revenue Report</h3>
            <div class="flex space-x-4 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span>Revenue</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span>Reservations</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span>Growth</span>
                </div>
            </div>
        </div>

        <div class="relative chart-container">
            <canvas id="revenueChart"></canvas>
            <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-medium">
                ${{ number_format($stats['current_month_revenue'] ?? 6230) }}
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    <!-- Current Clients Panel -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Clients</h3>
        <div class="flex space-x-2 mb-4">
            @for($i = 0; $i < 5; $i++)
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600 text-sm"></i>
                </div>
            @endfor
        </div>
        <div class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm font-medium inline-block">
            ${{ number_format($stats['current_clients_revenue'] ?? 6230) }}
        </div>
    </div>

    <!-- Revenue Breakdown Panel -->
    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Breakdown</h3>
        <div class="relative mb-6 chart-container">
            <canvas id="breakdownChart"></canvas>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-2xl font-bold text-gray-800">43%</span>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                    <span class="text-sm text-gray-600">Vehicle Rentals</span>
                </div>
                <span class="text-sm font-medium">50%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">Insurance</span>
                </div>
                <span class="text-sm font-medium">20%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">Maintenance</span>
                </div>
                <span class="text-sm font-medium">20%</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Other Services</span>
                </div>
                <span class="text-sm font-medium">10%</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 19000, 15000, 25000, 22000, 30000],
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1
        }, {
            label: 'Reservations',
            data: [45, 67, 52, 89, 78, 95],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Breakdown Chart
const breakdownCtx = document.getElementById('breakdownChart').getContext('2d');
const breakdownChart = new Chart(breakdownCtx, {
    type: 'doughnut',
    data: {
        labels: ['Vehicle Rentals', 'Insurance', 'Maintenance', 'Other Services'],
        datasets: [{
            data: [50, 20, 20, 10],
            backgroundColor: [
                'rgba(37, 99, 235, 0.8)',
                'rgba(250, 204, 21, 0.8)',
                'rgba(74, 222, 128, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});
</script>
@endpush 