<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For now, we'll use subdomain or session to identify tenant
        // In production, you might want to use domain-based routing
        
        $tenant = null;
        
        // Try to get tenant from subdomain
        if ($request->getHost() !== 'localhost' && $request->getHost() !== '127.0.0.1') {
            $subdomain = explode('.', $request->getHost())[0];
            $tenant = Tenant::where('slug', $subdomain)->first();
        }
        
        // Fallback to session for local development
        if (!$tenant && session('tenant_id')) {
            $tenant = Tenant::find(session('tenant_id'));
        }
        
        // Set tenant in request and config
        if ($tenant) {
            $request->merge(['tenant' => $tenant]);
            config(['tenant' => $tenant]);
            
            // Add tenant scope to all queries
            Tenant::addGlobalScope('tenant', function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            });
        }
        
        return $next($request);
    }
}
