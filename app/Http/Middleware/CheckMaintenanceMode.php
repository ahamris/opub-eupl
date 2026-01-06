<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = Setting::getValue('maintenance_mode', '0');
        
        // Allow admin routes to bypass maintenance mode
        if ($request->is('admin*') || $request->routeIs('admin.*')) {
            return $next($request);
        }
        
        // Allow authenticated admin users to bypass maintenance mode
        if (auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin')) {
            return $next($request);
        }
        
        // If maintenance mode is enabled, show maintenance page
        if ($maintenanceMode === '1') {
            return response()->view('errors.maintenance', [], 503);
        }
        
        return $next($request);
    }
}
