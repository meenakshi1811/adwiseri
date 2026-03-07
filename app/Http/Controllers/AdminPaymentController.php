<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Invoices;
use App\Models\User;
use App\Models\Internal_Invoices;

class AdminPaymentController extends Controller
{
    //

    public function payments()
    {
        $user = Auth::user();
        if ($user) {
            $page = "payments";
            $payments = Invoices::orderBy('created_at', 'desc')->get();
            // $payments = Invoices::where('type', 'inward')->orderBy('created_at', 'desc')->get();
            return view('admin.payments', compact('user', 'payments', 'page'));
        } else {
            return back();
        }
    }
    
}
