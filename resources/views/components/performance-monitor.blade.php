@props(['class' => ''])

<!-- Performance Monitor - Only in development -->
@if(config('app.debug'))
<div class="performance-monitor {{ $class }}" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 9999;">
    <div>Memory: <span id="memory-usage">{{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB</span></div>
    <div>Peak: <span id="peak-memory">{{ number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) }} MB</span></div>
    <div>Load Time: <span id="load-time">{{ round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2) }} ms</span></div>
    <div>Queries: <span id="query-count">N/A</span></div>
</div>

<script>
// Update memory usage in real-time
setInterval(function() {
    if (performance.memory) {
        document.getElementById('memory-usage').textContent = 
            (performance.memory.usedJSHeapSize / 1024 / 1024).toFixed(2) + ' MB';
    }
}, 1000);
</script>
@endif
