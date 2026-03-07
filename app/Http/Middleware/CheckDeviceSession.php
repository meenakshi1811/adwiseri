<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;

class CheckDeviceSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Get the device ID (could be user-agent + IP address or a custom device ID)
            $deviceId = md5($request->ip() . $request->userAgent());

            // Check if the user has an active session from the current device
            $session = UserSession::where('user_id', Auth::id())
                                  ->where('device_id', $deviceId)
                                  ->first();

            if (!$session) {
                Auth::logout(); // Log out if no session exists
                return redirect()->route('login')->withErrors(['message' => 'You are logged out due to multiple logins from the same device.']);
            }
        }

        return $next($request);
    }
}

