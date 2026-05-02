<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure settings table exists before querying
        if (Schema::hasTable('settings')) {
            $isMaintenance = Setting::where('key', 'maintenance_mode')->value('value');
            $announcement = Setting::where('key', 'system_announcement')->value('value');

            // If an announcement exists, share it with all views
            if (!empty($announcement)) {
                view()->share('system_announcement', $announcement);
            }

            // Allow users to log out safely
            if ($request->is('logout')) {
                return $next($request);
            }

            // Only block access if a normal user is logged in
            if ($isMaintenance === 'true' && auth()->check() && !auth()->user()->isAdmin()) {
                return response()->view('errors.maintenance', ['announcement' => $announcement], 503);
            }
        }

        return $next($request);
    }
}
