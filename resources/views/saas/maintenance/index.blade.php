@extends('layouts.app')

@section('title', 'System Maintenance')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3">
            <form action="{{ route('saas.maintenance.backup') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-download mr-2"></i>
                    Create Backup
                </button>
            </form>
            <form action="{{ route('saas.maintenance.clear-cache') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-broom mr-2"></i>
                    Clear Cache
                </button>
            </form>
            <form action="{{ route('saas.maintenance.optimize') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-rocket mr-2"></i>
                    Optimize System
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- System Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- PHP Version -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-code text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">PHP Version</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['php_version'] }}</p>
                </div>
            </div>
        </div>

        <!-- Laravel Version -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-leaf text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Laravel Version</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['laravel_version'] }}</p>
                </div>
            </div>
        </div>

        <!-- Database Size -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-database text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Database Size</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['database_size'] }} MB</p>
                </div>
            </div>
        </div>

        <!-- Storage Usage -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hdd text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Storage Usage</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['storage_usage']['used'] }} GB</p>
                    <p class="text-sm text-gray-500">{{ $systemInfo['storage_usage']['percentage'] }}% used</p>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $systemInfo['storage_usage']['percentage'] }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>{{ $systemInfo['storage_usage']['free'] }} GB free</span>
                    <span>{{ $systemInfo['storage_usage']['total'] }} GB total</span>
                </div>
            </div>
        </div>

        <!-- Last Backup -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-save text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Last Backup</p>
                    <p class="text-sm font-medium text-gray-900">{{ $systemInfo['last_backup'] }}</p>
                </div>
            </div>
        </div>

        <!-- Cache Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bolt text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Cache Size</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $systemInfo['cache_status'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health and Recent Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- System Health -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-heartbeat mr-3 text-green-600"></i>
                System Health
            </h3>
            
            <div class="space-y-3">
                @foreach($systemHealth as $component => $status)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $status['status'] === 'healthy' ? 'bg-green-500' : 'bg-red-500' }} mr-3"></div>
                        <span class="font-medium text-gray-700 capitalize">{{ $component }}</span>
                    </div>
                    <span class="text-sm {{ $status['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $status['message'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt mr-3 text-blue-600"></i>
                    Recent Logs
                </h3>
                <a href="{{ route('saas.maintenance.logs') }}" class="btn btn-secondary btn-sm">
                    View All Logs
                </a>
            </div>
            
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($recentLogs as $logLine)
                    <div class="text-xs font-mono bg-gray-50 p-2 rounded">
                        <span class="text-gray-600">{{ $logLine }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No recent logs found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('saas.maintenance.logs') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">View Logs</p>
                    <p class="text-xs text-gray-500">Check system and application logs</p>
                </div>
            </a>
            
            <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-database text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Database Status</p>
                    <p class="text-xs text-gray-500">Monitor database performance</p>
                </div>
            </div>
            
            <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Performance</p>
                    <p class="text-xs text-gray-500">System performance metrics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Schedule -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Maintenance Schedule</h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Daily Backups</p>
                        <p class="text-xs text-gray-500">Automated database backups at 2:00 AM</p>
                    </div>
                </div>
                <span class="text-sm text-blue-600 font-medium">Active</span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-broom text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Weekly Cache Cleanup</p>
                        <p class="text-xs text-gray-500">Cache optimization every Sunday at 3:00 AM</p>
                    </div>
                </div>
                <span class="text-sm text-green-600 font-medium">Active</span>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-bar text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Monthly Reports</p>
                        <p class="text-xs text-gray-500">System health reports on the 1st of each month</p>
                    </div>
                </div>
                <span class="text-sm text-yellow-600 font-medium">Active</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh system health every 30 seconds
    setInterval(function() {
        // You could implement AJAX refresh here
        console.log('System health check - ' + new Date().toLocaleTimeString());
    }, 30000);
});
</script>
@endpush
