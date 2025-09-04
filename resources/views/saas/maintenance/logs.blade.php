@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">System Logs</h1>
        <div class="flex space-x-3">
            <a href="{{ route('saas.maintenance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Maintenance
            </a>
            <button onclick="refreshLogs()" class="btn btn-primary">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh Logs
            </button>
        </div>
    </div>

    <!-- Log File Selection -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Log File</h3>
        
        <div class="flex space-x-4">
            @foreach($logFiles as $logFile)
                <a href="{{ route('saas.maintenance.logs', ['log' => $logFile]) }}" 
                   class="px-4 py-2 rounded-lg border {{ $currentLog === $logFile ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                    {{ $logFile }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Log Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Log Content: {{ $currentLog }}
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <label for="logLevel" class="text-sm font-medium text-gray-700">Filter Level:</label>
                        <select id="logLevel" class="text-sm border border-gray-300 rounded-md px-2 py-1">
                            <option value="">All Levels</option>
                            <option value="ERROR">ERROR</option>
                            <option value="WARNING">WARNING</option>
                            <option value="INFO">INFO</option>
                            <option value="DEBUG">DEBUG</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="searchLogs" class="text-sm font-medium text-gray-700">Search:</label>
                        <input type="text" id="searchLogs" placeholder="Search logs..." 
                               class="text-sm border border-gray-300 rounded-md px-2 py-1 w-48">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <div id="logContent" class="p-6 bg-gray-900 text-green-400 font-mono text-sm">
                @if($logContent === 'Log file not found')
                    <div class="text-red-400">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Log file not found or not accessible
                    </div>
                @else
                    @foreach($logLines as $logLine)
                        <div class="log-line mb-1" data-level="{{ $logLine['level'] }}">
                            <span class="text-gray-500">{{ $logLine['content'] }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        
        <!-- Log Statistics -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600" id="errorCount">0</div>
                    <div class="text-gray-600">Errors</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600" id="warningCount">0</div>
                    <div class="text-gray-600">Warnings</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600" id="infoCount">0</div>
                    <div class="text-gray-600">Info</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600" id="totalCount">0</div>
                    <div class="text-gray-600">Total Lines</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Log Management</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-download text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Download Logs</h4>
                        <p class="text-sm text-gray-500">Export current log file</p>
                    </div>
                </div>
                <button onclick="downloadLog()" class="btn btn-primary btn-sm w-full">
                    Download {{ $currentLog }}
                </button>
            </div>
            
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-archive text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Archive Logs</h4>
                        <p class="text-sm text-gray-500">Compress old log files</p>
                    </div>
                </div>
                <button onclick="archiveLogs()" class="btn btn-warning btn-sm w-full">
                    Archive Old Logs
                </button>
            </div>
            
            <div class="p-4 border border-gray-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-trash text-red-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Clear Logs</h4>
                        <p class="text-sm text-gray-500">Clear current log file</p>
                    </div>
                </div>
                <button onclick="clearLog()" class="btn btn-danger btn-sm w-full">
                    Clear {{ $currentLog }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize log filtering
    initializeLogFiltering();
    
    // Update log statistics
    updateLogStatistics();
});

function initializeLogFiltering() {
    const logLevel = document.getElementById('logLevel');
    const searchInput = document.getElementById('searchLogs');
    
    logLevel.addEventListener('change', filterLogs);
    searchInput.addEventListener('input', filterLogs);
}

function filterLogs() {
    const level = document.getElementById('logLevel').value;
    const search = document.getElementById('searchLogs').value.toLowerCase();
    const logLines = document.querySelectorAll('.log-line');
    
    logLines.forEach(line => {
        const lineText = line.textContent.toLowerCase();
        const lineLevel = line.dataset.level;
        
        let show = true;
        
        // Filter by level
        if (level && lineLevel !== level) {
            show = false;
        }
        
        // Filter by search term
        if (search && !lineText.includes(search)) {
            show = false;
        }
        
        line.style.display = show ? 'block' : 'none';
    });
    
    updateLogStatistics();
}

function updateLogStatistics() {
    const visibleLines = document.querySelectorAll('.log-line[style="display: block"], .log-line:not([style])');
    const totalCount = visibleLines.length;
    
    let errorCount = 0;
    let warningCount = 0;
    let infoCount = 0;
    
    visibleLines.forEach(line => {
        const level = line.dataset.level;
        switch(level) {
            case 'ERROR':
                errorCount++;
                break;
            case 'WARNING':
                warningCount++;
                break;
            case 'INFO':
                infoCount++;
                break;
        }
    });
    
    document.getElementById('totalCount').textContent = totalCount;
    document.getElementById('errorCount').textContent = errorCount;
    document.getElementById('warningCount').textContent = warningCount;
    document.getElementById('infoCount').textContent = infoCount;
}

function refreshLogs() {
    location.reload();
}

function downloadLog() {
    const logContent = document.getElementById('logContent').textContent;
    const blob = new Blob([logContent], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = '{{ $currentLog }}';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function archiveLogs() {
    if (confirm('Are you sure you want to archive old log files? This will compress logs older than 30 days.')) {
        // TODO: Implement log archiving
        alert('Log archiving feature will be implemented soon.');
    }
}

function clearLog() {
    if (confirm('Are you sure you want to clear the current log file? This action cannot be undone.')) {
        // TODO: Implement log clearing
        alert('Log clearing feature will be implemented soon.');
    }
}
</script>

<style>
.log-line {
    white-space: pre-wrap;
    word-break: break-all;
}

.log-line[data-level="ERROR"] {
    color: #ef4444;
}

.log-line[data-level="WARNING"] {
    color: #f59e0b;
}

.log-line[data-level="INFO"] {
    color: #3b82f6;
}

.log-line[data-level="DEBUG"] {
    color: #6b7280;
}
</style>
@endpush
