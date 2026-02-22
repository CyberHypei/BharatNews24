<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please login to access admin panel.');
        }

        $allowedSlugs = ['admin', 'editor', 'author'];
        $hasAccess = $request->user()->roles()
            ->whereIn('slug', $allowedSlugs)
            ->where('roles.status', 'active')
            ->exists();

        if (! $hasAccess) {
            abort(403, 'Unauthorized access to admin panel.');
        }

        return $next($request);
    }
}
