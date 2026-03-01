<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request - only admins allowed.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin access only');
        }

        return $next($request);
    }
}
