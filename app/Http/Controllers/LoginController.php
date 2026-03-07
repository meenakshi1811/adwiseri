<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {

        // Validate login credentials
        $credentials = $request->only('email', 'password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
             'g-recaptcha-response' => 'required|captcha'
        ]);




        if (auth()->attempt($credentials)) {
            // Authentication passed, redirect to user profile
            if (auth()->user()->user_type == 'Affiliate') {
                auth()->logout(); // Log out the user
                return redirect()->route('affiliate.createLogin')->withErrors([
                    'email' => 'Affiliate users must log in through the Affiliate portal.',
                ]);
            }
            if(auth()->user()->user_type == 'admin'){

                return redirect()->route('admin_dashboard');

            }
            $deviceId = md5($request->ip() . $request->userAgent());
            // Check if the user already has an active session on the same device
            $existingSession = UserSession::where('user_id', auth()->user()->id)
                                        ->where('device_id', $deviceId)
                                        ->first();
            if ($existingSession) {
                // Logout the previous session (if exists) or forcefully log out the user
                $existingSession->delete();
            }
            // Store the new session for the user and device
            UserSession::create([
                'user_id' => auth()->user()->id,
                'device_id' => $deviceId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return redirect()->route('userprofile'); // Ensure the route exists
        }

        // If authentication fails, redirect back with an error
        return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
    }
    public function logout()
    {
    
        $user = Auth::user() ?? Auth::guard('affiliates')->user();

        if ($user) {
            $user_type = $user->user_type;

            Auth::logout();
            Session::flush();

            if ($user_type == 'admin') {
                return redirect('/admin');
            } elseif ($user_type == 'Affiliate') {
                return redirect('/AffiliatesLogin');
            }
        }
        return redirect()->route('login');

    }

}
