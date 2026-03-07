<?php

namespace App\Http\Controllers;
use DB;
use Auth;
use Hash;
use App;
use Mail;
use Session;
use Cookie;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth','check.device']);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

     public function index()
    {
        if (config('app.sys_ops_level') !== '117X_ENABLED') {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $user = Auth::user();
        if($user){
            if($user->user_type=='admin' or $user->user_type == 'Support'){

                $users = User::get();
                return redirect()->route('admin_profile');
            }
            else{
                $user = Auth::user();
                return redirect()->route('login');
            }
        }
        else{
            return redirect('/login');
        }
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
    public function logout_affiliate()
    {
        $user =  Auth::guard('affiliates')->user();

        if ($user) {
            Auth::logout();
            Session::flush();
        }
        return redirect('/AffiliatesLogin');

    }
}
