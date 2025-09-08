@extends('layouts.app')

@section('title', 'System Diagnostics')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">System Diagnostics</h1>
            <p class="text-gray-600">Comprehensive system health monitoring and diagnostics</p>
        </div>
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <form method="POST" action="{{ route('saas.system-diagnostics.clear-cache') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200">
                    <i class="fas fa-broom mr-2"></i>
                    Clear System Cache
                </button>
            </form>
            <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh Diagnostics
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- System Health Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-heartbeat mr-2 text-{{ $diagnostics['system_health']['status'] === 'healthy' ? 'green' : ($diagnostics['system_health']['status'] === 'warning' ? 'yellow' : 'red') }}-500"></i>
                System Health Status
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $diagnostics['system_health']['status'] === 'healthy' ? 'green' : ($diagnostics['system_health']['status'] === 'warning' ? 'yellow' : 'red') }}-100 text-{{ $diagnostics['system_health']['status'] === 'healthy' ? 'green' : ($diagnostics['system_health']['status'] === 'warning' ? 'yellow' : 'red') }}-800">
                    {{ ucfirst($diagnostics['system_health']['status']) }}
                </span>
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($diagnostics['system_health']['checks'] as $check => $result)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $result['status'] === 'ok' ? 'check-circle text-green-500' : ($result['status'] === 'warning' ? 'exclamation-triangle text-yellow-500' : 'times-circle text-red-500') }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $check) }}</h4>
                        <p class="text-sm text-gray-600">{{ $result['message'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tenant Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-server mr-2 text-blue-500"></i>
                Tenant Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $diagnostics['tenant_statistics']['total_tenants'] }}</div>
                    <div class="text-sm text-gray-600">Total Tenants</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $diagnostics['tenant_statistics']['active_tenants'] }}</div>
                    <div class="text-sm text-gray-600">Active Tenants</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $diagnostics['tenant_statistics']['trial_tenants'] }}</div>
                    <div class="text-sm text-gray-600">Trial Tenants</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $diagnostics['tenant_statistics']['inactive_tenants'] }}</div>
                    <div class="text-sm text-gray-600">Inactive Tenants</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                Performance Metrics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $diagnostics['performance_metrics']['total_users'] }}</div>
                    <div class="text-sm text-gray-600">Total Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $diagnostics['performance_metrics']['total_agencies'] }}</div>
                    <div class="text-sm text-gray-600">Total Agencies</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $diagnostics['performance_metrics']['total_reservations'] }}</div>
                    <div class="text-sm text-gray-600">Total Reservations</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $diagnostics['performance_metrics']['total_clients'] }}</div>
                    <div class="text-sm text-gray-600">Total Clients</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-pink-600">{{ $diagnostics['performance_metrics']['total_vehicles'] }}</div>
                    <div class="text-sm text-gray-600">Total Vehicles</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ $diagnostics['performance_metrics']['active_reservations'] }}</div>
                    <div class="text-sm text-gray-600">Active Reservations</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Checks -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-shield-alt mr-2 text-{{ $diagnostics['security_checks']['status'] === 'secure' ? 'green' : 'yellow' }}-500"></i>
                Security Checks
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $diagnostics['security_checks']['status'] === 'secure' ? 'green' : 'yellow' }}-100 text-{{ $diagnostics['security_checks']['status'] === 'secure' ? 'green' : 'yellow' }}-800">
                    {{ ucfirst($diagnostics['security_checks']['status']) }}
                </span>
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($diagnostics['security_checks']['checks'] as $check => $result)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $result['status'] === 'ok' ? 'check-circle text-green-500' : ($result['status'] === 'warning' ? 'exclamation-triangle text-yellow-500' : 'times-circle text-red-500') }} text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $check) }}</h4>
                        <p class="text-sm text-gray-600">{{ $result['message'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                System Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Diagnostics Generated</h4>
                    <p class="text-sm text-gray-600">{{ $diagnostics['timestamp']->format('Y-m-d H:i:s T') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">PHP Version</h4>
                    <p class="text-sm text-gray-600">{{ PHP_VERSION }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Laravel Version</h4>
                    <p class="text-sm text-gray-600">{{ app()->version() }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Environment</h4>
                    <p class="text-sm text-gray-600">{{ app()->environment() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh diagnostics every 5 minutes
setInterval(function() {
    location.reload();
}, 300000); // 5 minutes

// Add loading state to buttons
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            this.disabled = true;
        });
    });
});
</script>
@endpush



