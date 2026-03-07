<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAccessMiddleware
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
        $secret = env('ADWISERI_GOOGLE_SECRET');

        // Check for custom header
        if ($request->header('X-Secret-Bypass') !== $secret) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
