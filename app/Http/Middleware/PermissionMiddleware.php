<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Permission parameter: single slug "manage-users" or multiple "create-post|edit-post" (user needs any one).
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $slugs = array_filter(explode('|', $permissions[0] ?? ''));
        if ($slugs === [] || $request->user()->hasAnyPermission($slugs)) {
            return $next($request);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
