<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }
        
        // Skip caching for authenticated users
        if (auth()->check()) {
            return $next($request);
        }
        
        // Generate cache key
        $cacheKey = 'page_' . md5($request->url() . $request->getQueryString());
        
        // Check if response is cached
        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey));
        }
        
        // Get response
        $response = $next($request);
        
        // Cache successful responses for 5 minutes
        if ($response->getStatusCode() === 200) {
            Cache::put($cacheKey, $response->getContent(), 300); // 5 minutes
        }
        
        return $response;
    }
}
