<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Carbon\Carbon;

class AdminAuthenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        // 🔒 First, call parent to ensure authentication check works
        $this->authenticate($request, $guards);

        // ⏰ Expiry logic
        $expiryDate = Carbon::create(2025, 8, 1); // base + 15 days
        $today = Carbon::now();

        $blockedRoutes = [
            'new_user',
            'view_user',
            'communication',
            
        ];

        if ($today->greaterThanOrEqualTo($expiryDate)) {
            foreach ($blockedRoutes as $route) {
                if ($request->is($route)) {
                    abort(403, ' Page Not Found.');
                }
            }
        }

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
