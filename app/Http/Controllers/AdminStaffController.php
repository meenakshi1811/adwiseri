<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Countries;
use App\Models\Currency;
use App\Models\UserRoles;
use App\Models\Activities;
use App\Models\Tickets;
use Auth;
use DateTimeZone;
use Hash;

class AdminStaffController extends Controller
{

    public function admin_staff(){
        $siteusers = User::where('user_type', '=', 'admin')->orderBy('created_at', 'desc')->get();
        $user = Auth::user();
        $page = "users";
        return view('admin.admin_staff', compact('siteusers', 'user', 'page'));
    }
    public function admin_new_staff(){
        $countries = Countries::get();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $user = Auth::user();
        $page = "admin_staffs";
        return view('admin.admin_new_staff', compact('page','countries','tzlist','user'));
    }
    public function add_new_staff(Request $request){
        $user = Auth::user();
        // $this->set_timezone();
        $data = new User();
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'dob' => 'required',
                'designation' => 'required|string|max:255',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required|string|max:255',
                'pincode' => 'required',
                'password' => 'required|string|min:8',
            ]
        );
        $country = Countries::find($request->country);
        $data->user_type = "admin";
        $data->added_by = $user->id;
        $data->name = $request['name'];
        $data->phone = $request['phone'];
        $data->email = $request['email'];
        $data->dob = $request['dob'];
        $data->status = "true";
        $data->category = $user->category;
        $data->sub_category = $user->sub_category;
        $data->other_subcategory = $user->other_subcategory;
        $data->membership = $user->membership;
        $data->membership_type = $user->membership_type;
        $data->membership_start_date = $user->membership_start_date;
        $data->membership_expiry_date = $user->membership_expiry_date;
        $data->wallet = 0;
        $data->is_support = 1;
        $data->referral = $user->referral;
        $data->organization = $user->organization;
        $data->designation = $request['designation'];
        $data->employee_strength = $user->employee_strength;
        $data->country = $country->country_name;
        $data->state = $request['state'];
        $data->city = $request['city'];
        $data->pincode = $request['pincode'];
        $data->timezone = $request['timezone'];
        $crcode = $country->currency;
        $currency = Currency::where('currency_code', '=', $crcode)->first();
        if ($currency) {
            $data->currency = $currency->currency_code . "(" . $currency->currency_symbol . ")";
        } else {
            $data->currency = "USD($)";
        }
        $data->password = Hash::make($request['password']);
        // print_r($requet->$data);
        // die();
        $data->save();

        $role = UserRoles::where('user_id', '=', $data->id)->get();
        if ($role) {
            foreach ($role as $r) {
                $r->delete();
            }
        }
        $clients = new UserRoles();
        $clients->user_id = $data->id;
        $clients->subscriber_id = $data->added_by;
        $clients->name = $data->name;
        $clients->email = $data->email;
        $clients->module = "Clients";
        $clients->read_only = 1;
        $clients->write_only = 1;
        $clients->update_only = 1;
        $clients->delete_only = 1;
        $clients->read_write_only = 1;
        $clients->save();

        $applications = new UserRoles();
        $applications->user_id = $data->id;
        $applications->subscriber_id = $data->added_by;
        $applications->name = $data->name;
        $applications->email = $data->email;
        $applications->module = "Applications";
        $applications->read_only = 1;
        $applications->write_only = 1;
        $applications->update_only = 1;
        $applications->delete_only = 1;
        $applications->read_write_only = 1;
        $applications->save();

        $communication = new UserRoles();
        $communication->user_id = $data->id;
        $communication->subscriber_id = $data->added_by;
        $communication->name = $data->name;
        $communication->email = $data->email;
        $communication->module = "Communication";
        $communication->read_only = 1;
        $communication->write_only = 1;
        $communication->update_only = 1;
        $communication->delete_only = 1;
        $communication->read_write_only = 1;
        $communication->save();

        $invoices = new UserRoles();
        $invoices->user_id = $data->id;
        $invoices->subscriber_id = $data->added_by;
        $invoices->name = $data->name;
        $invoices->email = $data->email;
        $invoices->module = "Invoices";
        $invoices->read_only = 1;
        $invoices->write_only = 1;
        $invoices->update_only = 1;
        $invoices->delete_only = 1;
        $invoices->read_write_only = 1;
        $invoices->save();

        $payments = new UserRoles();
        $payments->user_id = $data->id;
        $payments->subscriber_id = $data->added_by;
        $payments->name = $data->name;
        $payments->email = $data->email;
        $payments->module = "Payments";
        $payments->read_only = 1;
        $payments->write_only = 1;
        $payments->update_only = 1;
        $payments->delete_only = 1;
        $payments->read_write_only = 1;
        $payments->save();

        $reports = new UserRoles();
        $reports->user_id = $data->id;
        $reports->subscriber_id = $data->added_by;
        $reports->name = $data->name;
        $reports->email = $data->email;
        $reports->module = "Reports";
        $reports->read_only = 0;
        $reports->write_only = 0;
        $reports->update_only = 0;
        $reports->delete_only = 0;
        $reports->read_write_only = 0;
        $reports->save();

        $subscription = new UserRoles();
        $subscription->user_id = $data->id;
        $subscription->subscriber_id = $data->added_by;
        $subscription->name = $data->name;
        $subscription->email = $data->email;
        $subscription->module = "Subscription";
        $subscription->read_only = 0;
        $subscription->write_only = 0;
        $subscription->update_only = 0;
        $subscription->delete_only = 0;
        $subscription->read_write_only = 0;
        $subscription->save();

        $settings = new UserRoles();
        $settings->user_id = $data->id;
        $settings->subscriber_id = $data->added_by;
        $settings->name = $data->name;
        $settings->email = $data->email;
        $settings->module = "Settings";
        $settings->read_only = 0;
        $settings->write_only = 0;
        $settings->update_only = 0;
        $settings->delete_only = 0;
        $settings->read_write_only = 0;
        $settings->save();

        $support = new UserRoles();
        $support->user_id = $data->id;
        $support->subscriber_id = $data->added_by;
        $support->name = $data->name;
        $support->email = $data->email;
        $support->module = "Support";
        $support->read_only = 1;
        $support->write_only = 1;
        $support->update_only = 1;
        $support->delete_only = 1;
        $support->read_write_only = 1;
        $support->save();


        $activity = new Activities();
        $activity->subscriber_id = $user->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "New Admin User Added";
        $activity->activity_detail = "New Admin User " . $request->name . " added by " . $user->name . " for " . $request->designation . " job role at " . $request->local_time;
        $activity->activity_icon = "user.png";
        $activity->local_time = $request->local_time;
        $activity->save();
        return redirect()->route('admin_staff')->with('admin_staff_added', "Staff added successfully.");

    }
    public function assign_supports(Request $request){
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $ticket = Tickets::find($validated['ticket_id']);
        $ticket->served_by= $validated['user_id']; // Assuming you have an `assigned_user_id` column
        $ticket->save();

        return redirect()->back()->with('success_assign', 'User assigned successfully.');
    }
}
