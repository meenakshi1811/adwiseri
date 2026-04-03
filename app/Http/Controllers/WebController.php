<?php

namespace App\Http\Controllers;
// date_default_timezone_set("Asia/Kolkata");
use DB;
use Auth;
use Hash;
use App;
use Mail;
use Log;
use Session;
use Cookie;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use DateTime;
use DateTimeZone;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Mail\EmailVerification;
use App\Mail\Invoicemail;
use App\Mail\SupportMail;
use App\Mail\WelcomeMail;
use App\Mail\SubscriptionMail;
use App\Mail\PlanSubscriptionMail;
use App\Mail\AppointmentSchedulerMail;
use App\Mail\ClientCareLetterMail;

use App\Models\User;
use App\Models\Clients;
use App\Models\Client_Docs;
use App\Models\Currency;
use App\Models\Countries;
use App\Models\Services;
use App\Models\States;
use App\Models\Subscriber_Categories;
use App\Models\Subscriber_Sub_Categories;
use App\Models\Contactus;
use App\Models\Features;
use App\Models\Membership;
use App\Models\About_Advisori;
use App\Models\Invoices;
use App\Models\Internal_Invoices;
use App\Models\Job_roles;
use App\Models\Activities;
use App\Models\Client_jobs;
use App\Models\Messages;
use App\Models\Referrals;
use App\Models\Applications;
use App\Models\Tickets;
use App\Models\MyTimezones;
use App\Models\Faq;
use App\Models\Invoice_settings;
use App\Models\Used_referrals;
use App\Models\AffiliateCommissionEarnt;
use App\Models\Application_assignments;
use App\Models\Internal_communications;
use App\Models\Client_discussions;
use App\Models\EmailSubscriptions;
use App\Models\DemoRequests;
use App\Models\UserRoles;
use App\Models\Affiliates;
use App\Models\Feedbacks;
use App\Models\PaymentARs;
use App\Models\Offers;
use App\Models\Appointment;

use App\Exports\UsersExport;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
/*Newly added models on 2026-03-06 by Meenakshi Nanta*/
use App\Models\VisaEnquiry;
use App\Models\ReportSetting;
use App\Models\PaymentReminderSetting;
use App\Services\EmailTemplateService;
class WebController extends Controller
{
    private function generateInternalInvoiceId(): string
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id = "";
        for ($i = 0; $i < 8; $i++) {
            $id .= $ch[rand(0, strlen($ch) - 1)];
        }

        if (Internal_Invoices::where('invoice_no', '=', $id)->exists()) {
            return $this->generateInternalInvoiceId();
        }

        return $id;
    }

    private function generateInternalInvoiceToken(): string
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $token = "";
        for ($i = 0; $i < 20; $i++) {
            $token .= $ch[rand(0, strlen($ch) - 1)];
        }

        if (Internal_Invoices::where('token', '=', $token)->exists()) {
            return $this->generateInternalInvoiceToken();
        }

        return $token;
    }

    private function writeExportCsv($filePath, $rows)
    {
        $handle = fopen($filePath, 'w');
        if (!$handle) {
            return;
        }

        if (count($rows) > 0) {
            $headers = array_keys((array) $rows[0]);
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, array_values((array) $row));
            }
        }

        fclose($handle);
    }

    private function safeArchiveName($name)
    {
        return trim(preg_replace('/[^A-Za-z0-9\-_. ]/', '', (string) $name)) ?: 'Unknown';
    }

    private function createAdminApInvoiceAndPayment(User $subscriber, User $company, float $amount, string $paymentMode, string $detail = 'Subscription Fees'): Internal_Invoices
    {
        $amount = round(max(0, $amount), 2);

        $internalInvoice = new Internal_Invoices();
        $internalInvoice->invoice_no = $this->generateInternalInvoiceId();
        $internalInvoice->subscriber_id = $subscriber->id;
        $internalInvoice->name = $company->organization;
        $internalInvoice->email = $company->email;
        $internalInvoice->phone = $company->phone;
        $internalInvoice->country = $company->country;
        $internalInvoice->state = $company->state;
        $internalInvoice->city = $company->city;
        $internalInvoice->pincode = $company->pincode;
        $internalInvoice->address = $company->address_line;
        $internalInvoice->logo = $company->organization_logo;
        $internalInvoice->to_name = 'adwiseri.com';
        $internalInvoice->to_email = $subscriber->email;
        $internalInvoice->to_phone = $subscriber->phone;
        $internalInvoice->to_country = $subscriber->country;
        $internalInvoice->to_state = $subscriber->state;
        $internalInvoice->to_city = $subscriber->city;
        $internalInvoice->to_pincode = $subscriber->pincode;
        $internalInvoice->to_address = $subscriber->address_line;
        $internalInvoice->detail = $detail;
        $internalInvoice->amount = $amount;
        $internalInvoice->discount = 0;
        $internalInvoice->tax = 0;
        $internalInvoice->total = $amount;
        $internalInvoice->status = 'Paid';
        $internalInvoice->type = 'ap';
        $internalInvoice->due_date = date('Y-m-d');
        $internalInvoice->token = $this->generateInternalInvoiceToken();
        $internalInvoice->save();

        PaymentARs::create([
            'subscriber_id' => $subscriber->id,
            'invoice_no' => $internalInvoice->invoice_no,
            'service_provider' => 'adwiseri.com',
            'service_taken' => $detail,
            'amount' => $amount,
            'paid_amount' => $amount,
            'payment_mode' => 'Online',
            'payment_date' => now(),
            'type' => 'ap',
        ]);

        return $internalInvoice;
    }

    private function buildInvoicePdfData(Internal_Invoices $internalInvoice, User $subscriber, User $company): object
    {
        return (object) [
            'invoice_no' => $internalInvoice->invoice_no,
            'invoice_date' => $internalInvoice->created_at,
            'due_date' => $internalInvoice->due_date,
            'status' => $internalInvoice->status,
            'detail' => $internalInvoice->detail,
            'amount' => $internalInvoice->amount,
            'discount' => $internalInvoice->discount,
            'tax' => $internalInvoice->tax,
            'total' => $internalInvoice->total,
            'currency' => 'USD',
            'name' => $subscriber->name,
            'to_email' => $subscriber->email,
            'company_name' => $company->organization ?: 'adwiseri',
            'from_email' => $company->email,
            'display_from_email' => $company->email,
            'logo_path' => !empty($company->organization_logo) ? 'web_assets/users/logos/' . $company->organization_logo : null,
        ];
    }

    private function sendPlanUpdateMail(User $subscriber, ?Membership $plan, Internal_Invoices $internalInvoice, User $company): void
    {
        Mail::to($subscriber->email)->send(new PlanSubscriptionMail(
            $subscriber->name,
            $plan->membership ?? $subscriber->membership,
            $plan->validity ?? 'N/A',
            'Your Subscription Plan Has Been Updated',
            $this->buildInvoicePdfData($internalInvoice, $subscriber, $company)
        ));
    }

    public function add_subscriber_roles()
    {
        $subscribers = User::where('user_type', '=', "Subscriber")->get();
        foreach ($subscribers as $staff) {
            $role = UserRoles::where('user_id', '=', $staff->id)->get();
            if ($role) {
                foreach ($role as $r) {
                    $r->delete();
                }
            }

            $clients = new UserRoles();
            $clients->user_id = $staff->id;
            // $clients->subscriber_id = $staff->added_by;
            $clients->name = $staff->name;
            $clients->email = $staff->email;
            $clients->module = "Clients";
            $clients->read_only = 1;
            $clients->write_only = 1;
            $clients->update_only = 1;
            $clients->delete_only = 1;
            $clients->read_write_only = 1;
            $clients->save();

            $applications = new UserRoles();
            $applications->user_id = $staff->id;
            // $applications->subscriber_id = $staff->added_by;
            $applications->name = $staff->name;
            $applications->email = $staff->email;
            $applications->module = "Applications";
            $applications->read_only = 1;
            $applications->write_only = 1;
            $applications->update_only = 1;
            $applications->delete_only = 1;
            $applications->read_write_only = 1;
            $applications->save();

            $communication = new UserRoles();
            $communication->user_id = $staff->id;
            // $communication->subscriber_id = $staff->added_by;
            $communication->name = $staff->name;
            $communication->email = $staff->email;
            $communication->module = "Communication";
            $communication->read_only = 1;
            $communication->write_only = 1;
            $communication->update_only = 1;
            $communication->delete_only = 1;
            $communication->read_write_only = 1;
            $communication->save();

            $invoices = new UserRoles();
            $invoices->user_id = $staff->id;
            // $invoices->subscriber_id = $staff->added_by;
            $invoices->name = $staff->name;
            $invoices->email = $staff->email;
            $invoices->module = "Invoices";
            $invoices->read_only = 1;
            $invoices->write_only = 1;
            $invoices->update_only = 1;
            $invoices->delete_only = 1;
            $invoices->read_write_only = 1;
            $invoices->save();

            $payments = new UserRoles();
            $payments->user_id = $staff->id;
            // $payments->subscriber_id = $staff->added_by;
            $payments->name = $staff->name;
            $payments->email = $staff->email;
            $payments->module = "Payments";
            $payments->read_only = 1;
            $payments->write_only = 1;
            $payments->update_only = 1;
            $payments->delete_only = 1;
            $payments->read_write_only = 1;
            $payments->save();

            $reports = new UserRoles();
            $reports->user_id = $staff->id;
            // $reports->subscriber_id = $staff->added_by;
            $reports->name = $staff->name;
            $reports->email = $staff->email;
            $reports->module = "Reports";
            $reports->read_only = 1;
            $reports->write_only = 1;
            $reports->update_only = 1;
            $reports->delete_only = 1;
            $reports->read_write_only = 1;
            $reports->save();

            $subscription = new UserRoles();
            $subscription->user_id = $staff->id;
            // $subscription->subscriber_id = $staff->added_by;
            $subscription->name = $staff->name;
            $subscription->email = $staff->email;
            $subscription->module = "Subscription";
            $subscription->read_only = 1;
            $subscription->write_only = 1;
            $subscription->update_only = 1;
            $subscription->delete_only = 1;
            $subscription->read_write_only = 1;
            $subscription->save();

            $settings = new UserRoles();
            $settings->user_id = $staff->id;
            // $settings->subscriber_id = $staff->added_by;
            $settings->name = $staff->name;
            $settings->email = $staff->email;
            $settings->module = "Settings";
            $settings->read_only = 1;
            $settings->write_only = 1;
            $settings->update_only = 1;
            $settings->delete_only = 1;
            $settings->read_write_only = 1;
            $settings->save();

            $support = new UserRoles();
            $support->user_id = $staff->id;
            // $support->subscriber_id = $staff->added_by;
            $support->name = $staff->name;
            $support->email = $staff->email;
            $support->module = "Support";
            $support->read_only = 1;
            $support->write_only = 1;
            $support->update_only = 1;
            $support->delete_only = 1;
            $support->read_write_only = 1;
            $support->save();
        }
    }
    public function export_users()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function export_clients()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function set_timezone()
    {
        $user = Auth::user();
        if ($user) {
            // date_default_timezone_set($user->timezone);
        }
    }

    public function check_login()
    {
        $user = Auth::user();
        if( $user->status == 'true'){
            if ($user) {
                return $user;
            } else {
                $user = auth()->guard('affiliates')->user();
                if ($user) {
                    $user = User::where('status', 'true')->where('email', $user->email)->first();
                    $user['type_user'] = 'affiliate';
                    return $user;
                }
                Auth::logout();
                Session::flush();
                return redirect()->route('login');
            }
        }
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->user_type == 'admin') {
                return redirect()->route('admin_profile');
            }
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
            $this->set_timezone();
            $page = "index";
            $price_plans = Membership::orderBy('created_at', 'asc')->get();
            $features = Features::get();
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $myplan = Membership::where('plan_name', '=', $user->membership)->first();
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
                $myplan = Membership::where('plan_name', '=', $subscriber->membership)->first();
            }
            $total_users = User::where('added_by', '=', $subscriber->id)->get();
            $total_clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            $discounts = Offers::get();
            return view('web.index', compact('user', 'page', 'features', 'price_plans', 'myplan', 'subscriber', 'total_users', 'total_clients', 'discounts'));
        } else {
            $page = "index";
            $price_plans = Membership::orderBy('created_at', 'asc')->get();
            $features = Features::get();
            $myplan = null;
            $subscriber = null;
            $total_users = 0;
            $total_clients = 0;
            $discounts = Offers::get();
            return view('web.index', compact('page', 'features', 'price_plans', 'myplan', 'subscriber', 'total_users', 'total_clients', 'discounts'));
            // return redirect()->route('login');
        }
    }

    public function send_email()
    {
        $data["email"] = "sandeepkumarsangwal21@gmail.com";
        $data["title"] = "From Sandeep";
        $data["body"] = "This is Demo for file";

        $files = [
            asset('web_assets/images/50count.png'),
            asset('web_assets/images/100client.png'),
        ];
        Mail::send('web.subscriptiontemplate', $data, function ($message) use ($data, $files) {
            $message->to($data["email"], $data["email"])
                ->subject("adwiseri Subscription Added");

            foreach ($files as $file) {
                $message->attach($file);
            }
        });
        // Mail::to("sandeepkumarsangwal21@gmail.com")->send(new SubscriptionMail());
        if (Mail::failures()) {
            echo "Mail not Sent";
        } else {
            echo "Success";
        }
    }

    public function get_demo()
    {
        $countries = Countries::get();
        return view('web.book_demo', compact('countries'));
    }

    public function demo_post(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|min:9|max:12|unique:demo_requests',
                'email' => 'required|string|email|max:255|unique:demo_requests',
                'country' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'how_did_hear' => 'required|in:LinkedIn,Twitter,YouTube,Industry friend,Google', // Validation rule for "how_did_hear"
                'terms' => 'accepted',
                'g-recaptcha-response' => 'required|captcha'
            ]
        );
        $demo = new DemoRequests();
        $demo->name = $request['name'];
        $demo->phone = $request['phone'];
        $demo->email = $request['email'];
        $demo->country = $request['country'];
        $demo->city = $request['city'];
        $demo->terms_accepted_at = now();
        $demo->status = "Pending";
        $demo->company_name = $request['company_name'];
        $demo->job_title = $request['job_title'];
        $demo->how_did_hear = $request['how_did_hear'];
        $demo->save();
        Mail::to("seimpex1@gmail.com")->send(new EmailVerification($demo));
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Success';
        }
        Mail::to("care@adwiseri.com")->send(new EmailVerification($demo));
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Success';
            return back()->with('submitted', 'Demo request submitted successfully.');
        }
    }

    public function email_subscription(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:email_subscriptions',
        ], [
            
        ]);
        $subscription = new EmailSubscriptions();
        $subscription->email = $request['email'];
        $subscription->save();
        Mail::to($request['email'])->send(new SubscriptionMail());
        if (Mail::failures()) {
            echo "Mail not Sent";
        } else {
            echo "Success";
        }

        return redirect()->route('/')->with('subscribed', 'Email subscription submitted successfully.');
    }

    public function emailtemplate()
    {
        $user = Auth::user();
        $this->set_timezone();
        return view('web.emailtemplate');
    }

    public function login()
    {

        if (Auth::user()) {
            $user = Auth::user();
            if ($user->status != "true") {
                Auth::logout();
                Session::flush();
                return redirect()->route('login')->with('deactivated', "Your account is deactivated.");
            }
            $this->set_timezone();
            if ($user->organization != "") {
                if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                    return redirect()->route('userprofile')->with("price_plan_expiry", "Please renew or upgrade price plan.");
                } else {
                    return redirect()->route('userprofile');
                }
            } else {
                $this->set_timezone();
                $countries = Countries::all();
                $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                $states = States::all();
                $page = "index";
                return view('web.moredetails', compact('user', 'countries', 'states', 'page', 'tzlist'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function user_register($ref = null)
    {
        // $this->set_timezone();
        if ($ref != null) {
            $referral = $ref;
        } else {
            $referral = null;
        }
        $splan = null;
        $countries = Countries::get();
        $subscriber_categories = Subscriber_Categories::get();
        $membership = Membership::orderBy('price_per_year')->get();
        return view('web.register', compact('subscriber_categories', 'countries', 'membership', 'referral', 'splan'));
    }

    public function user_register_plan($plan = null)
    {
        // $this->set_timezone();
        if ($plan != null) {
            $splan = $plan;
        } else {
            $splan = null;
        }
        $referral = null;
        $countries = Countries::get();
        $subscriber_categories = Subscriber_Categories::get();
        $membership = Membership::orderBy('price_per_year')->get();
        return view('web.register', compact('subscriber_categories', 'countries', 'membership', 'referral', 'splan'));
    }

    public function check_registration(Request $request)
    {

        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|min:9|max:12|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'category' => 'required|string|max:255',
                'subcategory' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]
        );
        if ($request->referral != null) {
            $find_referral = User::where('referral', '=', $request->referral)->first();
            if (!$find_referral) {
                return redirect()->back()->withInput()->withError('Invalid Referral Code');
            }
        }
        if ($request->membership != "Free") {
            $data = $request->all();
            $data['duration'] = 1;
            Session::put('reg_data', $data);
            return redirect()->route('reg_pay');
        } else {
            $data = $request->all();
            Session::put('reg_data', $data);
            return redirect()->route('user_registration');
        }
    }

    public function reg_pay()
    {
        $data = session('reg_data');
        $user_data = array();
        foreach ($data as $key => $value) {
            $user_data[$key] = $value;
        }
        $plan = $data['membership'];
        $duration = $data['duration'];

        $membership = Membership::where('plan_name', '=', $plan)->first();
        // dd($membership);
        $plan_amt = $membership->price_per_year;
        if ($duration == 1) {
            $plan_amount = round(($plan_amt * 1) * 1);
        }
        if ($duration == 2) {
            $plan_amount = round(($plan_amt * 2) * 0.9);
        }
        if ($duration == 3) {
            $plan_amount = round(($plan_amt * 3) * 0.8);
        }
        if ($duration == 5) {
            $plan_amount = round(($plan_amt * 5) * 0.5);
        }
        $amount = $plan_amount;
        $user_data['amount'] = $amount;
        Session::put('reg_data', $user_data);
        return view('web.reg_pay', compact('amount'));
    }


    public function user_registration()
    {

        $request = session('reg_data');
        $data = new User();
        // $this->validate($request,
        // [
        //     'name' => 'required|string|max:255',
        //     'phone' => 'required|string|min:9|max:12|unique:users',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'category' => 'required|string|max:255',
        //     'subcategory' => 'required|string|max:255',
        //     'password' => 'required|string|min:8|confirmed',
        // ]);
        // if($request->referral != null){
        //     $find_referral = User::where('referral','=',$request->referral)->first();
        //     if(!$find_referral){
        //         return redirect()->back()->withInput()->withError('Invalid Referral Code');
        //     }
        // }

        if ($request['referral'] != null) {
            $find_referral = User::where('referral', '=', $request['referral'])->first();
        }
        $eotp = rand(10000, 99999);
        $plan = Membership::where('plan_name', '=', $request['membership'])->first();
        $data->user_type = "Subscriber";
        $data->name = $request['name'];
        $data->phone = $request['phone'];
        $data->email = $request['email'];
        $data->category = $request['category'];
        $data->sub_category = $request['subcategory'];
        $data->other_subcategory = $request['other'];
        // $data->membership = $request['membership'];
        $data->membership = $plan->plan_name;
        $data->membership_type = "Free";
        // if($request->membership == "Free"){
        // }
        // else{
        //     $data->membership_type = "Trial";
        //     $data->membership_expiry_date = (new DateTime("now"))->modify("+30 days");
        // }
        $enddate = (new DateTime("now"))->modify("+" . $plan->validity . " Days");
        $data->membership_start_date = new DateTime("now");
        $data->membership_expiry_date = $enddate;
        $data->wallet = 0;
        $data->referral = $this->get_referral();
        $data->referral_code = $request['referral'];
        $data->email_otp = $eotp;
        $data->timezone = "UTC";
        $data->status = "true";
        $data->password = Hash::make($request['password']);
        $data->save();

        $company = User::where('user_type', '=', 'admin')->first();
        $signupInvoiceAmount = strtolower((string) $plan->plan_name) === 'free'
            ? 0.0
            : (float) ($request['amount'] ?? $plan->price_per_year ?? 0);
        $internalInvoice = null;
        if ($company) {
            $internalInvoice = $this->createAdminApInvoiceAndPayment(
                $data,
                $company,
                $signupInvoiceAmount,
                "Manual",
                "Subscription Signup Fees"
            );
        }

        $role = UserRoles::where('user_id', '=', $data->id)->get();
        if ($role) {
            foreach ($role as $r) {
                $r->delete();
            }
        }
        $clients = new UserRoles();
        $clients->user_id = $data->id;
        // $clients->subscriber_id = '';
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
        // $applications->subscriber_id = '';
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
        // $communication->subscriber_id = '';
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
        // $invoices->subscriber_id = '';
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
        // $payments->subscriber_id = '';
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
        // $reports->subscriber_id = '';
        $reports->name = $data->name;
        $reports->email = $data->email;
        $reports->module = "Reports";
        $reports->read_only = 1;
        $reports->write_only = 1;
        $reports->update_only = 1;
        $reports->delete_only = 1;
        $reports->read_write_only = 1;
        $reports->save();

        $subscription = new UserRoles();
        $subscription->user_id = $data->id;
        // $subscription->subscriber_id = '';
        $subscription->name = $data->name;
        $subscription->email = $data->email;
        $subscription->module = "Subscription";
        $subscription->read_only = 1;
        $subscription->write_only = 1;
        $subscription->update_only = 1;
        $subscription->delete_only = 1;
        $subscription->read_write_only = 1;
        $subscription->save();

        $settings = new UserRoles();
        $settings->user_id = $data->id;
        // $settings->subscriber_id = '';
        $settings->name = $data->name;
        $settings->email = $data->email;
        $settings->module = "Settings";
        $settings->read_only = 1;
        $settings->write_only = 1;
        $settings->update_only = 1;
        $settings->delete_only = 1;
        $settings->read_write_only = 1;
        $settings->save();

        $support = new UserRoles();
        $support->user_id = $data->id;
        // $support->subscriber_id = '';
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
        $activity->user_id = $data->id;
        $activity->user_name = $data->name;
        $activity->activity_name = "New Subscriber Added";
        $activity->activity_detail = "New Subscriber " . $data->name . " registered at " . $request['local_time'];
        $activity->activity_icon = "user.png";
        $activity->local_time = $request['local_time'];
        $activity->save();
        if (isset($find_referral) &&  $plan->plan_name != 'Free') {
            $wallet = $find_referral->wallet;
            $find_referral->wallet = $wallet + 10;
            $find_referral->save();

            $save_referral = new Referrals();
            $save_referral->referral_code = $request['referral'];
            $save_referral->userid = $data->id;
            $save_referral->user_name = $data->name;
            $save_referral->total_amount = 10;
            $save_referral->amount_added = 10;
            $save_referral->previous_balance = $wallet;
            $save_referral->wallet_balance = $wallet + 10;
            $save_referral->save();

            $use_referral = new Used_referrals();
            $use_referral->referral_code = $request['referral'];
            $use_referral->subscriber_id = $data->id;
            $use_referral->commission_earnt = 10;
            $use_referral->save();

            $affiliate_commission = AffiliateCommissionEarnt::where('referral_code', $request['referral'])->first();
            if ($affiliate_commission) {

                $affiliate_commission->total_earned += 10;
                $affiliate_commission->save();
            } else {

                $use_referral = new AffiliateCommissionEarnt();
                $use_referral->referral_code = $request['referral'];
                $use_referral->total_earned = 10;
                $use_referral->save();
            }
        }
        $email = $data->email;
        $welcomedata = new \stdClass();
        $welcomedata->id = $data->id;
        $welcomedata->organization_logo = $data->organization_logo;
        $welcomedata->name = $data['name'];
        $welcomedata->email = $email;
        $welcomedata->plan_name = $plan->plan_name;
        $welcomedata->duration = $plan->validity . " Days";
        $welcomedata->amount = number_format((float) $signupInvoiceAmount, 2);
        $welcomedata->subscription_type = $plan->plan_name;
        $welcomedata->start_date = !empty($data->membership_start_date)
            ? (($data->membership_start_date instanceof \DateTimeInterface)
                ? $data->membership_start_date->format('d-m-Y')
                : date("d-m-Y", strtotime((string) $data->membership_start_date)))
            : '-';
        $welcomedata->end_date = !empty($data->membership_expiry_date)
            ? (($data->membership_expiry_date instanceof \DateTimeInterface)
                ? $data->membership_expiry_date->format('d-m-Y')
                : date("d-m-Y", strtotime((string) $data->membership_expiry_date)))
            : '-';
        $welcomedata->paid_amount = number_format((float) $signupInvoiceAmount, 2);

        if ($company) {
            $welcomedata->from_email = $company->email;
            $welcomedata->from_name = $company->organization ?: 'adwiseri';
        }
        if ($internalInvoice && $company) {
            $welcomedata->invoice_id = $internalInvoice->id;
            $welcomedata->token = $internalInvoice->token;
            $welcomedata->invoice_pdf_data = $this->buildInvoicePdfData($internalInvoice, $data, $company);
        }
        try {
            Mail::to($email)->cc('care@adwiseri.com')->send(new WelcomeMail($welcomedata));
        } catch (\Exception $e) {
            \Log::error('Welcome mail failed: ' . $e->getMessage());
        }
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Great! Successfully send in your mail';
        }

        $email = $request['email'];
        $maildata = new \stdClass();
        $maildata->name = $request['name'];
        $maildata->email = $email;
        $maildata->otp = $eotp;
        // return view('web.emailtemplate',compact('maildata'));
        try {
            Mail::to($email)->send(new EmailVerification($maildata));
        } catch (\Exception $e) {
            \Log::error('Verification mail failed: ' . $e->getMessage());
        }
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Great! Successfully send in your mail';
        }
        // $phone_otp = $request->phone_otp;
        // $email_otp = $request->email_otp;
        // return view('web.otp',compact('phone_otp','email_otp'));
        return redirect()->route('otp', $email);
    }

    public function send_otp(Request $request)
    {
        $eotp = rand(10000, 99999);
        $email = $request['email'];
        $usr = User::where('email', '=', $email)->first();
        if ($usr) {
            $usr->email_otp = $eotp;
            $usr->save();
            $maildata = new \stdClass();
            $maildata->name = $usr->name;
            $maildata->email = $email;
            $maildata->otp = $eotp;
            // return view('web.emailtemplate',compact('maildata'));
            Mail::to($email)->send(new EmailVerification($maildata));
            if (Mail::failures()) {
                echo 'Error';
            } else {
                echo 'Success';
            }
        }
    }

    public function get_states(Request $request)
    {
        // print_r($request->all());
        $country = $request['country'];
        // echo $country;
        // echo gettype($request['country']);
        $states = States::where('country_id', '=', $country)->orderBy('name')->get();
?>
        <option value="">Select State</option>
        <?php
        foreach ($states as $state) {
        ?>
            <option value="<?php echo $state->name; ?>"><?php echo $state->name; ?></option>
        <?php
        }
    }

    public function get_timezone(Request $request)
    {
        // print_r($request->all());
        $country = Countries::find($request['country']);
        $code = $country->country_code;
        $zones = MyTimezones::where('CountryCode', '=', $code)->get();
        ?>
        <?php
        foreach ($zones as $zone) {
        ?>
            <option value="<?php echo $zone->TimeZone; ?>"><?php echo $zone->TimeZone; ?></option>
        <?php
        }
    }

    public function get_application(Request $request)
    {
        // print_r($request->all());
       
        $id = $request['id'];

        // Get distinct application IDs that already exist in PaymentARs for this client
        $existingIds = PaymentARs::where('client_id', $id)
            ->distinct()
            ->pluck('application_id')
            ->toArray();

        // Debug check (optional)
        // dd($existingIds);
        $applications = '';
        if ($request->comm) {
            $applications = Applications::where('client_id', $id)->get();
        } else {
            $applications = Applications::where('client_id', $id)
                ->whereNull('assign_to')
                ->get();
        }

        $html = '<option value="">Select Application</option>';

        foreach ($applications as $app) {
            // 🧠 Skip if this application already exists in PaymentARs
            if (!in_array($app->id, $existingIds)) {
                $html .= '<option value="' . $app->application_id . '" data-name="' . $app->application_name . '">';
                $html .= $app->application_name . ' (' . $app->application_id . ')';
                $html .= '</option>';
            }
        }

        // Add the "Other" option at the bottom
        $html .= '<option value="Other">Other</option>';

        return $html;
    }

    public function get_sub_category(Request $request)
    {
       
        $category = $request['category'];
        
            $sub_categories = Subscriber_Sub_Categories::where('status','Active')->where('category_name', '=', $category)->get();
            ?>
            <option value="">Select Sub-Category</option>
            <?php
            foreach ($sub_categories as $subcategory) {
            ?>
                <option value="<?php echo $subcategory->sub_category_name; ?>"><?php echo $subcategory->sub_category_name; ?></option>
            <?php
            }
       
    }

    public function check_user_limit(Request $request)
    {
        // print_r($request->all());
        $subs = $request['subscriber'];
        // echo $country;
        // echo gettype($request['country']);
        $subscriber = User::find($subs);
        $siteusers = User::where('added_by', '=', $subscriber->id)->get();
        $membership_plan = Membership::where('plan_name', '=', $subscriber->membership)->first();
        if (count($siteusers) < $membership_plan->no_of_users) {
            return response()->json(['limit' => 'not full']);
        } else {
            return response()->json(['limit' => 'full']);
        }
    }

    public function check_client_limit(Request $request)
    {
        // print_r($request->all());
        $subs = $request['subscriber'];
        // echo $country;
        // echo gettype($request['country']);
        $subscriber = User::find($subs);
        $clients =  $subscriber ? Clients::whereNotNull('subscriber_id')->where('subscriber_id', '=', $subscriber->id)->with('subscriber')->get() :null;
        $membership_plan = Membership::where('plan_name', '=', $subscriber->membership)->first();
        if ($membership_plan->client_limit != "Unlimited") {
            if (count($clients) < $membership_plan->client_limit) {
                return response()->json(['limit' => 'not full','clients'=>$clients]);
            } else {
                return response()->json(['limit' => 'full']);
            }
        } else {
            return response()->json(['limit' => 'not full','clients'=>$clients]);
        }
    }

    public function dashboard()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $user->id)->get();
            $assignments = Application_assignments::where('subscriber_id', '=', $subscriber->id)->get();
            $users = User::where('added_by', '=', $user->id)->get();
            $totalPayments = PaymentARs::where('type','ap')->where('subscriber_id', '=', $user->id)->sum(DB::raw('amount - paid_amount'));
            $totalPaymentsAR = PaymentARs::where('type','aR')->where('subscriber_id', '=', $user->id)->sum(DB::raw('amount - paid_amount'));
            $meetings = Client_discussions::where('subscriber_id', $user->id)->get();
            $paymentARs =PaymentARs::where('subscriber_id', '=', $user->id)->get();
        } else {
            return redirect()->route('userprofile');
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('subscriber_id', '=', $user->added_by)->get();
            $assignments = Application_assignments::where('subscriber_id', '=', $subscriber->id)->get();
            $users = User::where('added_by', '=', $user->added_by)->get();
            $totalPayments = PaymentARs::where('type','ap')->where('subscriber_id', '=', $subscriber->added_by)->sum(DB::raw('amount - paid_amount'));
            $totalPaymentsAR = PaymentARs::where('type','ar')->where('subscriber_id', '=', $subscriber->added_by)->sum(DB::raw('amount - paid_amount'));
            $meetings = Client_discussions::where('subscriber_id', $subscriber->added_by)->get();
            $paymentARs =PaymentARs::where('subscriber_id', $subscriber->added_by)->get();
        }
        $invoices = Invoices::get();
        $countries = Countries::all();
        $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();
        $payments = Invoices::where('user_id', '=', $subscriber->id)->get();
        $total_countries = array();
        foreach ($applications as $apps) {
            $categ = $apps->application_name;
            $categ_app = 0;
            foreach ($applications as $app) {
                if ($categ == $app->application_name) {
                    $categ_app += 1;
                }
            }
            $total_countries[$categ] = $categ_app;
        }
        $total_assignments = array();
        foreach ($assignments as $assign) {
            $categ = $assign->user_id;
            $categ_app = 0;
            foreach ($assignments as $assig) {
                if ($categ == $assig->user_id) {
                    $categ_app += 1;
                }
            }
            $total_assignments[$assign->user_name . '(' . $categ . ')'] = $categ_app;
        }
        $total_clients = array();
        foreach ($countries as $contry) {
            $categ = $contry->country_name;
            $categ_app = 0;
            foreach ($clients as $clint) {
                if ($categ == $clint->country) {
                    $categ_app += 1;
                }
            }
            $total_clients[$categ] = $categ_app;
        }

        $grouped_data_payment_mode = $paymentARs->groupBy('payment_mode');

            // Initialize the final result array
            $total_payments = [];

            foreach ($grouped_data_payment_mode as $payment_mode => $payments) {
                // Store the total count for each payment mode
                $total_payments[$payment_mode] = [
                    'total_transactions' => $payments->count(),
                    'total_amount' => $payments->sum('amount'), // Assuming `amount` is a column in the `PaymentARs` model
                ];
            }
        $states = States::all();
        $page = "dashboard";
        $activities = Activities::where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->limit(15)->get();
        $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();
        $invoices = Internal_Invoices::where('subscriber_id', '=', $subscriber->id)->get();
        $invoiceARCount = $invoices->filter(function ($invoice) {
            return strtolower((string) $invoice->type) === 'ar';
        })->count();
        $invoiceAPCount = $invoices->filter(function ($invoice) {
            return strtolower((string) $invoice->type) === 'ap';
        })->count();
        // $referrals = Referrals::where('referral_code', '=', $subscriber->referral)->get();
        // $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        // $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        //  $referrals = Referrals::where('debit_amount', '=', null)->whereBetween('created_at', [$startDate, $endDate])->whereNotIn('type', ['one_off', 'double_term', 'cashback'])->orderBy('created_at', 'desc')->get();
        $user = auth()->user();
        $query = Referrals::join('users', 'referrals.userid', '=', 'users.id')
                            // ->whereBetween('users.created_at', [$startDate, $endDate]) // Filter by user creation date
                            ->where('users.user_type', 'Subscriber') // Ensure user_type is 'Subscriber'
                            ->whereNotNull('users.referral_code') // Ensure referral_code exists
                            ->whereNull('referrals.debit_amount') // Ensure debit_amount is null
                            ->orderBy('referrals.created_at', 'desc') // Order by referral creation date
                            ->select('referrals.*'); // Select all columns from referrals
        if($user->user_type == 'Subscriber'){
            $query = $query->where('users.referral_code', $user->referral);// Apply referral code filter for Subscriber
                       
        }
        $referrals = $query->where('referrals.type', 'Referral Commission') // Apply specific condition for Subscriber
        ->get();

        return view('web.dashboard', compact('meetings', 'totalPayments','totalPaymentsAR', 'invoiceARCount', 'invoiceAPCount', 'user', 'countries', 'total_countries', 'total_clients', 'total_payments', 'states', 'page', 'clients', 'users', 'activities', 'applications', 'invoices', 'referrals', 'total_assignments'));
    }

    public function analytics()
    {
        $user = auth()->user();
        // if ( (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
        //     return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        // }

        $this->set_timezone();

        $activity = new Activities();
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "Performed Analytics";
        $activity->activity_detail = "Analytics Performed by " . $user->name . " at " . date('d M, Y H:i:s');
        $activity->activity_icon = "user.png";
        $activity->save();


        $subscribers = User::where('added_by', auth()->user()->id)->where('user_type', 'User')->where('name', '!=', 'ADMIN (adwiseri.com)')->pluck('id', 'name');
        $page = "analytics";
        $countries = Countries::get();
        return view('web.analytics', compact('page', 'user', 'subscribers','countries'));
    }
    public function client()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('membership')->with('membership_expiry', 'Membership has expired.');
            }
            $clients = Clients::where('subscriber_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $subscriber = User::find($user->added_by);
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if ((new DateTime($subscriber->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('membership')->with('membership_expiry', 'Membership has expired.');
            }
            $clients = Clients::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
        }
        $countries = Countries::get();
        $page = "clients";
        return view('web.client', compact('user', 'clients', 'page', 'roles','countries'));
    }

    public function users()
    {
        $user = $this->check_login();
        // echo'<pre>';print_r($user);echo'</pre>';exit();
        if ($user->user_type != 'admin' && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();

        if ($user->user_type == 'admin') {

            $siteusers = User::orderBy('created_at', 'desc')->get();
        } else {

            $siteusers = User::where('added_by', '=', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $page = "users";

        if ($user->user_type != 'admin' && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('membership')->with('membership_expiry', 'Membership has expired.');
        } else {
            if (request()->ajax()) {
                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();

                $siteusers = $siteusers->whereBetween('created_at', [$startDate, $endDate]);
                return DataTables::of($siteusers)
                    ->editColumn('status', function ($row) {
                        $html = '';
                        if ($row->status == 'true') {
                            $html .= '<a style="background:green;border-color:green;" href="#" onclick="userstatus(' . $row->id . ')" class="p-0 px-1">Active</a>';
                        } else {
                            $html .= '<a style="background:red;border-color:red;" href="#" onclick="userstatus(' . $row->id . ')" class="p-0 px-1">Inactive</a>';
                        }
                        return $html;
                    })
                    ->addColumn('action', function ($row) {
                        $html = '';
                        $html .= '<a href="' . route('siteuser_profile', $row->id) . '" style="text-decoration:none; background:none; border:none">';
                        $html .= '<i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i>';
                        $html .= '</a>';
                        return $html;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
            return view('web.users', compact('user', 'siteusers', 'page'));
        }
    }

    public function timezone_test()
    {
        $this->set_timezone();
        // $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        // $countries = Countries::get();
        // foreach($countries as $key => $country){
        //     echo $key+1 . " = ";
        //     echo $country->timezones;
        //     echo "<br><br>";
        // }
        // foreach($tzlist as $key => $zone){
        //     echo $key . " = " . $zone . "<br><br>";
        // }
    }

    public function add_user()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $siteusers = User::where('added_by', '=', $user->id)->get();
        $job_roles = Job_roles::where('user_id', '=', $user->id)->get();
        $membership_plan = Membership::where('plan_name', '=', $user->membership)->first();
        if (count($siteusers) < $membership_plan->no_of_users) {
            $countries = Countries::get();
            $page = "users";
            return view('web.add_user', compact('user', 'countries', 'page', 'job_roles', 'tzlist'));
        } else {
            return back()->with('user_limit', 'Upgrade membership to add more users.');
        }
    }

    public function add_client()
    {
        // $user = $this->check_login();
        $user = auth()->user();
        // Check if the user's membership has expired
        // if($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))){
        //     return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        // }

        // Set the timezone
        $this->set_timezone();

        // Determine subscriber (either direct or through 'added_by' relationship)
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }

        // Fetch existing clients for this subscriber
        $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();

        // Fetch the user's membership plan details
        $membership_plan = Membership::where('plan_name', '=', $user->membership)->first();

        // Check if the membership plan has a defined client limit
        if ($membership_plan->client_limit != "Unlimited") {

            // Calculate the client limit based on the subscription duration
            $subscription_duration_years = $this->calculate_subscription_duration($user); // In years
            $client_limit_per_year = $membership_plan->client_limit; // Get the yearly client limit from the plan
            $total_client_limit =  ($subscription_duration_years) ? $client_limit_per_year * $subscription_duration_years :  $client_limit_per_year; // Total limit over the subscription period

            // Check if the current number of clients exceeds the available client limit
            if (count($clients) >= $total_client_limit) {
                return back()->with('client_limit', 'Upgrade membership to add more clients.');
            }
        }

        // Fetch job roles based on the subscriber's user ID
        $job_roles = Job_roles::where('user_id', '=', $subscriber->id)->get();

        // Fetch available countries
        $countries = Countries::get();

        // Determine the page context
        $page = "clients";

        // Fetch client jobs based on subscriber's category and sub-category
        if ($subscriber->category == "Law Firm") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } elseif ($subscriber->category == "Travel Agency") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } else {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)
                ->where('sub_category', '=', $subscriber->sub_category)
                ->get();
        }

        // Return the view to add a client
        return view('web.add_client', compact('user', 'countries', 'page', 'job_roles', 'client_jobs'));
    }


    public function calculate_subscription_duration($user)
    {
        $current_date = new DateTime();
        $expiry_date = new DateTime($user->membership_expiry_date);
        $interval = $current_date->diff($expiry_date);

        return $interval->y; // Return the number of years in the subscription
    }
    public function add_new_user(request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
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
        $data->user_type = "User";
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
        $activity->activity_name = "New User Added";
        $activity->activity_detail = "New user " . $request->name . " added by " . $user->name . " for " . $request->designation . " job role at " . $request->local_time;
        $activity->activity_icon = "user.png";
        $activity->local_time = $request->local_time;
        $activity->save();
        return redirect()->route('users')->with('user_added', "User added successfully.");
    }

    public function add_new_client(request $request)
    {
        // print_r($request->all());
        //  exit();
        function job_id()
        {
            $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $id = "";
            for ($i = 0; $i < 8; $i++) {
                $id = $id . $ch[rand(0, strlen($ch) - 1)];
            }
            return $id;
        }
        $user = Auth::user();
        $this->set_timezone();
        $data = new Clients();
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|min:9|max:12|unique:clients',
                'email' => 'required|string|email|max:255|unique:clients',
                'nationality' => 'required|string',
                // 'passport_no' => 'required|string',
                // 'dob' => 'required',
                'country' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required|string|max:255',
                'pincode' => 'required',
                // 'job_role' => 'required|string|max:255',
                // 'job_open_date' => 'required',
                // 'job_status' => 'required|string|max:255',
            ]
        );
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $data->subscriber_id = $subscriber->id;
        } else {
            $subscriber = User::find($user->added_by);
            $data->subscriber_id = $subscriber->id;
        }
        $country = Countries::find($request->country);
        $nationality = Countries::find($request->nationality);
        $data->user_id = $user->id;
        $data->name = $request['name'];
        $data->phone = $request['phone'];
        $data->email = $request['email'];
        $data->alternate_no = $request['alternate_no'];
        $data->nationality = $nationality->country_name;
        $data->passport_no = $request['passport_no'];
        $data->dob = $request['dob'];
        $data->address = $request['address'];
        $data->country = $country->country_name;
        $data->state = $request['state'];
        $data->city = $request['city'];
        $data->pincode = $request['pincode'];
        $data->save();
        $activity = new Activities();
        $activity->subscriber_id = $subscriber->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "New Client Added";
        if ($user->user_type == "Subscriber") {
            $activity->activity_detail = "New client " . $request->name . " added by " . $user->name . " for " . $request->job_role . " job at " . $request->local_time;
        } else {
            $activity->activity_detail = "New client " . $request->name . " added by " . $user->name . "(" . $subscriber->name . ") for " . $request->job_role . " job at " . $request->local_time;
        }
        $activity->activity_icon = "user.png";
        $activity->local_time = $request->local_time;
        $activity->save();
        // $application = new Applications();
        // $application->client_id = $data->id;
        // $application->subscriber_id = $subscriber->id;
        // $application->application_id = job_id();
        // $application->application_category = $subscriber->category;
        // $application->application_subcategory = $subscriber->sub_category;
        // $application->application_name = $request['job_role'];
        // $application->application_country = $request['visa_country'];
        // $application->application_detail = $request['job_detail'];
        // $application->application_program = $request['study_program'];
        // $application->application_status = $request['job_status'];
        // $application->start_date = $request['job_open_date'];
        // $application->end_date = $request['job_completion_date'];
        // $application->save();
        // $activity = new Activities();
        // $activity->subscriber_id = $subscriber->id;
        // $activity->user_id = $user->id;
        // $activity->user_name = $user->name;
        // $activity->activity_name = "New Application Added";
        // if ($user->user_type == "Subscriber") {
        //     $activity->activity_detail = "New Application of " . $request->job_role . " added by " . $user->name . " at " . $request->local_time;
        // } else {
        //     $activity->activity_detail = "New Application of " . $request->job_role . " added by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
        // }
        // $activity->activity_icon = "user.png";
        // $activity->local_time = $request->local_time;
        // $activity->save();
        return redirect()->route('client')->with('client_added', "Client added successfully.");
    }

    public function update_user(request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $user_update = Auth::user();
            if (isset($request->moredetails)) {
                $this->validate(
                    $request,
                    [
                        'organization' => 'required|string|max:255',
                        'designation' => 'required|string|max:255',
                        'employee_strength' => 'required|string|max:255',
                        'address_line' => 'required|string|max:255',
                        'country' => 'required',
                        'state' => 'required',
                        'city' => 'required|string|max:255',
                        'pincode' => 'required|string',
                    ]
                );
                $country = Countries::find($request->country);
                $user_update->organization = $request['organization'];
                $user_update->designation = $request['designation'];
                $user_update->employee_strength = $request['employee_strength'];
                $user_update->address_line = $request['address_line'];
                $user_update->country = $country->country_name;
                $user_update->state = $request['state'];
                $user_update->city = $request['city'];
                $user_update->pincode = $request['pincode'];
                $user_update->timezone = $request['timezone'];
                $crcode = $country->currency;
                $currency = Currency::where('currency_code', '=', $crcode)->first();
                if ($currency) {
                    $user_update->currency = $currency->currency_code . "(" . $currency->currency_symbol . ")";
                } else {
                    $user_update->currency = "USD($)";
                }
                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") Updates his profile at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return redirect()->route('login');
            } elseif (isset($request->profile)) {
                $country = Countries::find($request->country);
                $user_update->name = $request['name'];
                $user_update->phone = $request['phone'];
                $user_update->organization = $request['organization'];
                $user_update->designation = $request['designation'];
                $user_update->employee_strength = $request['employee_strength'];
                $user_update->address_line = $request['address_line'];
                $user_update->country = $country->country_name;
                $user_update->state = $request['state'];
                $user_update->city = $request['city'];
                $user_update->pincode = $request['pincode'];
                $user_update->timezone = $request['timezone'];
                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") Updates his profile at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('success', 'Profile Updated Successfully!');
            } elseif (isset($request->profile_image)) {
                if ($request->hasFile('profile_img')) {
                    $file = $request->file('profile_img');
                    $extension = $file->getClientOriginalName();
                    $filename = time() . $extension;
                    $file->move('web_assets/users/user' . $user_update->id . '/', $filename);
                    $user_update->profile_img = $filename;
                }
                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") Updates his profile at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('success', 'Profile Updated Successfully!');
            } elseif (isset($request->logo_image)) {
                if ($request->hasFile('organization_logo')) {
                    $file = $request->file('organization_logo');
                    $extension = $file->getClientOriginalName();
                    $filename = time() . $extension;
                    $file->move('web_assets/users/user' . $user_update->id . '/', $filename);
                    $user_update->organization_logo = $filename;
                }
                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Organization Logo Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates organization logo at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") Updates organization logo at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('logo_updated', 'Logo Updated Successfully!');
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function update_user_affiliate(request $request)
    {

        $user = auth()->guard('affiliates')->user();
        $affiliateUser = User::where('email', $user->email)->first();
        $this->set_timezone();
        if ($user) {

            $user_update = $user;
            User::where('email', $user->email)->update(
                [
                    'timezone' => $request->timezone,
                ]
            );
            if (isset($request->moredetails)) {
                $this->validate(
                    $request,
                    [
                        'organization' => 'required|string|max:255',
                        'designation' => 'required|string|max:255',
                        'employee_strength' => 'required|string|max:255',
                        'address_line' => 'required|string|max:255',
                        'country' => 'required',
                        'state' => 'required',
                        'city' => 'required|string|max:255',
                        'pincode' => 'required|string',
                    ]
                );
                $country = Countries::find($request->country);
                $user_update->organization = $request['organization'];
                $user_update->designation = $request['designation'];
                $user_update->employee_strength = $request['employee_strength'];
                $user_update->address_line = $request['address_line'];
                $user_update->country = $country->country_name;
                $user_update->state = $request['state'];
                $user_update->city = $request['city'];
                $user_update->pincode = $request['pincode'];
                $user_update->timezone = $request['timezone'];
                $crcode = $country->currency;
                $currency = Currency::where('currency_code', '=', $crcode)->first();
                if ($currency) {
                    $user_update->currency = $currency->currency_code . "(" . $currency->currency_symbol . ")";
                } else {
                    $user_update->currency = "USD($)";
                }
                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") Updates his profile at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return redirect()->route('login');
            } elseif (isset($request->profile)) {
                $country = Countries::find($request->country);
                $user_update->name = $request['name'];
                $user_update->email = $request['email'];
                $user_update->phone = $request['phone'];

                $user_update->country = $country->country_name;
                $user_update->city = $request['city'];

                $user_update->save();
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;


                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('success', 'Profile Updated Successfully!');
            } elseif (isset($request->profile_image)) {
                if ($request->hasFile('profile_img')) {
                    $file = $request->file('profile_img');
                    $extension = $file->getClientOriginalName();
                    $filename = time() . $extension;
                    $file->move('web_assets/users/user' . $affiliateUser->id . '/', $filename);
                    $affiliateUser->profile_img = $filename;
                }
                $affiliateUser->save();
                $activity = new Activities();
                // $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "User Profile Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "" . $user->name . " Updates his profile at " . $request->local_time;
                } else {
                    $activity->activity_detail = "" . $user->name . "(" . $affiliateUser->name . ") Updates his profile at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('success', 'Profile Updated Successfully!');
            } elseif (isset($request->logo_image)) {
                if ($request->hasFile('organization_logo')) {
                    $file = $request->file('organization_logo');
                    $extension = $file->getClientOriginalName();
                    $filename = time() . $extension;
                    $file->move('web_assets/users/user' . $affiliateUser->id . '/', $filename);
                    $affiliateUser->organization_logo = $filename;
                }
                $affiliateUser->save();
                $activity = new Activities();
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Organization Logo Updated";
                $activity->activity_detail = "" . $user->name . "(" . $affiliateUser->name . ") Updates organization logo at " . $request->local_time;
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('logo_updated', 'Logo Updated Successfully!');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function update_siteuser(request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            $this->set_timezone();
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $siteuser = User::find($request->id);
            if ($siteuser) {
                $siteuser_update = User::find($request->id);
                if (isset($request->profile)) {
                    $country = Countries::find($request->country);
                    $siteuser_update->name = $request['name'];
                    $siteuser_update->phone = $request['phone'];
                    $siteuser_update->dob = $request['dob'];
                    $siteuser_update->organization = $request['organization'];
                    $siteuser_update->designation = $request['designation'];
                    $siteuser_update->employee_strength = $request['employee_strength'];
                    $siteuser_update->address_line = $request['address_line'];
                    $siteuser_update->country = $country->country_name;
                    $siteuser_update->state = $request['state'];
                    $siteuser_update->city = $request['city'];
                    $siteuser_update->pincode = $request['pincode'];
                    $siteuser_update->timezone = $request['timezone'];
                    $siteuser_update->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "User Profile Updated";
                    $activity->activity_detail = "" . $user->name . " Updated profile of his staff " . $siteuser_update->name . " at " . $request->local_time;
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return back()->with('success', 'Profile Updated Successfully!');
                } elseif (isset($request->profile_image)) {
                    if ($request->hasFile('profile_img')) {
                        $file = $request->file('profile_img');
                        $extension = $file->getClientOriginalName();
                        $filename = time() . $extension;
                        $file->move('web_assets/users/user' . $siteuser_update->id . '/', $filename);
                        $siteuser_update->profile_img = $filename;
                    }
                    $siteuser_update->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "User Profile Updated";
                    $activity->activity_detail = "" . $user->name . " Updated profile of his staff " . $siteuser_update->name . " at " . $request->local_time;
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return back()->with('success', 'Profile Updated Successfully!');
                }
            } else {
                return back();
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function change_password(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }
        $old_password = $request->old_password;
        if (password_verify($old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "User Changed his Password";
            if ($user->user_type == "Subscriber") {
                $activity->activity_detail = "" . $user->name . " changed his password at " . $request->local_time;
            } else {
                $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") changed his password at " . $request->local_time;
            }
            $activity->activity_icon = "user.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('password_changed', 'Password changed successfully.');
        } else {
            return back()->with('wrong_password', 'The old password is incorrect.');
        }
    }
    public function change_password_affiliate(Request $request)
    {
        $user = auth()->guard('affiliates')->user();
        $subscriber = $user;

        $old_password = $request->old_password;
        if (password_verify($old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "User Changed his Password";
            if ($user->user_type == "Affiliate") {
                $activity->activity_detail = "" . $user->name . " changed his password at " . $request->local_time;
            } else {
                $activity->activity_detail = "" . $user->name . "(" . $subscriber->name . ") changed his password at " . $request->local_time;
            }
            $activity->activity_icon = "user.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            Auth::logout();
            Session::flush();
            return redirect()->route('Affiliates.create')->with('password_changed', 'Password changed successfully.');
        } else {
            return back()->with('wrong_password', 'The old password is incorrect.');
        }
    }

    public function update_client(request $request)
    {
        $user = Auth::user();
        $this->set_timezone();

        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $client = Clients::find($request->id);
            if ($client) {
                $client_update = Clients::find($request->id);
                if (isset($request->profile)) {
                    $country = Countries::find($request->country);
                    $nationality = Countries::find($request->nationality);
                    $client_update->name = $request['name'];
                    $client_update->phone = $request['phone'];
                    $client_update->email = $request['email'];
                    $client_update->alternate_no = $request['alternate_no'];
                    $client_update->nationality = $nationality->country_name;
                    $client_update->passport_no = $request['passport_no'];
                    $client_update->dob = $request['dob'];
                    $client_update->address = $request['address'];
                    $client_update->country = $country->country_name;
                    $client_update->state = $request['state'];
                    $client_update->city = $request['city'];
                    $client_update->pincode = $request['pincode'];
                    $client_update->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->client_id = $request->id;
                    $activity->activity_name = "Client Profile Updated";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = $user->name . " Updates client " . $client->name . " profile at " . $request->local_time;
                    } else {
                        $activity->activity_detail = $user->name . "(" . $subscriber->name . ") Updates client " . $client->name . " profile at " . $request->local_time;
                    }
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return back()->with('success', 'Profile Updated Successfully!');
                } elseif (isset($request->profile_image)) {
                    if ($request->hasFile('profile_img')) {
                        $file = $request->file('profile_img');
                        $extension = $file->getClientOriginalName();
                        $filename = time() . $extension;
                        $file->move('web_assets/users/client' . $client_update->id . '/', $filename);
                        $client_update->profile_img = $filename;
                    }
                    $client_update->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->client_id = $request->id;
                    $activity->activity_name = "Client Profile Updated";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = $user->name . " Updates client " . $client->name . " profile at " . $request->local_time;
                    } else {
                        $activity->activity_detail = $user->name . "(" . $subscriber->name . ") Updates client " . $client->name . " profile at " . $request->local_time;
                    }
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return back()->with('success', 'Profile Updated Successfully!');
                } elseif (isset($request->job)) {
                    $client_update->job_id = $request['job_id'];
                    $client_update->job_detail = $request['job_detail'];
                    $client_update->job_open_date = $request['job_open_date'];
                    $client_update->job_status = $request['job_status'];
                    $client_update->job_completion_date = $request['job_completion_date'];
                    $client_update->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->client_id = $request->id;
                    $activity->activity_name = "Client Job Details Updated";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = $user->name . " Updates client " . $client->name . " job details at " . $request->local_time;
                    } else {
                        $activity->activity_detail = $user->name . "(" . $subscriber->name . ") Updates client " . $client->name . " job details at " . $request->local_time;
                    }
                    $activity->activity_icon = "job_icon.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return back()->with('success', 'Profile Updated Successfully!');
                }
            } else {
                return back();
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function userprofile()
    {
        // echo'<pre>';print_r(auth()->user());echo'</pre>';exit();
        $user = $this->check_login();
        if ($user->type_user != "affiliate" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $countries = Countries::all();
        $states = [];
        foreach ($countries as $country) {
            if ($country->country_name == $user->country) {
                $states = States::where('country_id', '=', $country->id)->get();
            }
        }
        $page = "profile";
        if ($user->user_type == 'Affiliate') {
            $user = auth()->guard('affiliates')->user();

            return view('affiliate.userprofile', compact('user', 'countries', 'states', 'page', 'tzlist'));
        }
        return view('web.userprofile', compact('user', 'countries', 'states', 'page', 'tzlist'));
    }
    public function userprofile_affiliate()
    {

        $affiliateUser = auth()->guard('affiliates')->user();
        if (!isset($affiliateUser)) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $countries = Countries::all();
        $user = User::where('email', $affiliateUser->email)->first();
        $page = "profile";

        return view('affiliate.userprofile', compact('affiliateUser', 'user', 'countries', 'page', 'tzlist'));
    }

    public function siteuser_profile($id = null)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if (!empty($id)) {
            $siteuser  = User::find($id);
            $this->set_timezone();
            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $countries = Countries::get();
            foreach ($countries as $country) {
                if ($country->country_name == $siteuser->country) {
                    $states = States::where('country_id', '=', $country->id)->get();
                }
            }
            $page = "users";
            return view('web.siteuser_profile', compact('siteuser', 'user', 'countries', 'states', 'page', 'tzlist'));
        } else { //view the page.
            return back();
        }
    }

    public function client_profile($id = null)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if (!empty($id)) { //edit the page.
            $client  = Clients::find($id);
            $this->set_timezone();
            $countries = Countries::get();
            foreach ($countries as $country) {
                if ($country->country_name == $client->country) {
                    $states = States::where('country_id', '=', $country->id)->get();
                }
            }
            // $states = States::get();
            $page = "clients";
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            $documents = Client_Docs::where('client_id', '=', $id)->get();
            $applications = Applications::where('client_id', '=', $client->id)->get();
            $messages = Messages::where('client_id', '=', $client->id)->orderBy('created_at', 'desc')->get();
            $activities = Activities::where('client_id', '=', $client->id)->orderBy('created_at', 'desc')->get();
            return view('web.client_profile', compact('client', 'user', 'countries', 'states', 'page', 'documents', 'activities', 'messages', 'applications', 'roles'));
        } else { //view the page.
            return back();
        }
    }

    public function generate_client_care_letter(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        $subscriber = $user->user_type == "Subscriber" ? $user : User::find($user->added_by);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'letter_type' => 'required|in:oisc_iaa,service_agreement',
            'application_type' => 'required|string|min:3|max:150',
            'application_name' => 'nullable|string|max:150',
            'consultation_date' => 'required|date',
            'immigration_status' => 'nullable|string|max:255',
            'client_instructions' => 'nullable|string|max:4000',
            'advice_given' => 'nullable|string|max:4000',
            'work_agreed' => 'nullable|string|max:4000',
            'estimated_timeline' => 'required|string|min:2|max:150',
            'key_dates' => 'nullable|string|max:1000',
            'fee_details' => 'nullable|string|max:1200',
            'fixed_fee' => 'nullable|string|max:100',
            'home_office_fee' => 'nullable|string|max:100',
            'ihs_fee' => 'nullable|string|max:100',
            'additional_costs' => 'nullable|string|max:1200',
            'vat_note' => 'nullable|string|max:255',
            'merits_of_case' => 'required|integer|min:0|max:100',
            'case_notes' => 'nullable|string|max:1500',
            'line_manager_name' => 'nullable|string|max:150',
            'line_manager_phone' => 'nullable|string|max:50',
            'line_manager_email' => 'nullable|email|max:150',
            'office_hours' => 'nullable|string|max:150',
            'complaint_handling_details' => 'nullable|string|max:1500',
            'oisc_registration_number' => 'nullable|string|max:100',
            'authorisation_level' => 'nullable|string|max:150',
            'allow_resend' => 'nullable|in:0,1',
            'correction_note' => 'nullable|string|max:500',
        ]);

        $client = Clients::findOrFail($validated['client_id']);

        $baseDocName = $validated['letter_type'] === 'oisc_iaa' ? 'Client Care Letter' : 'Service Agreement';

        $existingLetter = Client_Docs::where('client_id', $client->id)
            ->where('doc_name', 'like', $baseDocName . '%')
            ->orderByDesc('id')
            ->first();

        $allowResend = (int) ($validated['allow_resend'] ?? 0) === 1;

        if ($existingLetter && !$allowResend) {
            return back()->with('ccl_exists', $baseDocName . ' has already been sent for this client. Use resend only if details were incorrect.');
        }

        if ($existingLetter && $allowResend && empty($validated['correction_note'])) {
            return back()->withErrors(['correction_note' => 'Please add a correction note before re-sending the document.'])->withInput();
        }

        $letterData = [
            'client' => $client,
            'subscriber' => $subscriber,
            'prepared_by' => $user,
            'letter_type' => $validated['letter_type'],
            'document_title' => $baseDocName,
            'reference_no' => 'IMM/' . now()->format('ymd') . '/' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->name), 0, 3)),
            'issue_date' => now()->format('d-m-Y'),
            'consultation_date' => date('d F Y', strtotime($validated['consultation_date'])),
            'application_type' => $validated['application_type'],
            'application_name' => $validated['application_name'] ?? '-',
            'immigration_status' => $validated['immigration_status'] ?? 'As stated during consultation and based on documents shared.',
            'client_instructions' => $validated['client_instructions'] ?? 'As discussed with the adviser during initial consultation.',
            'advice_given' => $validated['advice_given'] ?? 'Advice provided based on information and documents shared by the client.',
            'work_agreed' => $validated['work_agreed'] ?? 'Preparation, review and submission support for the identified application.',
            'estimated_timeline' => $validated['estimated_timeline'],
            'key_dates' => $validated['key_dates'] ?? 'Key dates will be tracked and communicated in writing as the matter progresses.',
            'fee_details' => $validated['fee_details'] ?? 'Fees discussed during consultation and confirmed in writing.',
            'fixed_fee' => $validated['fixed_fee'] ?? '0',
            'home_office_fee' => $validated['home_office_fee'] ?? '0',
            'ihs_fee' => $validated['ihs_fee'] ?? '0',
            'additional_costs' => $validated['additional_costs'] ?? 'Additional costs may include translation, interpreter, courier and photocopying expenses.',
            'vat_note' => $validated['vat_note'] ?? 'No VAT will be charged unless otherwise stated in writing.',
            'merits_of_case' => $validated['merits_of_case'],
            'case_notes' => $validated['case_notes'] ?? '',
            'adviser_name' => $user->name,
            'adviser_phone' => $user->phone ?? '-',
            'adviser_email' => $user->email,
            'line_manager_name' => $validated['line_manager_name'] ?? 'N/A',
            'line_manager_phone' => $validated['line_manager_phone'] ?? '-',
            'line_manager_email' => $validated['line_manager_email'] ?? '-',
            'organisation_name' => $subscriber->organization ?: $subscriber->name,
            'organisation_address' => $subscriber->address ?: 'Address available on request.',
            'organisation_phone' => $subscriber->phone ?: '-',
            'organisation_email' => $subscriber->email,
            'office_hours' => $validated['office_hours'] ?? '9am to 5pm during weekdays',
            'complaint_handling_details' => $validated['complaint_handling_details'] ?? 'Please raise concerns first with your case adviser or their line manager in writing.',
            'oisc_registration_number' => $validated['oisc_registration_number'] ?? 'To be provided by organisation',
            'authorisation_level' => $validated['authorisation_level'] ?? 'Level 1',
            'correction_note' => $validated['correction_note'] ?? null,
        ];

        $pdf = Pdf::loadView('web.client_care_letter_pdf', $letterData)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        $folder = public_path('web_assets/users/client' . $client->id . '/docs/');
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $fileName = strtolower(str_replace(' ', '-', $baseDocName)) . '-' . $client->id . '-' . time() . '.pdf';
        file_put_contents($folder . $fileName, $pdf->output());
        // echo $fileName;exit();
        $document = new Client_Docs();
        $document->client_id = $client->id;
        $document->user_id = $user->id;
        $document->doc_name = $existingLetter && $allowResend ? $baseDocName . ' (Corrected)' : $baseDocName;
        $document->doc_file = $fileName;
        $document->save();

        $activity = new Activities();
        $activity->subscriber_id = $subscriber->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->client_id = $client->id;
        $activity->activity_name = $existingLetter && $allowResend ? $baseDocName . ' Re-Sent' : $baseDocName . ' Sent';
        $activity->activity_detail = $user->name . ' generated and emailed ' . $baseDocName . ' for ' . $client->name . ' at ' . ($validated['local_time'] ?? now()->format('d M, Y H:i:s'));
        $activity->activity_icon = 'doc.png';
        $activity->local_time = $validated['local_time'] ?? null;
        $activity->save();

        try {
            Mail::to($client->email)->send(new ClientCareLetterMail($letterData, $folder . $fileName));
            return back()->with('ccl_sent', $baseDocName . ' generated, saved to documents, and emailed to the client for signature.');
        } catch (\Exception $exception) {
            echo'<pre>';print_r($exception);exit();
            Log::error('Client care letter email sending failed.', [
                'client_id' => $client->id,
                'client_email' => $client->email,
                'document' => $fileName,
                'error' => $exception->getMessage(),
            ]);

            return back()->with('ccl_error', $baseDocName . ' PDF was generated and saved, but email delivery failed. Please check email settings and try resend.');
        }
    }

    public function upload_client_doc(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }
        $document = Client_Docs::find($request->id);
        $docFileRule = $document ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096' : 'required|file|mimes:jpg,jpeg,png,pdf|max:4096';
        $this->validate($request, [
            'doc_name' => 'required|string|min:3|max:100',
            'doc_file' => $docFileRule,
        ], [
            'doc_file.mimes' => 'Please select a valid file format (jpg, jpeg, png, pdf).',
            'doc_file.max' => 'Please select file up to 4MB.',
        ]);
        if ($document) {
            $document->doc_name = $request['doc_name'];
            if ($request->hasFile('doc_file')) {
                $file = $request->file('doc_file');
                $extension = $file->getClientOriginalName();
                $filename = time() . $extension;
                $file->move('web_assets/users/client' . $document->client_id . '/docs/', $filename);
                $document->doc_file = $filename;
                $document->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->client_id = $request->client_id;
                $activity->activity_name = "Client Document Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = $user->name . " Updates " . $client->name . " " . $request->doc_name . " Document at " . $request->local_time;
                } else {
                    $activity->activity_detail = $user->name . "(" . $subscriber->name . ") Updates " . $client->name . " " . $request->doc_name . " Document at " . $request->local_time;
                }
                $activity->activity_icon = "doc.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('updated', 'updated successfully');
            }
        } else {
            $doc = new Client_Docs();
            $client = Clients::find($request->client_id);
            $doc->client_id = $request['client_id'];
            $doc->user_id = $user->id;
            $doc->doc_name = $request['doc_name'];
            if ($request->hasFile('doc_file')) {
                $file = $request->file('doc_file');
                $extension = $file->getClientOriginalName();
                $filename = time() . $extension;
                $file->move('web_assets/users/client' . $doc->client_id . '/docs/', $filename);
                $doc->doc_file = $filename;
            }

            $doc->save();
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->client_id = $request->client_id;
            $activity->activity_name = "New Client Document Added";
            if ($user->user_type == "Subscriber") {
                $activity->activity_detail = $user->name . " Uploads " . $client->name . " " . $request->doc_name . " Document at " . $request->local_time;
            } else {
                $activity->activity_detail = $user->name . "(" . $subscriber->name . ") Uploads " . $client->name . " " . $request->doc_name . " Document at " . $request->local_time;
            }
            $activity->activity_icon = "doc.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            return back()->with('uploaded', 'uploaded successfully');
        }
    }

    public function delete_siteuser($id = null, $localtime = null)
    {
        if (!empty($id)) { //edit the page.
            $user = Auth::user();
            $this->set_timezone();
            if ($user) {
                $subscriber = $user;
                $siteuser = User::find($id);
                $username = $siteuser->name;
                $siteuser->delete();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $id;
                $activity->user_name = $name;
                $activity->activity_name = "User Deleted";
                $activity->activity_detail = $user->name . " deleted staff user" . $username . " account at " . $localtime;
                $activity->activity_icon = "user.png";
                $activity->local_time = $localtime;
                $activity->save();
                return back()->with('deleted', 'user deleted successfully');
            } else {
                return redirect()->route('login');
            }
        } else { //view the page.
            return back();
        }
    }

    public function delete_client($id = null, $localtime = null)
    {
        if (!empty($id)) { //edit the page.
            $user = Auth::user();
            $this->set_timezone();
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($user) {
                $client = Clients::find($id);
                $client->delete();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->client_id = $id;
                $activity->activity_name = "Client Deleted";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = $user->name . " deleted " . $client->name . " account at " . $localtime;
                } else {
                    $activity->activity_detail = $user->name . "(" . $subscriber->name . ") deleted " . $client->name . " account at " . $localtime;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $localtime;
                $activity->save();
                return back()->with('deleted', 'client deleted successfully');
            } else {
                return redirect()->route('login');
            }
        } else { //view the page.
            return back();
        }
    }

    public function applications()
    {
        $user = $this->check_login();
        if ($user->user_type != 'admin' && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }

        $this->set_timezone();
        if ($user) {
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $applications = Applications::where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
            } elseif ($user->user_type == "admin") {
                $subscriber = $user;
                $applications = Applications::orderBy('created_at', 'desc')->get();
            } else {
                $subscriber = User::find($user->added_by);
                $applications = Applications::where('assign_to', '=', $user->id)->orwhere('assign_to', '=', null)->where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
            }
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            $page = "applications";


            if (request()->ajax()) {
                $application_roles = null;
                if ($user->user_type != 'admin') {
                    $application_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Applications')->first();
                }


                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();


                $applications = $applications->whereBetween('created_at', [$startDate, $endDate]);
                return DataTables::of($applications)
                ->addIndexColumn()
                ->editColumn('client_name', function ($row) {
                    return $row->client ?  $row->client->name.'('.$row->client_id.')' :'';
                })
                    ->editColumn('application_name', function ($row) {
                        return $row->application_name.'('.$row->application_id.')';
                    })
                    ->editColumn('end_date', function ($row) {
                        if ($row->end_date != null) {
                            return date("d-m-Y", strtotime($row->end_date));
                        }
                    })
                    ->editColumn('start_date', function ($row) {
                        return date("d-m-Y", strtotime($row->start_date));
                    })
                    ->addColumn('action', function ($row) use ($application_roles, $user) {
                        $html = '';
                        $html .= '<a style="background:transparent;border:none;" class="p-0 m-0 text-dark" ';
                        if ($user->user_type == 'admin' || $application_roles->read_only == 1 || $application_roles->read_write_only == 1) {
                            $html .= 'href="' . route('view_application', $row->id) . '">';
                        } else {
                            $html .= 'href="#">';
                        }
                        $html .= '<i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>';

                        // $html .= '<a style="background:transparent;border:none;" class="p-0 m-0 text-dark" ';
                        // if ($application_roles->update_only == 1) {
                        //     $html .= 'href="' . route('update_application', $row->id) . '">';
                        // } else {
                        //     $html .= 'href="#">';
                        // }
                        // $html .= '<i class="fa-solid fa-edit btn text-primary p-1 m-0"></i></a>';

                        // $html .= '<i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;" ';
                        // if ($application_roles->delete_only == 1) {
                        //     $html .= 'onclick="deleteapplication(' . $row->id . ')"></i>';
                        // } else {
                        //     $html .= '></i>';
                        // }
                        return $html;
                    })
                    ->rawcolumns(['application_name', 'action'])
                    ->make(true);
            }

            return view('web.applications', compact('applications', 'clients', 'user', 'page', 'roles'));
        } else {
            return redirect()->route('login');
        }
    }

    public function user_application_tracking(){

        $user = Auth::user();
        $clients = Clients::where('subscriber_id', '=', $user->id)->get();
        // $subscribers = User::where('user_type', '=', 'Subscriber')->get();
        if ($user) {
            $subscriber = User::find($user->id);             
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        }
        $countries = Countries::get();
        $page = "applications";
        $applications = Applications::orderBy('created_at', 'desc')->where('id', 0)->get();
        return view('web.application_tracking', compact('clients','applications', 'user', 'page', 'countries'));
        
    }

    public function getClientsBySubscriber()
    {   
        $user = Auth::user();
        $clients = Clients::where('subscriber_id', $user->id)->get();
        return response()->json($clients);
    }

    public function getApplicationsByClient($clientId)
    {
        $applications = Applications::where('client_id', $clientId)->get(['id', 'application_name']);
        return response()->json($applications);
    }

    public function getApplicationData($id)
    {
        $application = Applications::with('client.user')->find($id);

        if (!$application) {
            return response()->json([]);
        }

        $timeline = collect([]);

        $client = $application->client;
        $subscriber = $client && $client->user ? $client->user : null;
        $registrationDate = $application->start_date ? Carbon::parse($application->start_date) : ($application->created_at ? $application->created_at->copy() : null);

        $timeline->push([
            'status' => 'Registration',
            'start_date' => $registrationDate ? $registrationDate->format('d/m/Y') : '--',
            'end_date' => $registrationDate ? $registrationDate->format('d/m/Y') : '--',
            'user' => $subscriber ? $subscriber->name . ' (' . $subscriber->id . ')' : '--',
            'sort_at' => $registrationDate,
        ]);

        $assignments = Application_assignments::with('user')
            ->where(function ($query) use ($application) {
                $query->where('application_id', $application->id);

                if (!empty($application->application_id)) {
                    $query->orWhere('application_id', $application->application_id);
                }
            })
            ->orderBy('id')
            ->get();

        foreach ($assignments as $assignment) {
            $assignedUser = $assignment->user;
            $assignedDate = $assignment->created_at ? $assignment->created_at->format('d/m/Y') : '--';

            $timeline->push([
                'status' => 'Assigned',
                'start_date' => $assignedDate,
                'end_date' => $assignedDate,
                'user' => $assignedUser ? $assignedUser->name . ' (' . $assignedUser->id . ')' : '--',
                'sort_at' => $assignment->created_at ? $assignment->created_at->copy() : null,
            ]);
        }

        $assignedToUser = $application->assign_to ? User::find($application->assign_to) : null;
        $statusDate = $application->end_date
            ? Carbon::parse($application->end_date)
            : ($application->updated_at ? $application->updated_at->copy() : $registrationDate);

        $timeline->push([
            'status' => $application->application_status ?: 'Decision',
            'start_date' => $statusDate ? $statusDate->format('d/m/Y') : '--',
            'end_date' => $application->end_date ? date("d/m/Y", strtotime($application->end_date)) : '--',
            'user' => $assignedToUser
                ? $assignedToUser->name . ' (' . $assignedToUser->id . ')'
                : ($subscriber ? $subscriber->name . ' (' . $subscriber->id . ')' : '--'),
            'sort_at' => $statusDate,
        ]);

        $timeline = $timeline
            ->sortBy(function ($item) {
                return isset($item['sort_at']) && $item['sort_at'] ? $item['sort_at']->timestamp : PHP_INT_MAX;
            })
            ->values()
            ->map(function ($item, $idx) {
                unset($item['sort_at']);
                $item['index'] = $idx + 1;
                return $item;
            });

        return response()->json($timeline);
    }

    public function add_application()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }
        if ($subscriber->category == "Law Firm") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } elseif ($subscriber->category == "Travel Agency") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } else {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->where('sub_category', '=', $subscriber->sub_category)->get();
        }
        $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        $countries = Countries::get();
        $page = "applications";
        return view('web.add_application', compact('clients', 'user', 'page', 'countries', 'client_jobs'));
    }

    public function update_application($id)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $application = Applications::find($id);
        $countries = Countries::get();
        $client = Clients::find($application->client_id);
        $subscriber = User::find($client->subscriber_id);
        if ($subscriber->category == "Law Firm") {
            $job_roles = Client_jobs::where('category', '=', $subscriber->category)->get();
        } elseif ($subscriber->category == "Travel Agency") {
            $job_roles = Client_jobs::where('category', '=', $subscriber->category)->get();
        } else {
            $job_roles = Client_jobs::where('category', '=', $subscriber->category)->where('sub_category', '=', $subscriber->sub_category)->get();
        }
        $page = "applications";
        return view('web.add_application', compact('application', 'job_roles', 'user', 'page', 'countries'));
    }

    public function add_new_application(Request $request)
    {
        function job_id()
        {
            $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $id = "";
            for ($i = 0; $i < 8; $i++) {
                $id = $id . $ch[rand(0, strlen($ch) - 1)];
            }
            return $id;
        }
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            $application = Applications::find($request->id);
            if ($application) {
                $client = Clients::find($request->client);
                $subscriber = User::find($client->subscriber_id);
                $application->application_name = $request['job_role'];
                $application->application_country =  $client->country;
                $application->visa_country =  $request['visa_country'];
                $application->application_detail = $request['job_detail'];
                $application->application_program = $request['study_program'];
                $application->application_status = $request['job_status'];
                $application->start_date = $request['job_open_date'];
                $application->end_date = $request['job_completion_date'];
                $application->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Application Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "Application of " . $request->job_role . " updated by " . $user->name . " at " . $request->local_time;
                } else {
                    $activity->activity_detail = "Application of " . $request->job_role . " updated by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return redirect()->route('applications')->with('application_updated', "Application Updated successfully");
            } else {
                $client = Clients::find($request->client);
                if ($client) {
                    $subscriber = User::find($client->subscriber_id);
                    $application = new Applications();
                    $application->client_id = $client->id;
                    $application->subscriber_id = $subscriber->id;
                    $application->application_id = job_id();
                    $application->application_category = $subscriber->category;
                    $application->application_subcategory = $subscriber->sub_category;
                    $application->application_name = $request['job_role'];
                    $application->application_country =  $client->country;
                     $application->visa_country =  $request['visa_country'];
                    $application->application_detail = $request['job_detail'];
                    $application->application_program = $request['study_program'];
                    $application->application_status = $request['job_status'];
                    $application->start_date = $request['job_open_date'];
                    $application->end_date = $request['job_completion_date'];
                    $application->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "New Application Added";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = "New Application of " . $request->job_role . " added by " . $user->name . " at " . $request->local_time;
                    } else {
                        $activity->activity_detail = "New Application of " . $request->job_role . " added by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                    }
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return redirect()->route('applications')->with('application_added', "Application Added successfully");
                } else {
                    return back();
                }
            }
        } else {
            return redirect()->route('admin');
        }
    }

    public function view_application($id)
    {
        $application = Applications::find($id);
        $user = Auth::user();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $user = Auth::user();
        $page = "applications";
        return view('web.view_application', compact('application', 'user', 'page'));
    }

    public function send_message(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            $message = new Messages();
            $message->client_id = $request['client_id'];
            $message->message = $request['message'];
            $message->save();
            echo "Message Sent";
        } else {
            return back();
        }
    }

    public function otp($email)
    {
        $email = $email;
        $this->set_timezone();
        return view('web.otp', compact('email'));
    }

    public function moredetails()
    {
        $user = Auth::user();
        $this->set_timezone();
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $countries = Countries::all();
        $states = States::all();
        $page = "index";
        return view('web.moredetails', compact('user', 'countries', 'states', 'page', 'tzlist'));
    }

    public function verify_otp(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        $this->set_timezone();
        if ($user) {
            // if($request->phone != $request->phone_otp){
            //     return back()->with('phoneerror','Incorrect OTP');
            // }
            if ($request->otp != $user->email_otp) {
                return back()->with('emailerror', 'Incorrect OTP');
            } else {
                $user->email_verified_at = new DateTime('now');
                $user->email_otp = null;
                $user->phone_otp = null;
                $user->save();
                return redirect()->route('thanks');
            }
        } else {
            return back()->with('nouser', 'no user found');
        }
    }

    public function verify_password_otp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $this->set_timezone();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No user found with this email.',
            ]);
        }

        // Sending OTP
        if ($request->action === "send_otp") {
            $eotp = rand(10000, 99999);
            // $eotp = rand(10000, 99999);
            $user->email_otp = $eotp;
            $user->save();

            // Send OTP via email
            $maildata = new \stdClass();
            $maildata->name = $user->name;
            $maildata->email = $user->email;
            $maildata->password = "otp password";
            $maildata->otp = $eotp;

            try {
                Mail::to($user->email)->send(new EmailVerification($maildata));
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully to your email.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send OTP. Please try again.',
                ]);
            }
        }

        // Verifying OTP
        if ($request->action === "verify_otp") {
            if ($user->email_otp != $request->email_otp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP. Please try again.',
                ]);
            }

            // Clear OTP after verification
            $user->email_otp = null;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully.',
                'redirect_url' => route('new_password', ['email' => $user->email]),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid request.',
        ]);
    }


    public function verify_password_otp_affiliate(Request $request)
    {
        $user = Affiliates::where('email', '=', $request->email)->first();
        $this->set_timezone();
        if ($user) {
            // if($request->phone != $request->phone_otp){
            //     return back()->with('phoneerror','Incorrect OTP');
            // }
            if ($request->email_otp != $user->email_otp) {
                return redirect()->back()->withInput()->with('emailerror', 'Incorrect OTP');
            } else {
                $email = $request->email;
                return redirect()->route('new_password_affiliate', $email);
            }
        } else {
            return back()->with('nouser', 'no user found');
        }
    }

    public function new_password($email)
    {
        $email = $email;
        $this->set_timezone();
        return view('web.new_password', compact('email'));
    }
    public function new_password_affiliate($email)
    {
        $email = $email;
        $this->set_timezone();
        return view('web.new_password_affiliate', compact('email'));
    }

    public function save_password(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        $this->set_timezone();
        if ($user) {
            $this->validate($request, [
                'password' => 'required|string|min:8|confirmed',
            ]);
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $user->email_otp = null;
            $user->phone_otp = null;
            $user->password = Hash::make($request['password']);
            $user->save();
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Password Recovered";
            if ($user->user_type == "Subscriber") {
                $activity->activity_detail = "Password Recovered by " . $user->name . " at " . $request->local_time;
            } else {
                $activity->activity_detail = "Password Recovered by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
            }
            $activity->activity_icon = "user.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            return redirect()->route('login')->with('password_changed', 'password changed successfully');
        } else {
            return back()->with('nouser', 'no user found');
        }
    }
    public function save_password_affiliate(Request $request)
    {
        $user = Affiliates::where('email', '=', $request->email)->first();
        $this->set_timezone();
        if ($user) {
            $this->validate($request, [
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = Hash::make($request['password']);
            $user->email_otp = null;
            $user->phone_otp = null;
            $user->save();
            $activity = new Activities();
            $activity->subscriber_id = $user->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Password Recovered";
            $activity->activity_detail = "Password Recovered by " . $user->name . "(" . $user->name . ") at " . $request->local_time;
            $activity->activity_icon = "user.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            return redirect()->route('login')->with('password_changed', 'password changed successfully');
        } else {
            return back()->with('nouser', 'no user found');
        }
    }

    public function forget_password()
    {
        return view('web.forget_password');
    }

    public function thanks()
    {
        $page = 'index';
        return view('web.thanks', compact('page'));
    }

    public function features()
    {
        $user = Auth::user();
        $this->set_timezone();
        $features = Features::get();
        $page = "features";
        return view('web.features', compact('user', 'features', 'page'));
    }

    public function faqs()
    {
        $user = Auth::user();
        $this->set_timezone();
        
        $faqs = Faq::get();
        $page = "faqs";
            return view('web.faqs', compact('user', 'page', 'faqs'));
    }

    public function membership()
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $myplan = Membership::where('plan_name', '=', $user->membership)->first();
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
                $myplan = Membership::where('plan_name', '=', $subscriber->membership)->first();
            }
            $total_users = User::where('added_by', '=', $subscriber->id)->get();
            $total_clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $myplan = null;
            $subscriber = null;
            $total_users = 0;
            $total_clients = 0;
        }
        $membership = Membership::orderBy('created_at', 'asc')->get();
        $page = "membership";
        return view('web.membership', compact('user', 'membership', 'page', 'myplan', 'subscriber', 'total_users', 'total_clients'));
    }

    public function membershipRenewal()
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $myplan = Membership::where('plan_name', '=', $user->membership)->first();
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
                $myplan = Membership::where('plan_name', '=', $subscriber->membership)->first();
            }
            $total_users = User::where('added_by', '=', $subscriber->id)->get();
            $total_clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $myplan = null;
            $subscriber = null;
            $total_users = 0;
            $total_clients = 0;
        }
        $membership = Membership::orderBy('created_at', 'asc')->get();
        $page = "membership";
        return view('web.membership_renewal', compact('user', 'membership', 'page', 'myplan', 'subscriber', 'total_users', 'total_clients'));
    }

    public function user_membership()
    {

        $this->check_login();
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber" || $user->user_type == "admin") {
                $subscriber = $user;
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
            }
            $myplan = Membership::where('plan_name', '=', $user->membership)->first();

            $membership = Membership::get();
            $page = "user_membership";
            return view('web.user_membership', compact('user', 'membership', 'page', 'myplan', 'subscriber'));
        } else {
            return back();
        }
    }

    public function download_all_data()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->user_type == 'Subscriber' || $user->user_type == 'admin') {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }

        if (!$subscriber) {
            return back()->with('download_error', 'Unable to identify subscriber for export.');
        }

        $timestamp = now()->format('Ymd_His');
        $baseDir = storage_path('app/temp_exports/' . $subscriber->id . '_' . $timestamp);
        $tablesDir = $baseDir . '/Tables';
        $documentsDir = $baseDir . '/Documents';

        if (!is_dir($tablesDir) && !mkdir($tablesDir, 0777, true) && !is_dir($tablesDir)) {
            return back()->with('download_error', 'Unable to prepare export directory.');
        }
        if (!is_dir($documentsDir) && !mkdir($documentsDir, 0777, true) && !is_dir($documentsDir)) {
            return back()->with('download_error', 'Unable to prepare documents directory.');
        }

        $clients = Clients::where('subscriber_id', $subscriber->id)->get();
        $applications = Applications::where('subscriber_id', $subscriber->id)->get();
        $users = User::where('added_by', $subscriber->id)->get();
        $invoices = Invoices::where('user_id', $subscriber->id)->get();
        $payments = PaymentARs::where('subscriber_id', $subscriber->id)->get();
        $communications = Internal_communications::where('subscriber_id', $subscriber->id)->get();

        $this->writeExportCsv($tablesDir . '/Clients.csv', $clients->toArray());
        $this->writeExportCsv($tablesDir . '/Applications.csv', $applications->toArray());
        $this->writeExportCsv($tablesDir . '/Users_Staff.csv', $users->toArray());
        $this->writeExportCsv($tablesDir . '/Invoices.csv', $invoices->toArray());
        $this->writeExportCsv($tablesDir . '/Payments.csv', $payments->toArray());
        $this->writeExportCsv($tablesDir . '/Communications.csv', $communications->toArray());

        $clientDocuments = Client_Docs::where('user_id', $subscriber->id)->whereNotNull('doc_file')->get();

        foreach ($clientDocuments as $document) {
            $client = Clients::find($document->client_id);
            $application = Applications::where('application_id', $document->application_id)->first();

            $clientName = $client ? $this->safeArchiveName($client->name) : 'Unknown Client';
            $applicationName = $application ? $this->safeArchiveName($application->application_name ?: $application->application_id) : 'Unknown Application';
            $targetFolder = $documentsDir . '/' . $clientName . ' - ' . $applicationName;

            if (!is_dir($targetFolder)) {
                mkdir($targetFolder, 0777, true);
            }

            $sourcePath = public_path('web_assets/users/client' . $document->client_id . '/docs/' . $document->doc_file);
            if (file_exists($sourcePath)) {
                $destinationName = $document->doc_name ? $this->safeArchiveName($document->doc_name) : pathinfo($document->doc_file, PATHINFO_FILENAME);
                $extension = pathinfo($document->doc_file, PATHINFO_EXTENSION);
                $destinationPath = $targetFolder . '/' . $destinationName . ($extension ? '.' . $extension : '');

                if (file_exists($destinationPath)) {
                    $destinationPath = $targetFolder . '/' . $destinationName . '_' . $document->id . ($extension ? '.' . $extension : '');
                }

                copy($sourcePath, $destinationPath);
            }
        }

        $zipFileName = 'subscriber_data_' . Str::slug($subscriber->name ?: 'subscriber') . '_' . $timestamp . '.zip';
        $zipPath = storage_path('app/temp_exports/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('download_error', 'Unable to create export file.');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($baseDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($baseDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        if (is_dir($baseDir)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($baseDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($directoryIterator as $item) {
                $item->isDir() ? rmdir($item->getRealPath()) : unlink($item->getRealPath());
            }
            rmdir($baseDir);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function wallet()
    {
        $user = $this->check_login();
        if ($user->type_user != "affiliate" && $user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber" || $user->user_type == "admin" || $user->user_type == 'Affiliate') {
                $subscriber = $user;
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
            }
            if ($user->user_type != 'Affiliate') {

                $myplan = Membership::where('plan_name', '=', $subscriber->membership)->first();
            }


            if ($user->user_type == "admin") {
                $referrals = Referrals::orderBy('created_at', 'desc')->get();
                $transactions = Referrals::where('type', '=', 'Wallet Transaction')->orderBy('created_at', 'desc')->get();
            } elseif($user->user_type == 'Affiliate'){
                $referrals = Referrals::whereHas('user')
                ->whereHas('getRefferedByUser')
                ->with(['user'])->where('referral_code', '=', $subscriber->referral)->orderBy('created_at', 'desc')->get();
                $transactions = Referrals::where('userid', '=', $subscriber->id)->where('type', '=', 'Wallet Transaction')->orderBy('created_at', 'desc')->get();
            }else {
                $referrals = Referrals::where('referral_code', '=', $subscriber->referral)->orderBy('created_at', 'desc')->get();
                $transactions = Referrals::where('userid', '=', $subscriber->id)->where('type', '=', 'Wallet Transaction')->orderBy('created_at', 'desc')->get();
            }



            $membership = Membership::get();
            $today = new DateTime("now");
            $membership_expiry = new DateTime($user->membership_expiry_date);
            if ($today > $membership_expiry) {
                $expiry = "Plan Expired";
            } else {
                $expiry = null;
            }
            $page = "wallet";
            if (request()->ajax()) {
                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();

                if (request()->tableName == 'wallet'); {
                    $referrals = $referrals->whereBetween('created_at', [$startDate, $endDate]);

                    return DataTables::of($referrals)
                        ->addIndexColumn()
                        ->editColumn('walletId', function ($row) {
                            return $row->id;
                        })
                        ->editColumn('user_name', function ($row) {
                            if (strlen($row->user_name) > 15) {
                                return  substr($row->user_name, 0, 15) . '...';
                            } else {
                                return $row->user_name;
                            }
                        })

                        ->addColumn('finalamount', function ($row) {

                            if (!empty($row->amount_added)) {
                                return $row->amount_added;
                            } elseif (!empty($row->debit_amount)) {
                                return $row->debit_amount;
                            }
                            return 0;
                        })
                        ->addColumn('TransactionType', function ($row) {
                            $wallet_balance = round($row->wallet_balance,2) ?? 0;
                            $previous_balance = round($row->previous_balance,2) ?? 0;
                            $result ='';
                            if($wallet_balance > 0 && $wallet_balance > $previous_balance){
                                $result =  '+'.round(($wallet_balance - $previous_balance),2);
                             }elseif ($previous_balance > 0 && $wallet_balance < $previous_balance){
                             $result = '-'.round(($previous_balance - $wallet_balance),2);
                             }else{
                             $result ='0';
                            }
                            return $result;
                        })
                         ->addColumn('type', function ($row) {
                            $displayText = '';
                            switch ($row->type) {
                                case 'cashback':
                                    $displayText = 'Cashback';
                                    break;
                                case 'one_off':
                                    $displayText = 'One-off credit';
                                    break;
                                case 'double_term':
                                    $displayText = 'Double the subscription term';
                                    break;
                                default:
                                    $displayText = $row->type;
                            }
                            return $displayText;
                        })
                        ->editColumn('created_at', function ($row) {
                            return date("d-m-Y", strtotime($row->created_at));
                        })
                        ->make(true);


                }
                if (request()->tableName == 'transactions') {
                    $transactions = $transactions->whereBetween('created_at', [request()->startdate, request()->enddate]);

                    return DataTables::of($transactions)
                        ->addIndexColumn()
                        ->editColumn('user_name', function ($row) {
                            if (strlen($row->user_name) > 15) {
                                return  substr($row->user_name, 0, 15) . '...';
                            } else {
                                return $row->user_name;
                            }
                        })

                        ->addColumn('TransactionType', function ($row) {
                            if (!empty($row->amount_added)) {
                                return 'Credit';
                            } elseif (!empty($row->debit_amount)) {
                                return 'Debit';
                            }
                            return '';
                        })

                        ->editColumn('created_at', function ($row) {
                            return date("d-m-Y H:i:s", strtotime($row->created_at));
                        })
                        ->make(true);
                }
            }
            if ($user->type_user == 'affiliate') {

                return view('affiliate.wallet', compact('user', 'membership', 'page', 'subscriber', 'referrals', 'expiry', 'transactions'));
            } else {
                return view('web.wallet', compact('user', 'membership', 'page', 'myplan', 'subscriber', 'referrals', 'expiry', 'transactions'));
            }
        } else {
            return back();
        }
    }

    public function add_amount(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $amt = $subscriber->wallet;
                $subscriber->wallet = $amt + abs($request['amount']);
                $subscriber->save();
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->activity_name = "Amount added in Wallet";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = $user->name . " added " . $request->amount . " in his wallet at " . $request->local_time;
                } else {
                    $activity->activity_detail = $user->name . "(" . $subscriber->name . ") added " . $request->amount . " in his wallet at " . $request->local_time;
                }
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $request->local_time;
                $activity->save();
                return back()->with('amount_added', 'amount added successfully');
            } else {
                return back();
            }
        } else {
            return back();
        }
    }

    public function referrals()
    {
        $user = $this->check_login();
        if ($user->type_user != "affiliate" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {

            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber" || $user->user_type == "Affiliate") {
                $subscriber = $user;
            } else {
                $sid = $user->added_by;
                $subscriber = User::find($sid);
            }
            if ($user->user_type == "admin") {

                $referrals = Referrals::whereNotIn('type', ['one_off', 'double_term', 'cashback'])->where('type','Referral Commission')->orderBy('created_at', 'desc')->get();
            } else {

                $referrals = Referrals::where('userid','!=', $subscriber->id)->where('type','Referral Commission')->where('referral_code', '=', $subscriber->referral)->orderBy('created_at', 'desc')->get();
            }
            $page = "referrals";
            if (request()->ajax()) {
                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();

                $referrals = $referrals->whereBetween('created_at', [$startDate, $endDate]);

                return DataTables::of($referrals)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($row) {
                        return date("d-m-Y H:i:s", strtotime($row->created_at));
                    })
                    ->make(true);
            }
            if ($user->type_user == 'affiliate') {
                return view('affiliate.referrals', compact('user', 'page', 'subscriber', 'referrals'));
            } else {
                return view('web.referrals', compact('user', 'page', 'subscriber', 'referrals'));
            }
        } else {
            return back();
        }
    }

    public function upgrade_membership($plan)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            $expired = "expired";
        } else {
            $expired = "";
        }
        $membership = Membership::where('plan_name', '=', $plan)->first();
        $page = "membership";
        return view('web.upgrade_membership', compact('user', 'membership', 'page', 'expired'));
        // $user = Auth::user();
        // if($request->id == $user->id){
        //     $user->membership = $request['plan_name'];
        //     $user->save();
        //     echo "success";
        // }
    }

    public function transaction_id()
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id = "";
        for ($i = 0; $i < 10; $i++) {
            $id = $id . $ch[rand(0, strlen($ch) - 1)];
        }
        // $id = "#" . $id;
        $agent = Invoices::where('invoice', '=', $id)->first();
        if ($agent) {
            $this->transaction_id();
        } else {
            return $id;
        }
    }

    public function make_payment(Request $request)
    {
        // print_r($request->all());
        // exit();
        $user = Auth::user();
        $page = "membership";
        if($request->plan_amount != 0){
            if (isset($request->wallet_pay)) {
                if ($request->id == $user->id) {
                    $plan = $request->plan_name;
                    $duration = $request->plan_duration;
                    $amount = $request->plan_amount;
                    if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                        $expired = "expired";
                        $wallet_amount = 0;
                        $discount = 0;
                    } else {
                        $wallet_amount = $user->wallet;
                    }
                    $membership = Membership::where('plan_name', '=', $plan)->first();
                    $plan_amt = $membership->price_per_year;
                    if ($duration == 1) {
                        $plan_amount = round(($plan_amt * 1) * 1);
                    }
                    if ($duration == 2) {
                        $plan_amount = round(($plan_amt * 2) * 0.9);
                    }
                    if ($duration == 3) {
                        $plan_amount = round(($plan_amt * 3) * 0.8);
                    }
                    if ($duration == 5) {
                        $plan_amount = round(($plan_amt * 5) * 0.5);
                    }
                    if ($wallet_amount > $plan_amount) {
                        $discount = $plan_amount;
                    }
                    $new_wallet = $user->wallet - $plan_amount;
                    $user->membership = $membership->plan_name;
                    $user->membership_type = "Subscription";
                    $user->membership_start_date = new DateTime("now");
                    $user->membership_expiry_date = (new DateTime("now"))->modify("+" . $duration . " years");
                    $user->wallet = $new_wallet;
                    $user->save();
                    $my_users = User::where('added_by', '=', $user->id)->get();
                    foreach ($my_users as $myuser) {
                        $myuser->membership = $user->membership;
                        $myuser->membership_type = $user->membership_type;
                        $myuser->membership_start_date = $user->membership_start_date;
                        $myuser->membership_expiry_date = $user->membership_expiry_date;
                        $myuser->wallet = 0;
                        $myuser->save();
                    }
                    $activity = new Activities();
                    $activity->subscriber_id = $user->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "Subscription Updated";
                    $activity->activity_detail = $user->name . " updates account subscription at " . $request['local_time'];
                    $activity->activity_icon = "mmbrcp.png";
                    $activity->local_time = $request['local_time'];
                    $activity->save();
                    $service_fee = $plan_amount;
                    // $discount = 0;
                    // $tax = ($service_fee + $discount) * (18/100);
                    $tax = 0;
                    $company = User::where('user_type', '=', 'admin')->first();
                    $invoice = new Invoices();
                    $invoice->user_id = $user->id;
                    $invoice->invoice = $this->transaction_id();
                    $invoice->company_name = $company->organization;
                    $invoice->city = $company->city;
                    $invoice->state = $company->state;
                    $invoice->country = $company->country;
                    $invoice->pincode = $company->pincode;
                    $invoice->phone = $company->phone;
                    $invoice->address = $company->address_line;
                    $invoice->logo = $company->organization_logo;
                    $invoice->to_name = $user->name;
                    $invoice->to_company = $user->organization;
                    $invoice->to_city = $user->city;
                    $invoice->to_state = $user->state;
                    $invoice->to_country = $user->country;
                    $invoice->to_pincode = $user->pincode;
                    $invoice->to_phone = $user->phone;
                    $invoice->to_email = $user->email;
                    $invoice->service_fee = $service_fee;
                    $invoice->discount = $discount;
                    $invoice->tax = $tax;
                    $invoice->total = $service_fee - $discount + $tax;
                    $invoice->payment_mode = "Wallet";
                    $invoice->save();
                    $internalInvoice = $this->createAdminApInvoiceAndPayment($user, $company, (float) $invoice->total, "Wallet", "Subscription Fees");
                    $this->sendPlanUpdateMail($user, $membership, $internalInvoice, $company);
                    $save_referral = new Referrals();
                    $save_referral->userid = $user->id;
                    $save_referral->user_name = $user->name;
                    $save_referral->type = "Wallet Transaction";
                    $save_referral->total_amount = $service_fee;
                    $save_referral->debit_amount = $discount;
                    $save_referral->previous_balance = $wallet_amount;
                    $save_referral->wallet_balance = $user->wallet;
                    $save_referral->save();
                    return redirect()->route('user_membership')->with('payment_success', 'Payment Done Successfully.');
                } else {
                    return back();
                }
            } else {
                if ($request->id == $user->id) {
                    $data = array();
                    $plan = $request->plan_name;
                    $duration = $request->plan_duration;
                    $data['id'] = $request->id;
                    $data['plan_name'] = $plan;
                    $data['plan_duration'] = $duration;
                    if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                        $expired = "expired";
                        $wallet_amount = 0;
                    } else {
                        $wallet_amount = $user->wallet;
                    }
                    $membership = Membership::where('plan_name', '=', $plan)->first();
                    $plan_amt = $membership->price_per_year;
                    if ($duration == 1) {
                        $plan_amount = round(($plan_amt * 1) * 1);
                        $plan_price = $plan_amount - $wallet_amount;
                    }
                    if ($duration == 2) {
                        $plan_amount = round(($plan_amt * 2) * 0.9);
                        $plan_price = $plan_amount - $wallet_amount;
                    }
                    if ($duration == 3) {
                        $plan_amount = round(($plan_amt * 3) * 0.8);
                        $plan_price = $plan_amount - $wallet_amount;
                    }
                    if ($duration == 5) {
                        $plan_amount = round(($plan_amt * 5) * 0.5);
                        $plan_price = $plan_amount - $wallet_amount;
                    }
                    $data['wallet_amount'] = $wallet_amount;
                    $data['plan_amount'] = $plan_amount;
                    $data['plan_price'] = $plan_price;
                    $data['local_time'] = $request->local_time;
                    Session::put('pay_data', $data);
                    // foreach($data as $key => $value){
                    //     echo $key . " = " . $value . "<br>";
                    // }
                    return redirect('pay_securely');

                    // if(isset($request->referral_code)){
                    //     $referral = User::where('id','!=',$user->id)->where('referral','=',$request->referral_code)->first();
                    //     if($referral == null){
                    //         return back()->with('error','invalid referral code');
                    //     }
                    //     else{
                    //         $use_referral = Used_referrals::where('subscriber_id','=',$user->id)->where('referral_code','=',$request->referral_code)->first();
                    //         if($use_referral != null){
                    //             return back()->with('used','referral is already used');
                    //         }
                    //     }
                    // }
                } else {
                    return back();
                }
            }
        } else {
            if ($request->id == $user->id) {
                $plan = $request->plan_name;
                $duration = $request->plan_duration;
                $amount = $request->plan_amount;
                if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                    $expired = "expired";
                    $wallet_amount = 0;
                    $discount = 0;
                } else {
                    $wallet_amount = $user->wallet;
                }
                $membership = Membership::where('plan_name', '=', $plan)->first();
                $plan_amt = $membership->price_per_year;
                if ($duration == 1) {
                    $plan_amount = 0;
                }
                if ($duration == 2) {
                    $plan_amount = 0;
                }
                if ($duration == 3) {
                    $plan_amount = 0;
                }
                if ($duration == 5) {
                    $plan_amount = 0;
                }
                if ($wallet_amount > $plan_amount) {
                    $discount = $plan_amount;
                }
                $new_wallet = $user->wallet - $plan_amount;
                $user->membership = $membership->plan_name;
                $user->membership_type = "Subscription";
                $user->membership_start_date = new DateTime("now");
                $user->membership_expiry_date = (new DateTime("now"))->modify("+" . $duration . " years");
                $user->wallet = $new_wallet;
                $user->save();
                $my_users = User::where('added_by', '=', $user->id)->get();
                foreach ($my_users as $myuser) {
                    $myuser->membership = $user->membership;
                    $myuser->membership_type = $user->membership_type;
                    $myuser->membership_start_date = $user->membership_start_date;
                    $myuser->membership_expiry_date = $user->membership_expiry_date;
                    $myuser->wallet = 0;
                    $myuser->save();
                }
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Subscription Updated";
                $activity->activity_detail = $user->name . " updates account subscription at " . $request['local_time'];
                $activity->activity_icon = "mmbrcp.png";
                $activity->local_time = $request['local_time'];
                $activity->save();
                $service_fee = $plan_amount;
                // $discount = 0;
                // $tax = ($service_fee + $discount) * (18/100);
                $tax = 0;
                $company = User::where('user_type', '=', 'admin')->first();
                $invoice = new Invoices();
                $invoice->user_id = $user->id;
                $invoice->invoice = $this->transaction_id();
                $invoice->company_name = $company->organization;
                $invoice->city = $company->city;
                $invoice->state = $company->state;
                $invoice->country = $company->country;
                $invoice->pincode = $company->pincode;
                $invoice->phone = $company->phone;
                $invoice->address = $company->address_line;
                $invoice->logo = $company->organization_logo;
                $invoice->to_name = $user->name;
                $invoice->to_company = $user->organization;
                $invoice->to_city = $user->city;
                $invoice->to_state = $user->state;
                $invoice->to_country = $user->country;
                $invoice->to_pincode = $user->pincode;
                $invoice->to_phone = $user->phone;
                $invoice->to_email = $user->email;
                $invoice->service_fee = $service_fee;
                $invoice->discount = 0;
                $invoice->tax = $tax;
                $invoice->total = $service_fee - 0 + $tax;
                $invoice->payment_mode = "Wallet";
                $invoice->save();
                $internalInvoice = $this->createAdminApInvoiceAndPayment($user, $company, (float) $invoice->total, "Wallet", "Subscription Fees");
                $this->sendPlanUpdateMail($user, $membership, $internalInvoice, $company);
                $save_referral = new Referrals();
                $save_referral->userid = $user->id;
                $save_referral->user_name = $user->name;
                $save_referral->type = "Wallet Transaction";
                $save_referral->total_amount = $service_fee;
                $save_referral->debit_amount = 0;
                $save_referral->previous_balance = $wallet_amount;
                $save_referral->wallet_balance = $user->wallet;
                $save_referral->save();
                return redirect()->route('user_membership')->with('payment_success', 'Payment Done Successfully.');
            } else {
                return back();
            }
        }
    }

    public function pay_securely()
    {
        $user = Auth::user();
        if ($user) {
            $page = "membership";
            $data = session('pay_data');
            $amount = $data['plan_price'];
            return view('web.make_payment', compact('user', 'data', 'page', 'amount'));
        } else {
            return back();
        }
    }

    public function upgrade_plan(Request $request)
    {
        function invoice_id()
        {
            $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $id = "";
            for ($i = 0; $i < 10; $i++) {
                $id = $id . $ch[rand(0, strlen($ch) - 1)];
            }
            if (Invoices::where('invoice', '=', $id)->first()) {
                return invoice_id();
            }
            return $id;
        }
        $user = Auth::user();
        $paymentMode = isset($request->wallet_pay) ? 'Wallet' : 'Card';
        if ($request->id == $user->id) {
            if (isset($request->wallet_pay)) {
                $amt = $user->wallet;
                $user->wallet = $amt - $request->plan_price;
            }
            if (isset($request->referral_code)) {
                $referral = User::where('id', '!=', $user->id)->where('referral', '=', $request->referral_code)->first();
                if ($referral == null) {
                    return back()->with('error', 'invalid referral code');
                } else {
                    $use_referral = Used_referrals::where('subscriber_id', '=', $user->id)->where('referral_code', '=', $request->referral_code)->first();
                    if ($use_referral != null) {
                        return back()->with('used', 'referral is already used');
                    }
                }
            }
            $duration = $request['plan_duration'];
            $user->membership = $request['plan_name'];
            $user->membership_type = "Subscription";
            $user->membership_start_date = new DateTime("now");
            $user->membership_expiry_date = (new DateTime("now"))->modify("+" . $duration . " years");
            $user->save();
            $my_users = User::where('added_by', '=', $user->id)->get();
            foreach ($my_users as $myuser) {
                $myuser->membership = $user->membership;
                $myuser->membership_type = $user->membership_type;
                $myuser->membership_start_date = $user->membership_start_date;
                $myuser->membership_expiry_date = $user->membership_expiry_date;
                $myuser->save();
            }
            // if(isset($referral)){
            //     $amt = $referral->wallet;
            //     $referral->wallet = $amt + ($request->plan_price * 0.2);
            //     $referral->save();
            //     $save_referral = new Referrals();
            //     $save_referral->referral_code = $request->referral_code;
            //     $save_referral->userid = $user->id;
            //     $save_referral->user_name = $user->name;
            //     $save_referral->total_amount = $request->plan_price;
            //     $save_referral->amount_added = $request->plan_price * 0.2;
            //     $save_referral->previous_balance = $amt;
            //     $save_referral->wallet_balance = $amt + ($request->plan_price * 0.2);
            //     $save_referral->save();
            // }
            $activity = new Activities();
            $activity->subscriber_id = $user->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Price Plan Updated";
            $activity->activity_detail = $user->name . " updates account price plan at " . $request->local_time;
            $activity->activity_icon = "mmbrcp.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            $plan = Membership::where('plan_name', '=', $request->plan_name)->first();
            $service_fee = $request->plan_price;
            $discount = 0;
            $tax = ($service_fee + $discount) * (18 / 100);
            $company = User::where('user_type', '=', 'admin')->first();
            $invoice = new Invoices();
            $invoice->user_id = $user->id;
            $invoice->invoice = invoice_id();
            $invoice->company_name = $company->organization;
            $invoice->city = $company->city;
            $invoice->state = $company->state;
            $invoice->country = $company->country;
            $invoice->pincode = $company->pincode;
            $invoice->phone = $company->phone;
            $invoice->address = $company->address_line;
            $invoice->logo = $company->organization_logo;
            $invoice->to_name = $user->name;
            $invoice->to_company = $user->organization;
            $invoice->to_city = $user->city;
            $invoice->to_state = $user->state;
            $invoice->to_country = $user->country;
            $invoice->to_pincode = $user->pincode;
            $invoice->to_address = $user->address_line;
            $invoice->to_phone = $user->phone;
            $invoice->to_email = $user->email;
            $invoice->service_fee = $service_fee;
            $invoice->discount = $discount;
            $invoice->tax = $tax;
            $invoice->total = $service_fee - $discount + $tax;
            $invoice->save();
            $internalInvoice = $this->createAdminApInvoiceAndPayment($user, $company, (float) $invoice->total, $paymentMode, "Subscription Fees");
            $this->sendPlanUpdateMail($user, $plan, $internalInvoice, $company);
            $activity = new Activities();
            $activity->subscriber_id = $user->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Payment Generated";
            $activity->activity_detail = "Payment generated for price plan update for user " . $user->name . " at " . $request->local_time;
            $activity->activity_icon = "invoice.jpg";
            $activity->local_time = $request->local_time;
            $activity->save();
            return redirect()->route('user_membership');
        }
    }

    public function downgrade_plan(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($request->id == $user->id) {
            $user->membership = $request['plan_name'];
            $user->save();
            $activity = new Activities();
            $activity->subscriber_id = $user->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Price Plan Updated";
            $activity->activity_detail = $user->name . " updates account price plan at " . $request->local_time;
            $activity->activity_icon = "mmbrcp.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            echo "success";
        }
    }



    public function view_payment($id)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $roles = UserRoles::where('user_id', '=', $user->id)->first();
        $page = "payments";
        $invoice = Invoices::find($id);
        return view('web.view_payment', compact('user', 'roles', 'page', 'invoice'));
    }

    public function print_payment($id)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin") {
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
        }
        $page = "payments";
        $invoice = Invoices::find($id);
        return view('web.print_payment', compact('user', 'page', 'invoice'));
    }

    public function delete_payment($id = null, $localtime = null)
    {
        if (!empty($id)) { //edit the page.
            $user = Auth::user();
            $this->set_timezone();
            if ($user) {
                $invoice = Invoices::find($id);
                $invoice->delete();
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Payment Deleted";
                $activity->activity_detail = $user->name . " deleted an Invoice at " . $localtime;
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $localtime;
                $activity->save();
                return back()->with('deleted', 'Job deleted successfully');
            } else {
                return redirect()->route('login');
            }
        } else { //view the page.
            return back();
        }
    }

    public function invoices()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('user_id', '=', $user->id)->get();
        }
        if ($user->user_type == "admin") {

            $invoices = Internal_Invoices::orderBy('created_at', 'desc')->where('type', 'ar')->get();
        } else {
            $invoices = Internal_Invoices::where('subscriber_id', '=', $subscriber->id)->where('type', 'ar')->orderBy('created_at', 'desc')->get();
        }
        $roles = UserRoles::where('user_id', '=', $user->id)->first();
        $page = "invoices";

        if (request()->ajax()) {
            $startDate = Carbon::parse(request()->startdate)->startOfDay();
            $endDate = Carbon::parse(request()->enddate)->endOfDay();

            $invoice_roles = null;
            if ($user->user_type != "admin") {

                $invoice_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Invoices')->first();
            }
            $invoices = $invoices->whereBetween('created_at', [$startDate, $endDate]);

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->editColumn('to_name', function ($row) {
                    return trim($row->to_name . (!empty($row->vendor_id) ? ' (' . $row->vendor_id . ')' : ''));
                })
                ->editColumn('to_email', function ($row) {

                    return $row->to_email;
                })
                ->editColumn('discount', function ($row) {
                    return $row->discount . " %";
                })
                ->editColumn('tax', function ($row) {
                    return $row->tax . " %";
                })
                ->addColumn('sub_type', function ($row) use ($user) {

                    return $row->status;
                })
                ->editColumn('due_date', function ($row) {
                    return date("d-m-Y", strtotime($row->due_date));
                })
                ->addColumn('action', function ($row) use ($invoice_roles, $user) {
                    $html = '<a style="background:none; border:none;"';

                    if ($user->user_type == "admin" || $invoice_roles->read_only == 1 || $invoice_roles->read_write_only == 1) {
                        $html .= ' href="' . route('view_invoice', $row->id) . '"';
                    } else {
                        $html .= ' href="#"';
                    }

                    $html .= ' class="m-0 p-0"><i class="fa-solid fa-eye p-1 text-info" style="font-size:14px;"></i></a>';

                    return $html;
                })
                ->make(true);
        }
        return view('web.invoices', compact('user', 'page', 'invoices', 'roles', 'clients'));
    }

    public function invoice_payment_made(){
        $user = $this->check_login();
        if ($user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('user_id', '=', $user->id)->get();
        }
        if ($user->user_type == "admin") {

            $invoices = Internal_Invoices::orderBy('created_at', 'desc')->where('type', 'ap')->get();
        } else {
            $invoices = Internal_Invoices::where('subscriber_id', '=', $subscriber->id)->where('type', 'ap')->orderBy('created_at', 'desc')->get();
        }
        $roles = UserRoles::where('user_id', '=', $user->id)->first();
        $page = "invoices";

        if (request()->ajax()) {
            $startDate = Carbon::parse(request()->startdate)->startOfDay();
            $endDate = Carbon::parse(request()->enddate)->endOfDay();

            $invoice_roles = null;
            if ($user->user_type != "admin") {

                $invoice_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Invoices')->first();
            }
            $invoices = $invoices->whereBetween('created_at', [$startDate, $endDate]);

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->editColumn('to_name', function ($row) {
                    return trim($row->to_name . (!empty($row->vendor_id) ? ' (' . $row->vendor_id . ')' : ''));
                })
                ->editColumn('to_email', function ($row) {

                    return $row->to_email;
                })
                ->editColumn('discount', function ($row) {
                    return $row->discount . " %";
                })
                ->editColumn('tax', function ($row) {
                    return $row->tax . " %";
                })
                ->addColumn('sub_type', function ($row) use ($user) {

                    return $row->status;
                })
                ->editColumn('due_date', function ($row) {
                    return date("d-m-Y", strtotime($row->due_date));
                })
                ->addColumn('action', function ($row) use ($invoice_roles, $user) {
                    $html = '<a style="background:none; border:none;"';

                    if ($user->user_type == "admin" || $invoice_roles->read_only == 1 || $invoice_roles->read_write_only == 1) {
                        $html .= ' href="' . route('view_invoice', $row->id) . '"';
                    } else {
                        $html .= ' href="#"';
                    }

                    $html .= ' class="m-0 p-0"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i></a>';

                    return $html;
                })
                ->make(true);
        }
        return view('web.invoice_payment_made', compact('user', 'page', 'invoices', 'roles', 'clients'));
    }

    public function new_invoice()
    {
        $user = $this->check_login();
        $this->set_timezone();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('user_id', '=', $user->id)->get();
        }
        if (count($clients) < 1) {
            return back()->with('noclient', 'no client exists');
        }
        $countries = Countries::get();
        $page = "invoices";
        return view('web.add_invoice', compact('clients', 'user', 'page', 'countries'));
    }

    public function new_invoice_ap()
    {
        $user = $this->check_login();
        $this->set_timezone();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('user_id', '=', $user->id)->get();
        }
        if (count($clients) < 1) {
            return back()->with('noclient', 'no client exists');
        }
        $countries = Countries::get();
        $page = "invoices";
        return view('web.add_invoice_ap', compact('clients', 'user', 'page', 'countries'));
    }

    private function normalizeInvoiceDueDate($input): ?string
    {
        $value = trim((string) $input);

        if ($value === '') {
            return null;
        }

        $formats = ['d-m-y', 'd-m-Y', 'Y-m-d'];

        foreach ($formats as $format) {
            try {
                return \Carbon\Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Try the next format
            }
        }

        return $value;
    }

    private function generateApVendorId(int $subscriberId): string
    {
        do {
            $candidate = 'VND-' . $subscriberId . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            $exists = Internal_Invoices::where('vendor_id', $candidate)->exists();
        } while ($exists);

        return $candidate;
    }

    public function create_new_invoice(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        $user = Auth::user();
        $this->set_timezone();
        $subId =  (Auth::user()->user_type == 'Subscriber') ? $user->id :$user->added_by;
        $inv_setting = Invoice_settings::where('user_id', $subId)->first();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($request->client) {
                $client = Clients::find($request->client);
                $invoice = new Internal_Invoices();
                $invoice->invoice_no = $this->generateInternalInvoiceId();
                $invoice->subscriber_id = $subscriber->id;
                $invoice->user_id = $user->id;
                $invoice->name = $subscriber->name;
                $invoice->email = $subscriber->email;
                $invoice->phone = $subscriber->phone;
                $invoice->country = $subscriber->country;
                $invoice->state = $subscriber->state;
                $invoice->city = $subscriber->city;
                $invoice->pincode = $subscriber->pincode;
                $invoice->address = $subscriber->address_line;
                $invoice->logo = $subscriber->organization_logo;
                $invoice->to_name = $client->name;
                $invoice->to_email = $client->email;
                $invoice->to_phone = $client->phone;
                $invoice->to_country = $client->country;
                $invoice->to_state = $client->state;
                $invoice->to_city = $client->city;
                $invoice->to_pincode = $client->pincode;
                $invoice->to_address = $client->address;
                $invoice->detail = $request['detail'];
                $invoiceAmount = (float) $request['amount'];
                $discountPercent = max(0, min(100, (float) ($inv_setting->discount ?? 0)));
                $taxPercent = max(0, min(100, (float) ($inv_setting->tax ?? 0)));
                $discountRate = $discountPercent / 100;
                $taxRate = $taxPercent / 100;

                $invoice->amount = $invoiceAmount;
                $invoice->type = 'ar';
                $invoice->discount = $discountPercent;
                $invoice->tax = $taxPercent;
                $subtotal = $invoiceAmount - ($invoiceAmount * $discountRate);
                $invoice->total = max(0, $subtotal + ($subtotal * $taxRate));
                $invoice->status = $request['status'];
                $invoice->due_date = $this->normalizeInvoiceDueDate($request['due_date']);
                $invoice->token = $this->generateInternalInvoiceToken();
                $invoice->save();

                if ($invoice->status == "Paid") {
                    $new_invoice = Invoices::where('invoice', '=', $invoice->invoice_no)->first();
                    if ($new_invoice == null) {
                        $new_invoice = new Invoices();
                    }
                    $new_invoice->user_id = $invoice->subscriber_id;
                    $new_invoice->invoice = $invoice->invoice_no;
                    $new_invoice->company_name = $invoice->name;
                    $new_invoice->city = $invoice->city;
                    $new_invoice->state = $invoice->state;
                    $new_invoice->country = $invoice->country;
                    $new_invoice->pincode = $invoice->pincode;
                    $new_invoice->phone = $invoice->phone;
                    $new_invoice->address = $invoice->address;
                    $new_invoice->logo = $invoice->logo;
                    $new_invoice->to_name = $invoice->to_name;
                    $new_invoice->to_company = $user->to_email;
                    $new_invoice->to_city = $invoice->to_city;
                    $new_invoice->to_state = $invoice->to_state;
                    $new_invoice->to_country = $invoice->to_country;
                    $new_invoice->to_pincode = $invoice->to_pincode;
                    $new_invoice->to_phone = $invoice->to_phone;
                    $new_invoice->to_email = $invoice->to_email;
                    $new_invoice->service_fee = $invoice->amount;
                    $new_invoice->discount = ($invoice->amount * ($invoice->discount / 100));
                    $new_invoice->tax = (($invoice->amount - ($invoice->amount * $invoice->discount / 100)) * ($invoice->tax / 100));
                    $new_invoice->total = $invoice->total;
                    $new_invoice->payment_mode = "Cash";
                    $new_invoice->save();
                }
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Invoice Generated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "Invoice generated by " . $user->name . " at " . $request->local_time;
                } else {
                    $activity->activity_detail = "Invoice generated by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                }
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $request->local_time;
                $activity->save();

                $maildata = new \stdClass();
                $maildata->name = $client['name'];
                $maildata->email = $subscriber->email;
                $maildata->from_email = $subscriber->email;
                $maildata->to_email = $client->email;
                $maildata->company_name = $subscriber->organization ?? $subscriber->name;
                $maildata->display_from_email = $subscriber->email;
                $maildata->logo_path = 'web_assets/users/user' . $subscriber->id . '/' . $invoice->logo;
                $maildata->detail = $invoice->detail;
                $maildata->amount = $invoice->amount;
                $maildata->discount = $invoice->discount;
                $maildata->tax = $invoice->tax;
                $maildata->total = $invoice->total;
                $maildata->currency = $subscriber->currency ?? 'Rs.';
                $maildata->status = $invoice->status;
                $maildata->invoice_no = $invoice->invoice_no;
                $maildata->invoice_date = $invoice->created_at;
                $maildata->due_date = $invoice->due_date;
                $maildata->invoice_id = $invoice->id;
                $maildata->token = $invoice->token;
                $maildata->payment_link = $inv_setting->payment_link ?? null;
                $maildata->message = "You have new invoice from " . ($subscriber->organization ?? 'Adwiseri') . " for " . ($subscriber->currency ?? 'Rs.') . " " . number_format($invoice->total, 2) . ".";
                $maildata->from_name = "Sent on behalf of " . ($subscriber->organization ?? $subscriber->name ?? 'Subscriber');
                $maildata->from_email = "alerts@adwiseri.com";
                $maildata->reply_to_email = $subscriber->email;
                $maildata->reply_to_name = $subscriber->name ?? 'Subscriber';
                try {
                    Mail::to($client->email)->send(new Invoicemail($maildata));
                } catch (\Exception $e) {
                    // skip the error (optional log)
                    \Log::warning('Invoice email not sent to: '.$client->email);
                }
                if (Mail::failures()) {
                    echo 'Sorry! Please try again latter';
                } else {
                    echo 'Success';
                }
                return redirect()->route('invoices')->with('invoice_generated', 'Invoice created Successfully.');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function create_new_invoice_ap(Request $request)
    {
        $request->validate([
            'invoice_vendor_id' => 'required|string|min:2|max:100',
            'vendor_name' => 'required|string|min:2|max:150',
            'service_taken' => 'required|string|min:2|max:200',
            'amount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'tax' => 'required|numeric|min:0|max:100',
            'total_to_pay' => 'required|numeric|min:0',
            'upload_invoice' => 'required|file|mimes:pdf|max:10240',
        ]);
        $user = Auth::user();
        $this->set_timezone();
        $subId =  (Auth::user()->user_type == 'Subscriber') ? $user->id :$user->added_by;
        $inv_setting = Invoice_settings::where('user_id', $subId)->first();
        
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $client = $request->client ? Clients::find($request->client) : null;
            $vendorName = trim((string) $request->vendor_name);
            $vendorInvoiceId = trim((string) $request->invoice_vendor_id);
            $serviceTaken = trim((string) $request->service_taken);
            $vendorId = $this->generateApVendorId((int) $subscriber->id);
            $invoice = new Internal_Invoices();
                $invoice->invoice_no = $vendorInvoiceId;
                $invoice->vendor_id = $vendorId;
                $invoice->subscriber_id = $subscriber->id;
                $invoice->user_id = $user->id;
                $invoice->name = $subscriber->name;
                $invoice->email = $subscriber->email;
                $invoice->phone = $subscriber->phone;
                $invoice->country = $subscriber->country;
                $invoice->state = $subscriber->state;
                $invoice->city = $subscriber->city;
                $invoice->pincode = $subscriber->pincode;
                $invoice->address = $subscriber->address_line;
                $invoice->logo = $subscriber->organization_logo;
                $invoice->to_name = $vendorName;
                $invoice->to_email = optional($client)->email;
                $invoice->to_phone = optional($client)->phone;
                $invoice->to_country = optional($client)->country;
                $invoice->to_state = optional($client)->state;
                $invoice->to_city = optional($client)->city;
                $invoice->to_pincode = optional($client)->pincode;
                $invoice->to_address = optional($client)->address;
                $invoice->detail = $serviceTaken;
                $invoiceAmount = (float) $request['amount'];
                $discountPercent = max(0, min(100, (float) $request->discount));
                $taxPercent = max(0, min(100, (float) $request->tax));
                $discountRate = $discountPercent / 100;
                $taxRate = $taxPercent / 100;

                $invoice->amount = $invoiceAmount;
                $invoice->type = 'ap';
                $invoice->discount = $discountPercent;
                $invoice->tax = $taxPercent;
                $subtotal = $invoiceAmount - ($invoiceAmount * $discountRate);
                $calculatedTotal = max(0, $subtotal + ($subtotal * $taxRate));
                $invoice->total = (float) $request->total_to_pay > 0 ? (float) $request->total_to_pay : $calculatedTotal;
                $invoice->status = $request['status'];
                $invoice->due_date = $this->normalizeInvoiceDueDate($request['due_date']);
                $invoice->token = $this->generateInternalInvoiceToken();
                if ($request->hasFile('upload_invoice')) {
                    $pdfFile = $request->file('upload_invoice');
                    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $pdfFile->getClientOriginalName());
                    $destinationPath = 'web_assets/users/user' . $subscriber->id . '/invoice_uploads';
                    $pdfFile->move($destinationPath, $fileName);
                    $invoice->uploaded_invoice = 'user' . $subscriber->id . '/invoice_uploads/' . $fileName;
                }
                $invoice->save();

                if ($invoice->status == "Paid") {
                    $new_invoice = Invoices::where('invoice', '=', $invoice->invoice_no)->first();
                    if ($new_invoice == null) {
                        $new_invoice = new Invoices();
                    }
                    $new_invoice->user_id = $invoice->subscriber_id;
                    $new_invoice->invoice = $invoice->invoice_no;
                    $new_invoice->company_name = $invoice->name;
                    $new_invoice->city = $invoice->city;
                    $new_invoice->state = $invoice->state;
                    $new_invoice->country = $invoice->country;
                    $new_invoice->pincode = $invoice->pincode;
                    $new_invoice->phone = $invoice->phone;
                    $new_invoice->address = $invoice->address;
                    $new_invoice->logo = $invoice->logo;
                    $new_invoice->to_name = $invoice->to_name;
                    $new_invoice->to_company = $user->to_email;
                    $new_invoice->to_city = $invoice->to_city;
                    $new_invoice->to_state = $invoice->to_state;
                    $new_invoice->to_country = $invoice->to_country;
                    $new_invoice->to_pincode = $invoice->to_pincode;
                    $new_invoice->to_phone = $invoice->to_phone;
                    $new_invoice->to_email = $invoice->to_email;
                    $new_invoice->service_fee = $invoice->amount;
                    $new_invoice->discount = ($invoice->amount * ($invoice->discount / 100));
                    $new_invoice->tax = (($invoice->amount - ($invoice->amount * $invoice->discount / 100)) * ($invoice->tax / 100));
                    $new_invoice->total = $invoice->total;
                    $new_invoice->payment_mode = "Cash";
                    $new_invoice->save();
                }
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Invoice Generated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "Invoice generated by " . $user->name . " at " . $request->local_time;
                } else {
                    $activity->activity_detail = "Invoice generated by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                }
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $request->local_time;
                $activity->save();

                $maildata = new \stdClass();
                $maildata->name = $vendorName;
                $maildata->email = $subscriber->email;
                $maildata->from_email = $subscriber->email;
                $maildata->to_email = optional($client)->email ?? $subscriber->email;
                $maildata->company_name = $subscriber->organization ?? $subscriber->name;
                $maildata->display_from_email = $subscriber->email;
                $maildata->logo_path = 'web_assets/users/user' . $subscriber->id . '/' . $invoice->logo;
                $maildata->detail = $invoice->detail;
                $maildata->amount = $invoice->amount;
                $maildata->discount = $invoice->discount;
                $maildata->tax = $invoice->tax;
                $maildata->total = $invoice->total;
                $maildata->currency = $subscriber->currency ?? 'Rs.';
                $maildata->status = $invoice->status;
                $maildata->invoice_no = $invoice->invoice_no;
                $maildata->invoice_date = $invoice->created_at;
                $maildata->due_date = $invoice->due_date;
                $maildata->invoice_id = $invoice->id;
                $maildata->token = $invoice->token;
                $maildata->payment_link = $inv_setting->payment_link ?? null;
                $maildata->message = "You have new invoice from " . ($subscriber->organization ?? 'Adwiseri') . " for " . ($subscriber->currency ?? 'Rs.') . " " . number_format($invoice->total, 2) . ".";
                $maildata->from_name = "Sent on behalf of " . ($subscriber->organization ?? $subscriber->name ?? 'Subscriber');
                $maildata->from_email = "alerts@adwiseri.com";
                $maildata->reply_to_email = $subscriber->email;
                $maildata->reply_to_name = $subscriber->name ?? 'Subscriber';
                // Mail::to($client->email)->send(new Invoicemail($maildata));
                try {
                    if (!empty(optional($client)->email)) {
                        Mail::to($client->email)->send(new Invoicemail($maildata));
                    }
                } catch (\Exception $e) {
                    // skip the error (optional log)
                    \Log::warning('Invoice email not sent for AP invoice_no: '.$invoice->invoice_no);
                }
                 if (Mail::failures()) {
                    echo 'Sorry! Please try again latter';
                } else {
                    echo 'Success';
                }
            return redirect()->route('invoice_payment_made')->with('invoice_generated', 'Invoice created Successfully.');
        } else {
            return redirect()->route('login');
        }
    }

    public function view_invoice($id)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $roles = UserRoles::where('user_id', '=', $user->id)->first();
        $page = "invoices";
        $invoice = Internal_Invoices::find($id);
        $u = User::where('email', '=', $invoice->email)->first();
        $invoiceSetting = Invoice_settings::where('user_id', $u->id)->first();
        if ($invoiceSetting) {
            $invoice->discount = $invoiceSetting->discount;
            $invoice->tax = $invoiceSetting->tax;
            $invoice->paymenyt_link = $invoiceSetting->payment_link;
        }
        return view('web.view_invoice', compact('user', 'u', 'page', 'invoice', 'roles', 'invoiceSetting'));
    }

    public function invoice_preview($id, $token)
    {

        $invoice = Internal_Invoices::where('id', '=', $id)->where('token', '=', $token)->first();
        if ($invoice) {
            $u = User::where('email', '=', $invoice->email)->first();
            $invoiceSetting = Invoice_settings::where('user_id', $u->id)->first();
            if ($invoiceSetting) {
                $invoice->discount = $invoiceSetting->discount;
                $invoice->tax = $invoiceSetting->tax;
                $invoice->paymenyt_link = $invoiceSetting->payment_link;
            }
            return view('web.invoice_preview', compact('u', 'invoice', 'invoiceSetting'));
        } else {
            echo "NO INVOICE FOUND.";
            exit();
        }
    }

    public function print_invoice($id)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        $page = "invoices";
        $invoice = Internal_Invoices::find($id);
        $u = User::where('email', '=', $invoice->email)->first();
        $invoiceSetting = Invoice_settings::where('user_id', $u->id)->first();
        if ($invoiceSetting) {
            $invoice->discount = $invoiceSetting->discount;
            $invoice->tax = $invoiceSetting->tax;
            $invoice->paymenyt_link = $invoiceSetting->payment_link;
        }
        return view('web.print_invoice', compact('user', 'u', 'page', 'invoice', 'invoiceSetting'));
    }

    public function delete_invoice($id = null, $localtime = null)
    {
        if (!empty($id)) { //edit the page.
            $user = Auth::user();
            $this->set_timezone();
            if ($user) {
                $invoice = Invoices::find($id);
                $invoice->delete();
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Invoice Deleted";
                $activity->activity_detail = $user->name . " deleted an Invoice at " . $localtime;
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $localtime;
                $activity->save();
                return back()->with('deleted', 'Job deleted successfully');
            } else {
                return redirect()->route('login');
            }
        } else { //view the page.
            return back();
        }
    }

    public function invoice_status(Request $request)
    {
        if (!empty($request->id)) { //edit the page.
            $user = Auth::user();
            if ($user) {
                $invoice = Internal_Invoices::find($request->id);
                $invoice->status = $request->status;
                $invoice->save();
                if ($request->status == "Paid") {
                    $new_invoice = Invoices::where('invoice', '=', $invoice->invoice_no)->first();
                    if ($new_invoice == null) {
                        $new_invoice = new Invoices();
                    }
                    $new_invoice->user_id = $invoice->subscriber_id;
                    $new_invoice->invoice = $invoice->invoice_no;
                    $new_invoice->company_name = $invoice->name;
                    $new_invoice->city = $invoice->city;
                    $new_invoice->state = $invoice->state;
                    $new_invoice->country = $invoice->country;
                    $new_invoice->pincode = $invoice->pincode;
                    $new_invoice->phone = $invoice->phone;
                    $new_invoice->address = $invoice->address;
                    $new_invoice->logo = $invoice->logo;
                    $new_invoice->to_name = $invoice->to_name;
                    $new_invoice->to_company = $user->to_email;
                    $new_invoice->to_city = $invoice->to_city;
                    $new_invoice->to_state = $invoice->to_state;
                    $new_invoice->to_country = $invoice->to_country;
                    $new_invoice->to_pincode = $invoice->to_pincode;
                    $new_invoice->to_phone = $invoice->to_phone;
                    $new_invoice->to_email = $invoice->to_email;
                    $new_invoice->service_fee = $invoice->amount;
                    $new_invoice->discount = ($invoice->amount * ($invoice->discount / 100));
                    $new_invoice->tax = (($invoice->amount - ($invoice->amount * $invoice->discount / 100)) * ($invoice->tax / 100));
                    $new_invoice->total = $invoice->total;
                    $new_invoice->payment_mode = "Cash";
                    $new_invoice->save();
                }
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Invoice Updated";
                $activity->activity_detail = $user->name . " Update an Invoice status at " . $request->localtime;
                $activity->activity_icon = "invoice.jpg";
                $activity->local_time = $request->localtime;
                $activity->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'no user']);
            }
        } else { //view the page.
            return response()->json(['status' => 'no invoice']);
        }
    }

    public function job_role($id = "")
    {
        $user = Auth::user();
        $this->set_timezone();
        $page = "job_role";
        $job_roles = Job_roles::where('user_id', '=', $user->id)->get();
        return view('web.job_role', compact('user', 'page', 'job_roles'));
    }

    public function add_job_role()
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $page = "job_role";
            return view('web.add_job_role', compact('user', 'page'));
        } else {
            return back();
        }
    }

    public function add_new_job_role(request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if (isset($request->id)) {
            $job_role = Job_roles::find($request->id);
            $this->validate(
                $request,
                [
                    'job_role' => 'required',
                ]
            );
            $job_role->job_role = $request['job_role'];
            $job_role->save();
            $activity = new Activities();
            $activity->subscriber_id = $user->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Job Role Updated";
            $activity->activity_detail = $user->name . " updates job role " . $request->job_role . " at " . $request->local_time;
            $activity->activity_icon = "job_icon.png";
            $activity->local_time = $request->local_time;
            $activity->save();
            return back()->with("job_updated", "Job Updated Successfully");
        }
        $data = new Job_roles();
        $this->validate(
            $request,
            [
                'job_role' => 'required|string|max:255|unique:job_roles',
            ]
        );
        $data->user_id = $user->id;
        $data->job_role = $request['job_role'];
        $data->save();
        $activity = new Activities();
        $activity->subscriber_id = $user->id;
        $activity->user_id = $user->id;
        $activity->activity_name = "New Job Role Added";
        $activity->activity_detail = $user->name . " added new job role " . $request->job_role . " at " . $request->local_time;
        $activity->activity_icon = "job_icon.png";
        $activity->local_time = $request->local_time;
        $activity->save();
        return redirect()->route('job_role')->with('job_added', "Job Role Added Successfully");
    }

    public function delete_job_role($id = null, $localtime = null)
    {
        if (!empty($id)) { //edit the page.
            $user = Auth::user();
            $this->set_timezone();
            if ($user) {
                $job_role = Job_roles::find($id);
                $job_role->delete();
                $activity = new Activities();
                $activity->subscriber_id = $user->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Job Role Deleted";
                $activity->activity_detail = $user->name . " deleted job role " . $job_role->job_role . " at " . $localtime;
                $activity->activity_icon = "job_icon.png";
                $activity->local_time = $localtime;
                $activity->save();
                return back()->with('deleted', 'Job deleted successfully');
            } else {
                return redirect()->route('login');
            }
        } else { //view the page.
            return back();
        }
    }

    public function aboutadvisori()
    {
        $user = Auth::user();
        $this->set_timezone();
        $about_adwiseri = About_Advisori::first();
        $page = "about_adwiseri";
        return view('web.aboutadvisori', compact('user', 'about_adwiseri', 'page'));
    }

    public function contactus()
    {
        $user = Auth::user();
        $this->set_timezone();
        $contact = Contactus::first();
        $countries = Countries::get();
        $page = "contact_us";
        return view('web.contactus', compact('user', 'contact', 'countries', 'page'));
    }

    public function post_contact(Request $request)
    {
        $maildata = new \stdClass();
        $maildata->name = $request['name'];
        $maildata->email = $request['email'];
        $maildata->phone = $request['phone'];
        $maildata->country = $request['country'];
        $maildata->city = $request['city'];
        $maildata->message = $request['message'];
        $maildata->contact = "True";
        Mail::to("seimpex1@gmail.com")->send(new EmailVerification($maildata));
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Success';
        }
        Mail::to("care@adwiseri.com")->send(new EmailVerification($maildata));
        if (Mail::failures()) {
            echo 'Sorry! Please try again latter';
        } else {
            echo 'Success';
            return redirect()->route('contactus')->with('message_sent', 'message send success');
        }
    }

    public function privacy_policy()
    {
        $user = Auth::user();
        $this->set_timezone();
        $page = "privacy_policy";
        return view('web.privacy_policy', compact('user', 'page'));
    }

    public function terms_conditions()
    {
        $user = Auth::user();
        $page = "terms_conditions";
        return view('web.terms_conditions', compact('user', 'page'));
    }

    public function terms_use()
    {
        $user = Auth::user();
        $page = "terms_use";
        return view('web.terms_use', compact('user', 'page'));
    }

    public function refund_policy()
    {
        $user = Auth::user();
        $page = "refund_policy";
        return view('web.refund_policy', compact('user', 'page'));
    }

    public function support()
    {
        $user = $this->check_login();
        if ($user->type_user != "affiliate" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user) {
            $type =  ($user->type_user == 'affiliate') ? 'Affiliates' : Null;
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            $faqs = Faq::where('type', $type)->get();
            $page = "support";
            if ($user->type_user == 'affiliate') {

                return view('affiliate.support', compact('user', 'page', 'faqs', 'roles'));
            } else {
                return view('web.support', compact('user', 'page', 'faqs', 'roles'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function ask_support()
    {
        $user = $this->check_login();
        $this->set_timezone();
        if ($user->type_user != "affiliate" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user) {
            if ($user->user_type == "Subscriber" || $user->user_type == "Affiliate") {
                $subscriber = $user;
                $tickets = Tickets::where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
            } else {
                $subscriber = User::find($user->added_by);
                $tickets = Tickets::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
            }
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            $page = "support";
            if ($user->type_user == 'affiliate') {

                return view('affiliate.ask_support', compact('user', 'page', 'clients', 'subscriber', 'tickets'));
            } else {

                return view('web.ask_support', compact('user', 'page', 'clients', 'subscriber', 'tickets'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function ask_new_question(Request $request)
    {
        function ticket()
        {
            $str = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $tic = "";
            for ($i = 0; $i < 8; $i++) {
                $tic = $tic . $str[rand(0, strlen($str) - 1)];
            }
            return $tic;
        }
        $user = Auth::user();
        if (!$user) {
            $user = auth()->guard('affiliates')->user();
            $user = User::where('email', $user->email)->first();
            $user['type_user'] = 'affiliate';
        }

        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Affiliate") {
                $subscriber = $user;
            } else {
                $subscriber =  empty($user->added_by) ? User::find($user->id) : User::find($user->added_by);
            }
            $this->validate($request, [
                'support' => 'required',
                'question' => 'required',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png|max:4096'
            ], [
                'attachment.mimes' => 'Please select a valid file format (jpg, jpeg, png).',
                'attachment.max' => 'Please select file up to 4MB.'
            ]);
            $data = new Tickets();
            $data->ticket_no = ticket();
            $data->user_id = $user->id;
            $data->subscriber_id = $subscriber->id;
            // $data->client_id = $request['client'];
            $data->issue = $request['question'];
            $data->status = "Open";
            $data->support = $request['support'];
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . rand(100, 999) . "." . $extension;
                $file->move('web_assets/users/ticket_images/', $filename);
                $data->attachment = $filename;
            }
            $data->save();
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Query Ticket Sent";
            if ($user->user_type == "Subscriber") {
                $activity->activity_detail = "Ticket raised by " . $user->name . " at " . $request->local_time;
            } else {
                $activity->activity_detail = "Ticket raised by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
            }
            $activity->activity_icon = "user.png";
            $activity->local_time = $request->local_time;
            $activity->save();

            $maildata = new \stdClass();
            $maildata->ticket_id = $data['ticket_no'];
            $maildata->subscriber_id = $data['subscriber_id'];
            $maildata->support = $data['support'];
            $maildata->date = $data['created_at'];
            $maildata->issue = $data['issue'];
            $maildata->attachment = $data['attachment'];
            $maildata->contact = "True";
            Mail::to("seimpex1@gmail.com")->send(new SupportMail($maildata));
            if (Mail::failures()) {
                echo 'Sorry! Please try again latter';
            } else {
                echo 'Success';
            }
            Mail::to("care@adwiseri.com")->send(new SupportMail($maildata));
            if (Mail::failures()) {
                echo 'Sorry! Please try again latter';
            } else {
                echo 'Success';
            }
            if ($user->type_user == 'Affiliate') {
                return redirect()->route('ask_support')->with('success', 'data sent to support');
            } else {
                return redirect()->route('ask_support_affiliate')->with('success', 'data sent to support');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function my_query($id)
    {
        $user = Auth::user() ?? Auth::guard('affiliates')->user()->user;

        if ($user) {
            if ($id) {
                $query = Tickets::find($id);
                $page = "support";
                $template = ($user->user_type != 'Affiliate') ?  'web.my_query' :'affiliate.my_query';

                return view($template, compact('user', 'page', 'query'));
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function sub_reports()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now")) && $user->user_type != 'admin') {

            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user->user_type == "Subscriber" || $user->user_type == "admin") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }


        if ($subscriber->category == "Law Firm") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } elseif ($subscriber->category == "Travel Agency") {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->get();
        } else {
            $client_jobs = Client_jobs::where('category', '=', $subscriber->category)->where('sub_category', '=', $subscriber->sub_category)->get();
        }
        $visa_categories = Subscriber_Sub_Categories::where('category_name', '=', 'Visas & Immigration Advisory')->get();
        $law_categories = Subscriber_Sub_Categories::where('category_name', '=', 'Law Firm')->get();
        $travel_categories = Countries::get();
        $total_apps = array();
        $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();

        foreach ($client_jobs as $job) {
            $categ = $job->job;
            $categ_app = 0;
            foreach ($applications as $app) {
                if ($categ == $app->application_name) {
                    $categ_app += 1;
                }
            }
            $total_apps[$categ] = $categ_app;
        }
        $internal_invoices = Internal_Invoices::where('subscriber_id', '=', $subscriber->id)->get();
        $internal_total = 0;
        foreach ($internal_invoices as $inv) {
            $internal_total += $inv->total;
        }
        $unpaid = Internal_Invoices::where('subscriber_id', '=', $subscriber->id)->where('status', '=', 'UnPaid')->get();
        $unpaid_total = 0;
        foreach ($unpaid as $inv) {
            $unpaid_total += $inv->total;
        }
        $invoices = Invoices::where('user_id', '=', $subscriber->id)->get();
        $total = 0;
        foreach ($invoices as $inv) {
            $total += $inv->total;
        }
        $total_invoices = count($internal_invoices);
        $total_paid = $total_invoices - count($unpaid);
        $total_unpaid = count($unpaid);
        $total_amt = $internal_total;
        $paid_total = $total_amt - $unpaid_total;
        $page = "reports";

        $price_plans = Membership::orderBy('created_at', 'asc')->get();


        return view('web.reports', compact('user', 'total_apps', 'page', 'applications', 'total_invoices', 'total_paid', 'total_unpaid', 'total_amt', 'paid_total', 'unpaid_total', 'price_plans'));
    }

    public function communications()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $siteusers = User::where('added_by', '=', $subscriber->id)->get();
            } else {
                $subscriber = User::find($user->added_by);
                $siteusers = false;
            }


            if ($user->user_type == "admin") {

                $messages = Internal_communications::orderBy('created_at', 'desc')->get();
            } else {
                $messages = Internal_communications::where('user_id', '=', $user->id)->orwhere('send_to', 'like', '%' . $user->id . '%')->orderBy('created_at', 'desc')->get();
            }
            $page = "communications";
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if (request()->ajax()) {
                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();

                $communication_roles = null;
                if ($user->user_type != "admin") {
                    $communication_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Communication')->first();
                }
                $messages = $messages->whereBetween('created_at', [$startDate, $endDate]);

                return DataTables::of($messages)
                    ->addIndexColumn()
                    ->addColumn('recevier_name', function ($row) use ($user) {
                        if ($row->send_by == 1) {
                            $receiver = $user->name;
                        } else {
                            $receiver = "";
                            $receivernames = json_decode($row->receiver_name, true);
                            foreach ($receivernames as $k => $name) {
                                if ($k == count($receivernames) - 1) {
                                    $receiver = $receiver . $name;
                                } else {
                                    $receiver = $receiver . $name . ", ";
                                }
                            }
                        }
                        if (strlen($receiver) > 22) {
                            return substr($receiver, 0, 22) . "...";
                        } else {
                            return $receiver;
                        }
                    })
                    ->editColumn('created_at', function ($row) {
                        return date("d-m-Y H:i:s", strtotime($row->created_at));
                    })
                    ->editColumn('message', function ($row) {
                        if (strlen($row->message) > 22) {
                            return substr($row->message, 0, 22) . '...';
                        } else {
                            return $row->message;
                        }
                    })
                    ->addColumn('action', function ($row) use ($communication_roles, $user) {
                        $html = "<a ";
                        if ($user->user_type == "admin" || $communication_roles->read_only == 1 || $communication_roles->read_write_only == 1) {
                            $html .= 'href = ' . route('view_message', $row->id) . ' ';
                        } else {
                            $html .= 'href = "#"';
                        }
                        $html .= ' style="text-decoration:none;background:none;border:none"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:14px;"></i>';
                        return $html;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('web.communications', compact('user', 'page', 'messages', 'siteusers', 'roles'));
        } else {
            return redirect()->route('login');
        }
    }

    public function messaging()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $siteusers = User::where('added_by', '=', $subscriber->id)->get();
            } else {
                $subscriber = User::find($user->added_by);
                $siteusers = false;
            }
            $page = "messaging";
            return view('web.messaging', compact('user', 'page', 'siteusers'));
        } else {
            return redirect()->route('login');
        }
    }

    public function communicate(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->validate($request, [
                'sendto' => 'required',
                'message' => 'required|string',
            ]);

            // Function to generate a unique communication ID
            function communication_id()
            {
                $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $id = "";
                for ($i = 0; $i < 7; $i++) {
                    $id = $id . $ch[rand(0, strlen($ch) - 1)];
                }
                if (Internal_communications::where('communication_id', '=', $id)->first()) {
                    return communication_id();
                }
                return $id;
            }

            $communication_id = communication_id();
            $subscriber = $user->user_type == "Subscriber" ? $user : User::find($user->added_by);

            // Fetch the membership plan for the user
            $membership_plan = Membership::where('plan_name', '=', $user->membership)->first();
            if (!$membership_plan) {
                return back()->with('error', 'Invalid membership plan.');
            }

            // Get the messaging limit from the membership plan
            $messageLimitPerYear = $membership_plan->messaging; // Assuming message_limit exists in Membership table

            // Count the messages sent by the user in the current year
            $currentYear = now()->year;
            $messagesSentThisYear = Internal_communications::where('send_by', $user->id)
                ->whereYear('created_at', $currentYear) // Filter messages by the current year
                ->count();
            // If the user has exceeded the limit for this year, show an error
            if ($messagesSentThisYear >= $messageLimitPerYear) {
                return back()->with('error', 'You have exceeded your message limit for this year.');
            }

            // Proceed to send the message
            $sendto = $request->sendto;
            $receiver_id = array();
            $receiver_name = array();
            // dd(  $sendto );
            if ($sendto != null) {
                if (count($sendto)) {
                    // Handle sending messages to admin and all users
                    if (in_array('admin', $sendto)) {
                        $admin = User::where('user_type', '=', 'admin')->first();
                        array_push($receiver_id, $admin->id);
                        array_push($receiver_name, $admin->name);
                        $admin_index = array_search('admin', $sendto);
                        array_splice($sendto, $admin_index, 1);
                    }

                    if (in_array('all user', $sendto)) {
                        $siteusers = User::where('added_by', '=', $subscriber->id)->get();
                        foreach ($siteusers as $suser) {
                            array_push($receiver_id, $suser->id);
                            array_push($receiver_name, $suser->name);
                        }
                        $admin_index = array_search('admin', $sendto);
                        array_splice($sendto, $admin_index, 1);
                    } else {
                        if (count($sendto)) {
                            foreach ($sendto as $uid) {
                                $suser = User::find($uid);
                                array_push($receiver_id, $suser->id);
                                array_push($receiver_name, $suser->name);
                            }
                        }
                    }

                    // Save the message
                    $message = new Internal_communications();
                    $message->subscriber_id = $subscriber->id;
                    $message->communication_id = $communication_id;
                    $message->user_id = $user->id;
                    $message->send_by = $user->id;
                    $message->send_to = json_encode($receiver_id, true);
                    $message->sender_name = $user->name;
                    $message->receiver_name = json_encode($receiver_name, true);
                    $message->message = $request['message'];
                    $message->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id ;
                    $activity->user_id = $subscriber->id;
                    $activity->user_name =  $subscriber->name;
                    $activity->activity_name = "New Message";
                    if (auth()->user()->user_type == "Subscriber") {
                        $activity->activity_detail = "New  message sent by" .  auth()->user()->name . " at " . $request->local_time;
                    } else {
                        $activity->activity_detail = "New  message sent by " .  auth()->user()->name . "(" . auth()->user()->name . ") at " . $request->local_time;
                    }
                    $activity->activity_icon = "invoice.jpg";
                    $activity->local_time = $request->local_time;
                    $activity->save();

                    return back()->with('sent', 'Message sent successfully!');
                } else {
                    return back()->with('noUser', 'No user selected');
                }
            } else {
                return back()->with('noUser', 'No user selected');
            }
        } else {
            return redirect()->route('login');
        }
    }


    public function view_message($id = null)
    {
        $user = Auth::user();
        if ($id) {
            $page = "communications";
            $message = Internal_communications::find($id);
            return view('web.view_message', compact('message', 'user', 'page'));
        }
    }

    public function client_discussion()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $discussions = Client_discussions::where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
                $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            } else {
                $subscriber = User::find($user->added_by);
                $discussions = Client_discussions::where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
                $clients = Clients::where('user_id', '=', $user->id)->get();
            }
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();
            $page = "communications";
            return view('web.client_discussion', compact('user', 'roles', 'page', 'discussions', 'subscriber', 'clients', 'applications'));
        } else {
            return redirect()->route('login');
        }
    }

    public function post_client_discussion(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($request) {
                $client = Clients::find($request->client);
                $discussion = new Client_discussions();
                $discussion->subscriber_id = $subscriber->id;
                $discussion->user_id = $user->id;
                $discussion->user_name = $user->name;
                $discussion->client_id = $request['client'];
                $discussion->client_name = $client->name;
                $discussion->application_id = $request['application'];
                $discussion->communication_type = $request['communication_type'];
                $discussion->communication_date = $request['communication_date'];
                $discussion->discussion = $request['discussion'];
                $discussion->save();
                return redirect()->back()->with('disucssion_saved', 'Discussion Saved Successfully');
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function user_applications()
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user) {
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $assignments = Application_assignments::whereNotNull('application_id')->whereHas('application')->where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
            } else {
                $subscriber = User::find($user->added_by);
                $assignments = Application_assignments::whereNotNull('application_id')->whereHas('application')->where('subscriber_id', '=', $subscriber->id)->where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
            }
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            $siteusers = User::where('designation', 'Consultant/Advisor')->where('added_by', '=', $subscriber->id)->get();
            $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();
            $page = "applications";
            return view('web.user_applications', compact('roles', 'assignments', 'user', 'page', 'clients', 'siteusers', 'applications'));
        } else {
            return redirect()->route('login');
        }
    }

    public function update_application_assignment($id)
    {
        $user = Auth::user();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $assignment = Application_assignments::find($id);
        $client = Clients::find($assignment->client_id);
        $applications = Applications::where('client_id', '=', $client->id)->get();
        $advisors = User::where('id', '=', $client->subscriber_id)->orwhere('added_by', '=', $client->subscriber_id)->get();
        $page = "applications";
        return view('web.update_application_assignment', compact('assignment', 'user', 'advisors', 'page', 'client', 'applications'));
    }

    public function user_app_assignment(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
            $assignment = Application_assignments::find($request->id);
            if ($assignment) {
                $u = User::find($request->user_id);
                $assignment->client_id = $request['client_id'];
                $assignment->application_id = $request['application_id'];
                $assignment->subscriber_id = $u->added_by;
                $assignment->user_id = $request['user_id'];
                $assignment->user_name = $u->name;
                $assignment->save();
                $app = Applications::where('application_id', '=', $request['application_id'])->first();
                $app->assign_to = $u->id;
                $app->save();
                $activity = new Activities();
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Application Assign Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "Application Assign updated by " . $user->name . " at " . $request->local_time;
                } else {
                    $activity->activity_detail = "Application Assign updated by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return redirect()->route('user_applications')->with('assignment_updated', "Assignment Updated successfully");
            } else {
                $client = Clients::find($request->client_id);
                if ($client) {
                    $assignment = new Application_assignments();
                    $u = User::find($request->user_id);
                    $assignment->client_id = $request['client_id'];
                    $assignment->application_id = $request['application_id'];
                    $assignment->user_id = $request['user_id'];
                    $assignment->subscriber_id = $u->added_by;
                    $assignment->user_name = $u->name;
                    $assignment->save();
                    $app = Applications::where('application_id', '=', $request['application_id'])->first();
                    $app->assign_to = $u->id;
                    $app->save();
                    $activity = new Activities();
                    $activity->client_id = $client->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "Assignment Added";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = "New Assignment added by " . $user->name . " at " . $request->local_time;
                    } else {
                        $activity->activity_detail = "New Assignment added by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                    }
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return redirect()->route('user_applications')->with('assignment_added', "Assignment added successfully");
                } else {
                    return back();
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function client_documents()
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        if ($user) {
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            if ($user->user_type != "admin" && $user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
            if ($user->user_type == "Subscriber" || $user->user_type == "admin") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($user->user_type == 'admin') {

                $applications = Applications::get();
                $clients = Clients::get();
            } else {
                $applications = Applications::where('subscriber_id', '=', $subscriber->id)->get();
                $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            }
            $page = "applications";
            if ($user->user_type == "admin") {
                $client_docs = Client_Docs::whereNotNull('application_id')->orderBy('created_at', 'desc')->get();
            } else {

                $client_docs = Client_Docs::whereNotNull('application_id')->whereHas('application')->where('user_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
            }

            if (request()->ajax()) {
                $application_roles = null;
                if ($user->user_type != "admin") {

                    $application_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Applications')->first();
                }

                $startDate = Carbon::parse(request()->startdate)->startOfDay();
                $endDate = Carbon::parse(request()->enddate)->endOfDay();

                $client_docs = $client_docs->whereBetween('created_at', [$startDate, $endDate]);
                return DataTables::of($client_docs)
                    ->editColumn('created_at', function ($row) {
                        return date("d-m-Y", strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        $html = '<a style="background:transparent;border:none;" class="p-0 m-0 text-dark" href=' . route('application_view', $row->id) . '><i class="fa-solid fa-eye btn text-info p-1 m-0"></i></a>';
                        return $html;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('web.client_documents', compact('roles', 'applications', 'user', 'page', 'clients', 'client_docs'));
        } else {
            return redirect()->route('login');
        }
    }

    public function client_document_update($id)
    {
        $user = $this->check_login();
        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
        }
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $document = Client_docs::find($id);
            $clients = Clients::get();
            $application  = Applications::where('application_id', $document->application_id)->first();
            $page = "applications";
            return view('web.client_document_update', compact('document', 'user', 'page', 'clients', 'application'));
        } else {
            return redirect()->route('login');
        }
    }

    public function upload_client_document(Request $request)
    {
        $user = Auth::user();
        $this->set_timezone();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
            $document = Client_Docs::find($request->id);
            $docFileRule = $document ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096' : 'required|file|mimes:jpg,jpeg,png,pdf|max:4096';
            $this->validate($request, [
                'doc_file' => $docFileRule,
            ], [
                'doc_file.mimes' => 'Please select a valid file format (jpg, jpeg, png, pdf).',
                'doc_file.max' => 'Please select file up to 4MB.',
            ]);
            if ($document) {
                $client = Clients::find($request->client_id);
                $application = Applications::find($request->application_id);
                $subscriber = User::find($client->subscriber_id);
                $document->client_id = $request['client_id'];
                $document->application_id = $application->application_id;
                $document->user_id = $subscriber->id;
                $document->doc_type = $request['doc_type'];
                $document->doc_name = $request['doc_name'];
                if ($request->hasFile('doc_file')) {
                    $file = $request->file('doc_file');
                    $extension = $file->getClientOriginalName();
                    $filename = time() . $extension;
                    $file->move('web_assets/users/client' . $document->client_id . '/docs/', $filename);
                    $document->doc_file = $filename;
                }
                $document->save();
                $activity = new Activities();
                $activity->subscriber_id = $subscriber->id;
                $activity->user_id = $user->id;
                $activity->user_name = $user->name;
                $activity->activity_name = "Document Updated";
                if ($user->user_type == "Subscriber") {
                    $activity->activity_detail = "Document updated by " . $user->name . " at " . $request->local_time;
                } else {
                    $activity->activity_detail = "Document updated by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                }
                $activity->activity_icon = "user.png";
                $activity->local_time = $request->local_time;
                $activity->save();
                return redirect()->route('client_documents')->with('document_updated', "Document Updated successfully");
            } else {
                $client = Clients::find($request->client_id);
                if ($client) {
                    $document = new Client_Docs();
                    $client = Clients::find($request->client_id);
                    $application = Applications::find($request->application_id);
                    $subscriber = User::find($client->subscriber_id);
                    $document->client_id = $request['client_id'];
                    $document->application_id = $application->application_id;
                    $document->user_id = $subscriber->id;
                    $document->doc_name = $request['doc_name'];
                    $document->doc_type = $request['doc_type'];
                    if ($request->hasFile('doc_file')) {
                        $file = $request->file('doc_file');
                        $extension = $file->getClientOriginalName();
                        $filename = time() . $extension;
                        $file->move('web_assets/users/client' . $document->client_id . '/docs/', $filename);
                        $document->doc_file = $filename;
                    }
                    $document->save();
                    $activity = new Activities();
                    $activity->subscriber_id = $subscriber->id;
                    $activity->user_id = $user->id;
                    $activity->user_name = $user->name;
                    $activity->activity_name = "Document Added";
                    if ($user->user_type == "Subscriber") {
                        $activity->activity_detail = "New Document added by " . $user->name . " at " . $request->local_time;
                    } else {
                        $activity->activity_detail = "New Document added by " . $user->name . "(" . $subscriber->name . ") at " . $request->local_time;
                    }
                    $activity->activity_icon = "user.png";
                    $activity->local_time = $request->local_time;
                    $activity->save();
                    return redirect()->route('client_documents')->with('document_added', "Document added successfully");
                } else {
                    return back();
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function my_settings()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('user_membership')->with("price_plan_expiry", "Please renew or upgrade price plan.");
            }
            $roles = UserRoles::where('user_id', '=', $user->id)->first();
            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $page = "settings";
            $currencies = Currency::orderBy('currency_code')->get();
            $inv_setting = Invoice_settings::where('user_id',$user->id)->first();
            $clients = Clients::where('subscriber_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
            $reportSetting = ReportSetting::where('user_id', $user->id)->first();
            $paymentReminderSetting = PaymentReminderSetting::where('user_id', $user->id)->first();
            $emailTemplates = app(EmailTemplateService::class)->getTemplatesForSettings($user);
            $emailTemplateAudience = strtolower($user->user_type) === 'admin' ? 'admin' : 'subscriber';

            $reportModules = [
                'clients' => 'Clients',
                'applications' => 'Applications',
                'invoices' => 'Invoices',
                'payments' => 'Payments',
                'referrals' => 'Referrals',
                'wallets' => 'Wallets',
            ];

            if (strtolower($user->user_type) === 'admin') {
                $reportModules['subscribers'] = 'Subscribers';
                $reportModules['affiliates'] = 'Affiliates';
            }

            return view('web.my_settings', compact('tzlist', 'roles', 'user', 'page', 'currencies', 'inv_setting', 'clients', 'reportSetting', 'paymentReminderSetting', 'reportModules', 'emailTemplates', 'emailTemplateAudience'));
        } else {
            return redirect()->route('login');
        }
    }

    public function update_my_currency(Request $request)
    {

        // dd($request->all());
        $user = Auth::user();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }
            $all_users = User::where('added_by', '=', $subscriber->id)->get();
            foreach ($all_users as $one) {
                // $one->timezone = $request['timezones'];
                $one->currency = $request['currency'];
                $one->save();
            }
            $user->timezone = $request['timezone'];
            $user->currency = $request['currency'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Update Successfully',
                'currency' => $user->currency,
                'timezone' => $user->timezone
            ]);
        } else {
            return redirect()->route('admin');
        }
    }

    public function user_role()
    {
        $user = Auth::user();
        if ($user->user_type == "Subscriber") {
            $allroles = UserRoles::where('subscriber_id', '=', $user->id)->get();
            $roles = $allroles->unique('user_id');
            $page = "user_role";
            return view('web.user_role', compact('user', 'page', 'roles'));
        } else {
            return back();
        }
    }

    public function add_user_role($id = null)
    {
        $user = Auth::user();
        if ($user->user_type == "Subscriber") {
            if ($id != null) {
                $roles = UserRoles::where('user_id', '=', $id)->get();
                $staff = User::find($id);
                $page = "user_role";
                $update = "update";
                return view('web.add_user_role', compact('user', 'page', 'roles', 'update', 'staff'));
            } else {
                $page = "user_role";
                $siteusers = User::where('added_by', '=', $user->id)->get();
                if (count($siteusers) < 1) {
                    return back()->with('no_user', 'no user found.');
                }
                $roles = UserRoles::where('subscriber_id', '=', $user->id)->get();
                $roles = $roles->unique('user_id');
                $existing = array();
                foreach ($roles as $rol) {
                    array_push($existing, $rol->user_id);
                }
                if (count($siteusers) == count($existing)) {
                    return back()->with('all_access', 'all users have access.');
                }
                return view('web.add_user_role', compact('user', 'page', 'siteusers', 'existing'));
            }
        } else {
            return back();
        }
    }

    public function user_role_post(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $role = UserRoles::where('user_id', '=', $request->user_id)->get();
            $staff = User::find($request->user_id);
            if ($role) {
                foreach ($role as $r) {
                    $r->delete();
                }
            }
            if ($request->access_type == 'full_access') {
                $clients = new UserRoles();
                $clients->user_id = $request['user_id'];
                $clients->subscriber_id = $user->id;
                $clients->name = $staff->name;
                $clients->email = $staff->email;
                $clients->module = "Clients";
                $clients->read_only = 1;
                $clients->write_only = 1;
                $clients->update_only = 1;
                $clients->delete_only = 1;
                $clients->read_write_only = 1;
                $clients->save();

                $applications = new UserRoles();
                $applications->user_id = $request['user_id'];
                $applications->subscriber_id = $user->id;
                $applications->name = $staff->name;
                $applications->email = $staff->email;
                $applications->module = "Applications";
                $applications->read_only = 1;
                $applications->write_only = 1;
                $applications->update_only = 1;
                $applications->delete_only = 1;
                $applications->read_write_only = 1;
                $applications->save();

                $communication = new UserRoles();
                $communication->user_id = $request['user_id'];
                $communication->subscriber_id = $user->id;
                $communication->name = $staff->name;
                $communication->email = $staff->email;
                $communication->module = "Communication";
                $communication->read_only = 1;
                $communication->write_only = 1;
                $communication->update_only = 1;
                $communication->delete_only = 1;
                $communication->read_write_only = 1;
                $communication->save();

                $invoices = new UserRoles();
                $invoices->user_id = $request['user_id'];
                $invoices->subscriber_id = $user->id;
                $invoices->name = $staff->name;
                $invoices->email = $staff->email;
                $invoices->module = "Invoices";
                $invoices->read_only = 1;
                $invoices->write_only = 1;
                $invoices->update_only = 1;
                $invoices->delete_only = 1;
                $invoices->read_write_only = 1;
                $invoices->save();

                $payments = new UserRoles();
                $payments->user_id = $request['user_id'];
                $payments->subscriber_id = $user->id;
                $payments->name = $staff->name;
                $payments->email = $staff->email;
                $payments->module = "Payments";
                $payments->read_only = 1;
                $payments->write_only = 1;
                $payments->update_only = 1;
                $payments->delete_only = 1;
                $payments->read_write_only = 1;
                $payments->save();

                $reports = new UserRoles();
                $reports->user_id = $request['user_id'];
                $reports->subscriber_id = $user->id;
                $reports->name = $staff->name;
                $reports->email = $staff->email;
                $reports->module = "Reports";
                $reports->read_only = 1;
                $reports->write_only = 1;
                $reports->update_only = 1;
                $reports->delete_only = 1;
                $reports->read_write_only = 1;
                $reports->save();

                $subscription = new UserRoles();
                $subscription->user_id = $request['user_id'];
                $subscription->subscriber_id = $user->id;
                $subscription->name = $staff->name;
                $subscription->email = $staff->email;
                $subscription->module = "Subscription";
                $subscription->read_only = 1;
                $subscription->write_only = 1;
                $subscription->update_only = 1;
                $subscription->delete_only = 1;
                $subscription->read_write_only = 1;
                $subscription->save();

                $settings = new UserRoles();
                $settings->user_id = $request['user_id'];
                $settings->subscriber_id = $user->id;
                $settings->name = $staff->name;
                $settings->email = $staff->email;
                $settings->module = "Settings";
                $settings->read_only = 1;
                $settings->write_only = 1;
                $settings->update_only = 1;
                $settings->delete_only = 1;
                $settings->read_write_only = 1;
                $settings->save();

                $support = new UserRoles();
                $support->user_id = $request['user_id'];
                $support->subscriber_id = $user->id;
                $support->name = $staff->name;
                $support->email = $staff->email;
                $support->module = "Support";
                $support->read_only = 1;
                $support->write_only = 1;
                $support->update_only = 1;
                $support->delete_only = 1;
                $support->read_write_only = 1;
                $support->save();
            }
            if ($request->access_type == 'limited_access') {
                $clients = new UserRoles();
                $clients->user_id = $request['user_id'];
                $clients->subscriber_id = $user->id;
                $clients->name = $staff->name;
                $clients->email = $staff->email;
                $clients->module = "Clients";
                $clients->read_only = ($request->clients_read_only == 1) ? 1 : 0;
                $clients->write_only = ($request->clients_write_only == 1) ? 1 : 0;
                $clients->update_only = ($request->clients_update_only == 1) ? 1 : 0;
                $clients->delete_only = ($request->clients_delete_only == 1) ? 1 : 0;
                $clients->read_write_only = ($request->clients_read_write_only == 1) ? 1 : 0;
                $clients->save();

                $applications = new UserRoles();
                $applications->user_id = $request['user_id'];
                $applications->subscriber_id = $user->id;
                $applications->name = $staff->name;
                $applications->email = $staff->email;
                $applications->module = "Applications";
                $applications->read_only = ($request->applications_read_only == 1) ? 1 : 0;
                $applications->write_only = ($request->applications_write_only == 1) ? 1 : 0;
                $applications->update_only = ($request->applications_update_only == 1) ? 1 : 0;
                $applications->delete_only = ($request->applications_delete_only == 1) ? 1 : 0;
                $applications->read_write_only = ($request->applications_read_write_only == 1) ? 1 : 0;
                $applications->save();

                $communication = new UserRoles();
                $communication->user_id = $request['user_id'];
                $communication->subscriber_id = $user->id;
                $communication->name = $staff->name;
                $communication->email = $staff->email;
                $communication->module = "Communication";
                $communication->read_only = ($request->communication_read_only == 1) ? 1 : 0;
                $communication->write_only = ($request->communication_write_only == 1) ? 1 : 0;
                $communication->update_only = ($request->communication_update_only == 1) ? 1 : 0;
                $communication->delete_only = ($request->communication_delete_only == 1) ? 1 : 0;
                $communication->read_write_only = ($request->communication_read_write_only == 1) ? 1 : 0;
                $communication->save();

                $invoices = new UserRoles();
                $invoices->user_id = $request['user_id'];
                $invoices->subscriber_id = $user->id;
                $invoices->name = $staff->name;
                $invoices->email = $staff->email;
                $invoices->module = "Invoices";
                $invoices->read_only = ($request->invoices_read_only) ? 1 : 0;
                $invoices->write_only = ($request->invoices_write_only) ? 1 : 0;
                $invoices->update_only = ($request->invoices_update_only) ? 1 : 0;
                $invoices->delete_only = ($request->invoices_delete_only) ? 1 : 0;
                $invoices->read_write_only = ($request->invoices_read_write_only) ? 1 : 0;
                $invoices->save();

                $payments = new UserRoles();
                $payments->user_id = $request['user_id'];
                $payments->subscriber_id = $user->id;
                $payments->name = $staff->name;
                $payments->email = $staff->email;
                $payments->module = "Payments";
                $payments->read_only = ($request->payments_read_only) ? 1 : 0;
                $payments->write_only = ($request->payments_write_only) ? 1 : 0;
                $payments->update_only = ($request->payments_update_only) ? 1 : 0;
                $payments->delete_only = ($request->payments_delete_only) ? 1 : 0;
                $payments->read_write_only = ($request->payments_read_write_only) ? 1 : 0;
                $payments->save();

                $reports = new UserRoles();
                $reports->user_id = $request['user_id'];
                $reports->subscriber_id = $user->id;
                $reports->name = $staff->name;
                $reports->email = $staff->email;
                $reports->module = "Reports";
                $reports->read_only = ($request->reports_read_only) ? 1 : 0;
                $reports->write_only = ($request->reports_write_only) ? 1 : 0;
                $reports->update_only = ($request->reports_update_only) ? 1 : 0;
                $reports->delete_only = ($request->reports_delete_only) ? 1 : 0;
                $reports->read_write_only = ($request->reports_read_write_only) ? 1 : 0;
                $reports->save();

                $subscription = new UserRoles();
                $subscription->user_id = $request['user_id'];
                $subscription->subscriber_id = $user->id;
                $subscription->name = $staff->name;
                $subscription->email = $staff->email;
                $subscription->module = "Subscription";
                $subscription->read_only = ($request->subscription_read_only) ? 1 : 0;
                $subscription->write_only = ($request->subscription_write_only) ? 1 : 0;
                $subscription->update_only = ($request->subscription_update_only) ? 1 : 0;
                $subscription->delete_only = ($request->subscription_delete_only) ? 1 : 0;
                $subscription->read_write_only = ($request->subscription_read_write_only) ? 1 : 0;
                $subscription->save();

                $settings = new UserRoles();
                $settings->user_id = $request['user_id'];
                $settings->subscriber_id = $user->id;
                $settings->name = $staff->name;
                $settings->email = $staff->email;
                $settings->module = "Settings";
                $settings->read_only = ($request->settings_read_only) ? 1 : 0;
                $settings->write_only = ($request->settings_write_only) ? 1 : 0;
                $settings->update_only = ($request->settings_update_only) ? 1 : 0;
                $settings->delete_only = ($request->settings_delete_only) ? 1 : 0;
                $settings->read_write_only = ($request->settings_read_write_only) ? 1 : 0;
                $settings->save();

                $support = new UserRoles();
                $support->user_id = $request['user_id'];
                $support->subscriber_id = $user->id;
                $support->name = $staff->name;
                $support->email = $staff->email;
                $support->module = "Support";
                $support->read_only = ($request->support_read_only) ? 1 : 0;
                $support->write_only = ($request->support_write_only) ? 1 : 0;
                $support->update_only = ($request->support_update_only) ? 1 : 0;
                $support->delete_only = ($request->support_delete_only) ? 1 : 0;
                $support->read_write_only = ($request->support_read_write_only) ? 1 : 0;
                $support->save();
            }
            return redirect()->route('user_role')->with('role_added', 'user role added');
        } else {
            return redirect()->route('login');
        }
    }

    public function delete_user_role($id = null)
    {
        $user = Auth::user();
        if ($id != null) {
            $role = UserRoles::find($id);
            $role->delete();
            return redirect()->route('user_role')->with('role_deleted', 'user role deleted');
        } else {
            return back();
        }
    }

    public function clientDatatable()
    {
        $user = Auth::user();
        $client_roles = null;
        if ($user->user_type != 'admin') {

            $client_roles = UserRoles::where('user_id', '=', $user->id)->where('module', '=', 'Clients')->first();
        }

        if (request()->ajax()) {

            $startDate = Carbon::parse(request()->startdate)->startOfDay();
            $endDate = Carbon::parse(request()->enddate)->endOfDay();

            if ($user->user_type == 'admin') {

                $clients = Clients::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
            } else {
                $clients = Clients::where('subscriber_id', '=', $user->id)->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
            }

            // dd($clients->toSql(),$clients->getBindings(),$startDate,$endDate);


            return DataTables::of($clients)
              ->addColumn('client_name',function ($row) use ($client_roles, $user) {
                 return $row->name.'('.$row->subscriber_id.')';
              })
                 ->addColumn('noa',function ($row) use ($client_roles, $user) {
                 return $row->applications ? ($row->applications->count() ?? 'No') : 'No User' ;
              })
              ->editColumn('created_at', function ($row) {
                return date("d-m-Y", strtotime($row->created_at));
            })
                ->addColumn('action', function ($row) use ($client_roles, $user) {


                    $html = '<a';
                    if ($user->user_type == 'admin' || ($client_roles->read_only == 1 || $client_roles->read_write_only == 1)) {
                        $html .= ' href="' . route('client_profile', $row->id) . '" ';
                    } else {
                        $html .= ' href = "#"';
                    }

                    $html .= 'style="text-decoration:none; background:none;border:none;padding:0px" > <i class="fa-solid fa-eye btn p-1 text-info" style="font-size:12px;"></i></a>';
                    // <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:12px;" ';

                    // if($client_roles->delete_only == 1)
                    // {
                    //     $html .= 'onclick="deleteclient(' . $row->id . ')" ';

                    // }
                    // $html .= '></i>';
                    return $html;








                    $html = '<a ';

                    if ($client_roles->read_only == 1 || $client_roles->read_write_only == 1) {
                        $html .= 'href="' . route('client_profile', $row->id) . '" ';
                    } else {
                        $html .= 'href="#" ';
                    }

                    $html .= 'style="text-decoration:none;"><i class="fa-solid fa-eye btn p-1 text-info" style="font-size:12px;"></i></a>';

                    $html .= '<i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:12px;" ';

                    if ($client_roles->delete_only == 1) {
                        $html .= 'onclick="deleteclient(' . $row->id . ')" ';
                    }

                    $html .= '></i>';

                    return $html;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function Affiliates_Reg()
    {
        $countries = Countries::get();
        return view('web.affiliates_reg', compact('countries'));
    }
    public function Affiliates_store(Request $req)
    {

        if($req->has('terms') && $req->terms == 'admin'){
            $validated = $req->validate([
                'name' => 'required',
                'phone' => 'required|unique:affiliates',
                'email' => 'required|email|unique:affiliates,email',
                'type' => 'required',
                'country' => 'required',
                'city' => 'required',
                'password' => 'required',
            ]);
        }else{
            $validated = $req->validate([
                'name' => 'required',
                'phone' => 'required|unique:affiliates',
                'email' => 'required|email|unique:affiliates,email',
                'type' => 'required',
                'country' => 'required',
                'city' => 'required',
                'password' => 'required',
                'terms' => 'required|accepted',
                'g-recaptcha-response' => 'required|captcha'
            ]);
        }



        $validated['password'] = Hash::make($validated['password']);
        try {
            DB::beginTransaction();


            Affiliates::create($validated);

            $user =  User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'wallet' => 0,
                    'user_type' => 'Affiliate',
                    'password' => $validated['password'],
                    'referral' => $this->get_referral(),
                    'referral_code' => $req['referral'],
                    'terms_accepted_at' => now(),
                    'local_time' => $req['local_time']
                ]
            );
            // if($req['referral'] != null){
            //     $find_referral = User::where('referral','=',$req['referral'])->first();
            // }
            $activity = new Activities();
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "New Affiliate Added";
            $activity->activity_detail = "New Affiliate " . $user->name . " registered at " . $req['local_time'];
            $activity->activity_icon = "user.png";
            $activity->local_time = $req['local_time'];
            $activity->save();
            // if(isset($find_referral)){
            //     $wallet = $find_referral->wallet;
            //     $find_referral->wallet = $wallet + 10;
            //     $find_referral->save();

            //     $save_referral = new Referrals();
            //     $save_referral->referral_code = $req['referral'];
            //     $save_referral->userid = $user->id;
            //     $save_referral->user_name = $user->name;
            //     $save_referral->total_amount = 10;
            //     $save_referral->amount_added = 10;
            //     $save_referral->previous_balance = $wallet;
            //     $save_referral->wallet_balance = $wallet + 10;
            //     $save_referral->save();

            //     $use_referral = new Used_referrals();
            //     $use_referral->referral_code = $req['referral'];
            //     $use_referral->user_id = $user->id;
            //     $use_referral->commission_earnt = 10;
            //     $use_referral->save();

            //     $affiliate_commission = AffiliateCommissionEarnt::where('referral_code',$req['referral'])->first();
            //     if($affiliate_commission)
            //     {

            //         $affiliate_commission->total_earned += 10;
            //         $affiliate_commission->save();
            //     }
            //     else
            //     {

            //         $use_referral = new AffiliateCommissionEarnt();
            //         $use_referral->referral_code = $req['referral'];
            //         $use_referral->total_earned = 10;
            //         $use_referral->save();
            //     }
            // }

            DB::commit();
            if ($req->has('url_model')) {
                return redirect()->route('affiliates')->with('msg', 'Affiliate Added Successfuly');
            }
            return redirect()->route('/')->with('success', 'Submitted Successfuly');
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->route('/')->with('msg', $th->getMessage());
        }
    }
    public function Affiliates_ceateLogin()
    {
        return view('web.affiliate_login');
    }
    public function Affiliates_storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $loginDetails = $request->except(['_token', 'g-recaptcha-response', 'local_time']);
        $affiliates = Affiliates::where('email', $credentials['email'])->first();
        if(isset($affiliates)){
            if ($affiliates->status == 1) {
                if (Auth::guard('affiliates')->attempt($loginDetails)) {
                    $request->session()->regenerate();
                    return redirect()->route('affiliate.dashboard_affiliate');
                }
            } else {
                return back()->withErrors([
                    'email' => 'Your account is still not activated.',
                ]);
            }
        }else{
            return back()->withErrors([
                'email' => 'Login Attempt by a non-affiliate user',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard_affiliate()
    {
        $affiliateUser = Auth::guard('affiliates')->user();

        // $report_roles['read_only'] = 1;
        // $report_roles['read_write_only'] = 1;
        $page = "dashboard";

        $user = User::where('email', $affiliateUser->email)->first();

        if ($user->referral) {
            $referrals = Referrals::where('referral_code', '=', $user->referral);
            $comission_earned = $referrals->sum('amount_added');
            $total_referrals = $referrals->count();
        } else {

            $comission_earned = 0;
            $total_referrals = 0;
            $total_referrals  = 0;
        }
        $users  =  User::where('user_type', '=', 'Subscriber')->where('referral_code', $user->referral);
        $subscribers =  $users->get();
        $price_plans = Membership::get();
        $total_subscribers = array();
        $grouped_data = [];
        foreach ($price_plans as $plan) {
            $categ = $plan->plan_name;
            $categ_app = 0;
            foreach ($subscribers as $subs) {
                if ($categ == $subs->membership) {
                    $categ_app += 1;

                }



            }
            $total_subscribers[$categ] = $categ_app;
        }
        foreach ($subscribers as $subs) {
            // Parse the membership start and end dates
            $start_date = \Carbon\Carbon::parse($subs->membership_start_date);
            $end_date = \Carbon\Carbon::parse($subs->membership_expiry_date);

            // Calculate the membership duration in years based on start and end dates
            $membership_duration = $start_date->diffInYears($end_date); // Difference in years between start and end date

            // Initialize the grouped data for the duration if not set
            if (!isset($grouped_data[$membership_duration])) {
                $grouped_data[$membership_duration] = [];
            }

            // Initialize the membership category within the duration if not set
            if (!isset($grouped_data[$membership_duration][$subs->membership])) {
                $grouped_data[$membership_duration][$subs->membership] = [
                    'total_users' => 0,
                ];
            }

            // Increment the total users for this membership and duration
            $grouped_data[$membership_duration][$subs->membership]['total_users']++;
        }

        // Debugging output to check the structure of the grouped data

        $grouped_data_country = $users->whereNotNull('country')->get()->groupBy('country');
        // Initialize the final result array
        $final_grouped_data = [];

        foreach ($grouped_data_country as $country => $country_subscribers) {
            // Group the subscribers by membership type within each country
            $grouped_by_membership = $country_subscribers->groupBy('membership');

            foreach ($grouped_by_membership as $membership => $membership_subscribers) {
                // Store the total user count for each membership type within the country
                $final_grouped_data[$country][$membership] = [
                    'total_users' => $membership_subscribers->count(),
                ];
            }
        }
        $grouped_data_subcategory = $users->whereNotNull('sub_category')->get()->groupBy('sub_category');

        // Initialize the final result array
        $final_grouped_data_subcategory = [];

        foreach ($grouped_data_subcategory as $sub_category => $subcategory_users) {
            // Store the total user count for each subcategory
            $final_grouped_data_subcategory[$sub_category] = [
                'total_users' => $subcategory_users->count(),
            ];
        }


        if (request()->ajax()) {
            $user = User::where('user_type', '=', 'Subscriber')->where('referral_code', $user->referral);


            $subscribers_byPlan = $user->clone()->select('membership', DB::raw('count(id) as total'))
                ->groupBy('membership')
                ->get();
            $subscribers_byPlan_labels = $subscribers_byPlan->pluck('membership')->toArray(); // Membership types
            $subscribers_byPlan_data = $subscribers_byPlan->pluck('total')->toArray();


            $subscriberPlanDuration = $user->clone()->select(
                DB::raw('TIMESTAMPDIFF(YEAR, membership_start_date, membership_expiry_date) AS duration'),
                DB::raw('COUNT(*) AS total_subscribers')
            )->groupBy('duration')->get();
            $subscriberPlanDuration_labels = $subscriberPlanDuration->pluck('duration')->toArray(); // Membership types
            $subscriberPlanDuration_data = $subscriberPlanDuration->pluck('total_subscribers')->toArray();



            $subscriberCountry = $user->clone()->whereNotNull('country')->select('country', DB::raw('COUNT(users.id) as No_of_Subscribers'))
                ->groupBy('country')->get();

            $subscriberCountry_labels = $subscriberCountry->pluck('country')->toArray(); // Membership types
            $subscriberCountry_data = $subscriberCountry->pluck('No_of_Subscribers')->toArray();



            // $subscriberAgeGroup = $user->clone()->select(
            //     DB::raw("
            //         CASE
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-35'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-45'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
            //             ELSE 'Over 55'
            //         END AS age_group"),
            //     DB::raw('COUNT(*) AS count')
            // )->groupBy('age_group')->get();
            // $subscriberAgeGroup_labels = $subscriberAgeGroup->pluck('age_group')->toArray(); // Membership types
            // $subscriberAgeGroup_data = $subscriberAgeGroup->pluck('count')->toArray();


            $subscriberAgeGroup_bySubcategory= $user->clone()->select('sub_category', DB::raw('count(id) as total'))
            ->groupBy('sub_category')->get(); // Membership types
            $subscriberAgeGroup_labels = $subscriberAgeGroup_bySubcategory->pluck('total')->toArray();
            // Membership types
            $subscriberAgeGroup_data= $subscriberAgeGroup_bySubcategory->pluck('sub_category')->toArray();


            return response()->json(['subscribers_byPlan_result' => array($subscribers_byPlan_labels, $subscribers_byPlan_data, $subscriberPlanDuration_labels, $subscriberPlanDuration_data, $subscriberCountry_labels, $subscriberCountry_data, $subscriberAgeGroup_labels, $subscriberAgeGroup_data)]);
        }


        $wallet = ($user->referral) ?  Referrals::where('referral_code', $user->referral)->latest()->first() : '';
        if (!$wallet) {
            $wallet = 0;
        } else {
            $wallet = $user->wallet;
        }

        return view('affiliate.dashboard_affiliate', compact('final_grouped_data_subcategory','final_grouped_data','grouped_data','total_subscribers','total_referrals', 'comission_earned', 'affiliateUser', 'user', 'page', 'wallet'));
    }
    public function Affiliates_forget_create()
    {
        return view('web.affiliate_forget');
    }
    public function get_referral()
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $ref = "";
        for ($i = 0; $i < 8; $i++) {
            $ref = $ref . $ch[rand(0, strlen($ch) - 1)];
        }
        $referal = User::where('referral', '=', $ref)->first();
        if ($referal) {
            $this->get_referral();
        } else {
            return $ref;
        }
    }
    public function add_service(Request $request){
        // Validate the incoming request
        $validated = $request->validate([
            'service_name' => 'required|string|max:255', // Service name is required, should be a string and max 255 characters
            'fees' => 'nullable|numeric|min:0', // Fees are optional, should be a number and at least 0
        ]);
        if(!empty($request->input('id'))){
            $service = Services::find($request->input('id'));

            // Check if the service exists
            if (!$service) {
                return response()->json(['error' => 'Service not found'], 404);
            }
            if($request->has('service_name') &&  $request->has('fees') ){
                $service->service_name = $request->input('service_name');
                $service->fees = $request->input('fees');
            }
             if($request->has('status')){
                $service->status = ($service->status == true) ? false:true; // Update the status field
             }

            $service->save(); // Save the changes
            $message ='Service update successfully.';
        }else{
            $user = auth()->user();
            // If validation passes, store the service
            $service = new Services();
            $service->service_name = $request->input('service_name');
            $service->fees = $request->input('fees');
            $service->subscriber_id = empty($user->added_by) ? $user->id : $user->added_by;
            $service->user_id = $user->id;
            $service->status = true; // Default status is true if not provided
            $service->save();
            $message ='Service created successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,

        ]);
        // return redirect()->route('my_settings')->with('success_services',$message );
    }



    public function get_subscriber_service(){

           $user = auth()->user();
        if ($user->user_type == "Subscriber") {
            $services = Services::where('subscriber_id',$user->id)->orderBy('created_at','desc')->get();
        } else {
            $services = Services::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        }
        return DataTables::of($services)
        ->addIndexColumn()
        ->editColumn('subscriber', function ($row) {
            return $row->subscriber ? $row->subscriber->name . '(' . $row->subscriber_id . ')' : 'N/A';
        })
        ->editColumn('user', function ($row) {
            return $row->user ? $row->user->name . '(' . $row->user_id . ')' : 'N/A';
        })
        ->editColumn('status', function ($row) {
            return $row->status
                ? '<a style="background:green;border-color:green;" href="#" onclick="userstatus(' . $row->id . ')" class="p-0 px-1">Active</a>'
                : '<a style="background:red;border-color:red;" href="#" onclick="userstatus(' . $row->id . ')" class="p-0 px-1">Inactive</a>';
        })
        ->addColumn('action', function ($row) {
            // $deleteUrl = route('services_delete', $row->id); // Define delete route

            return '
                <a href="javascript:void(0)"
                   class="editService"
                   data-id="' . $row->id . '"
                   data-name="' . $row->service_name . '"
                   data-fee="' . $row->fees . '"
                   style="text-decoration:none; background:none; border:none">
                    <i class="fa-solid fa-edit btn p-1 text-primary" style="font-size:14px;"></i>
                </a>
               <a href="javascript:void(0)"
                    onclick="deleteService('.$row->id .')"
                    style="text-decoration:none; background:none; border:none">
                        <i class="fa-solid fa-trash btn p-1 text-danger" style="font-size:14px;"></i>
                    </a>';
        })
        ->rawColumns(['status', 'action'])
        ->make(true);

    }
    public function services_delete($id){
        $service = Services::findOrFail($id);
       $service->delete();
     return response()->json(['message' => 'Service deleted successfully!']);

    }
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string',
            'rating' => 'required|string',
        ]);
    $user = auth()->user();
        Feedbacks::create([
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'subscriber_id' => empty($user->added_by) ? $user->id : $user->added_by,
            'user_id' => $user->id
        ]);


        return response()->json(['message' => 'Your feedback received successfully.']);
    }

public function showFeedbackPopup()
{
     // Get the current date and subtract 3 months
     $threeMonthsAgo = Carbon::now()->subMonths(3);

     // Get the most recent feedback submitted by the user
     $feedback = Feedbacks::where('user_id', auth()->id())
                     ->latest() // To get the most recent feedback
                     ->first();
         if (empty($feedback)) {
            return response()->json(['show_popup' => true]);
         }
     // If the user hasn't submitted feedback in the last 3 months

     if ($feedback || Carbon::parse($feedback->created_at)->lte($threeMonthsAgo)) {
         // Check if the user is due for a reminder (based on last reminder date or never reminded)
         $user = auth()->user();
         $lastReminder =   $feedback->created_at; // Assuming this column exists

         // If last reminder is older than 3 months or never reminded
         $threeMonthsAgoReminder = Carbon::now()->subMonths(3);

         if (!$lastReminder || Carbon::parse($lastReminder)->lte($threeMonthsAgoReminder)) {
             // You can now show the popup
             return response()->json(['show_popup' => true]);
         }
     }

     // If feedback was added today or within the last 3 months, don't send a reminder
     if ($feedback && Carbon::parse($feedback->created_at)->gt(Carbon::now()->subMonths(3))) {
         return response()->json(['show_popup' => false]);
     }

     // If no feedback needed and no reminders required, return false (no popup)
     return response()->json(['show_popup' => false]);
    }

    /*Newly added code by Meenakshi Nanta*/
    public function enquiries()
    {
        $user = $this->check_login();

        if ($user->user_type != "admin" && (new DateTime($user->membership_expiry_date)) < (new DateTime("now"))) {
            return redirect()->route('membership')->with('membership_expiry', 'Membership has expired.');
        }

        $this->set_timezone();

        if ($user->user_type == "Subscriber") {

            $enquiries = VisaEnquiry::where('subscriber_id',$user->id)
                        ->orderBy('created_at','desc')
                        ->get();

        } else {

            $subscriber = User::find($user->added_by);

            if ((new DateTime($subscriber->membership_expiry_date)) < (new DateTime("now"))) {
                return redirect()->route('membership')->with('membership_expiry', 'Membership has expired.');
            }

            $enquiries = VisaEnquiry::where('subscriber_id',$subscriber->id)
                        ->orderBy('created_at','desc')
                        ->get();
        }

        $page = "enquiries";

        return view('web.enquiries',compact('user','enquiries','page'));
    }

    public function convertEnquiryClient(Request $request){
        $user = Auth::user();
        $this->set_timezone();

        try {

            DB::beginTransaction();

            $enquiry = VisaEnquiry::find($request->enquiry_id);

            if (!$enquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enquiry not found.'
                ]);
            }

            /* Determine subscriber */
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
            } else {
                $subscriber = User::find($user->added_by);
            }

            /* Create Client */
            $client = new Clients();
            $client->subscriber_id = $subscriber->id;
            $client->user_id = $user->id;

            $client->name = $enquiry->full_name;
            $client->phone = $enquiry->contact_no;
            $client->email = $enquiry->email;

            $client->alternate_no = null;

            $client->nationality = $enquiry->nationality ?? null;
            $client->passport_no = $enquiry->passport_no ?? null;
            $client->dob = $enquiry->dob ?? null;

            $client->address = $enquiry->address;
            $client->country = $enquiry->country_pref_1;
            $client->state = $enquiry->state ?? null;
            $client->city = $enquiry->city ?? null;
            $client->pincode = $enquiry->pincode ?? null;

            $client->save();

            /* Update enquiry status */
            $enquiry->status = 1;
            $enquiry->save();

            /* Activity Log */
            $activity = new Activities();
            $activity->subscriber_id = $subscriber->id;
            $activity->user_id = $user->id;
            $activity->user_name = $user->name;
            $activity->activity_name = "Enquiry Converted To Client";

            if ($user->user_type == "Subscriber") {
                $activity->activity_detail = "Enquiry ".$enquiry->full_name." converted to client by ".$user->name;
            } else {
                $activity->activity_detail = "Enquiry ".$enquiry->full_name." converted to client by ".$user->name." (".$subscriber->name.")";
            }

            $activity->activity_icon = "user.png";
            $activity->local_time = now();
            $activity->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Enquiry successfully converted to client.'
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function deleteEnquiry($id)
    {
        $enquiry = VisaEnquiry::find($id);

        if(!$enquiry){
            return response()->json([
                'success' => false,
                'message' => 'Enquiry not found'
            ]);
        }

        $enquiry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Enquiry deleted successfully'
        ]);
    }

    public function viewEnquiry($id)
    {
        $enquiry = VisaEnquiry::find($id);

        if(!$enquiry){
            return redirect()->back()->with('error','Enquiry not found');
        }
        $user = $this->check_login();
        $page = "visa-enquiries";
        return view('web.view_enquiries', compact('user','enquiry','page'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'remarks' => 'nullable|string|max:500',
            'send_via' => 'required|in:email,sms,both'
        ]);

        $user = Auth::user();
        $client = Clients::findOrFail($request->client_id);

        $clientEmail = $request->filled('client_email') ? $request->client_email : $client->email;
        $clientPhone = $request->filled('client_phone') ? $request->client_phone : $client->phone;

        if (in_array($request->send_via, ['email', 'both']) && empty($clientEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Client email is required for email notifications.'
            ], 422);
        }

        if (in_array($request->send_via, ['sms', 'both']) && empty($clientPhone)) {
            return response()->json([
                'success' => false,
                'message' => 'Client phone number is required for SMS notifications.'
            ], 422);
        }

        $appointment = new Appointment();
        $appointment->client_id = $request->client_id;
        $appointment->subscriber_id = empty($user->added_by) ? $user->id : $user->added_by;
        $appointment->user_id = Auth::id();
        $appointment->appointment_date = $request->appointment_date;
        $appointment->appointment_time = $request->appointment_time;
        $appointment->remarks = $request->remarks;
        $appointment->send_via = $request->send_via;
        $appointment->calendly_link = null;
        $appointment->calendly_event_uri = null;
        $appointment->save();

        $responseLinks = $this->createAppointmentResponseLinks($appointment, $clientEmail);
        $appointment->calendly_link = $responseLinks['accept_url'];
        $appointment->save();

        $appointment->setAttribute('accept_url', $responseLinks['accept_url']);
        $appointment->setAttribute('decline_url', $responseLinks['decline_url']);

        if (in_array($request->send_via, ['email', 'both'])) {
            Mail::to($clientEmail)->send(new AppointmentSchedulerMail($appointment, $client, $user));
        }

        $smsStatus = ['sent' => false, 'message' => null];
        if (in_array($request->send_via, ['sms', 'both'])) {
            $smsStatus = $this->sendAppointmentSms($clientPhone, $client->name, $user->name, $responseLinks['accept_url'], $responseLinks['decline_url'], $request->remarks, $request->appointment_date, $request->appointment_time, $user->timezone ?? config('app.timezone'));
        }

        return response()->json([
            'success' => true,
            'message' => $smsStatus['message'] ?: 'Appointment invitation sent successfully.',
            'calendly_link' => $responseLinks['accept_url'],
        ]);
    }

    private function createAppointmentResponseLinks(Appointment $appointment, ?string $clientEmail): array
    {
        $expiresAt = Carbon::parse($appointment->appointment_date.' '.$appointment->appointment_time, config('app.timezone'))
            ->addDay();

        $routeParams = ['appointment' => $appointment->id];
        if (!empty($clientEmail)) {
            $routeParams['email'] = $clientEmail;
        }

        return [
            'accept_url' => URL::temporarySignedRoute('appointment.respond', $expiresAt, array_merge($routeParams, ['action' => 'accept'])),
            'decline_url' => URL::temporarySignedRoute('appointment.respond', $expiresAt, array_merge($routeParams, ['action' => 'decline'])),
        ];
    }

    private function createCalendlySchedulingLinkForAppointment(
        Appointment $appointment,
        ?string $clientEmail,
        User $sender
    ): array {
        // ✅ Guard: check status first, before any API calls
        if ($appointment->status === 'completed') {
            return ['success' => false, 'message' => 'Appointment already completed.'];
        }

        // ✅ Define $token before using it
        $token = config('services.calendly.pat');
        if (empty($token)) {
            return ['success' => false, 'message' => 'Calendly PAT not configured.'];
        }

        $baseUrl = rtrim(config('services.calendly.base_url', 'https://api.calendly.com'), '/');

        $startDateTime = Carbon::parse(
            $appointment->appointment_date . ' ' . $appointment->appointment_time,
            $sender->timezone ?? config('app.timezone')
        );

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ];

        // ✅ try block is now correctly INSIDE the function
        try {
            $meResponse = Http::withHeaders($headers)->get($baseUrl . '/users/me');
            if (!$meResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'Unable to connect with Calendly. Please verify your Calendly token.',
                ];
            }

            $ownerUri = $meResponse->json('resource.uri');
            if (!$ownerUri) {
                return [
                    'success' => false,
                    'message' => 'Calendly user details are incomplete. Please reconnect Calendly.',
                ];
            }

            $eventPayload = [
                'name'     => substr('Confirmed Appointment with ' . $sender->name, 0, 55),
                'host'     => $ownerUri,
                'duration' => 30,
                'timezone' => $sender->timezone ?? config('app.timezone'),
                'date_setting' => [
                    'type'       => 'date_range',
                    'start_date' => $startDateTime->toDateString(),
                    'end_date'   => $startDateTime->toDateString(),
                ],
                'location' => ['kind' => 'zoom_conference'],
            ];

            $oneOffResponse = Http::withHeaders($headers)
                ->post($baseUrl . '/one_off_event_types', $eventPayload);

            if (!$oneOffResponse->successful()) {
                Log::error('Calendly one_off_event_types failed', [
                    'response' => $oneOffResponse->json(),
                ]);
                return [
                    'success' => false,
                    'message' => 'Unable to create a Calendly confirmation link right now.',
                ];
            }

            $eventTypeUri = $oneOffResponse->json('resource.uri');

            $linkPayload = [
                'owner'           => $eventTypeUri,
                'owner_type'      => 'EventType',
                'max_event_count' => 1,
            ];

            if (!empty($clientEmail)) {
                $linkPayload['invitee_email'] = $clientEmail;
            }

            $schedulingLinkResponse = Http::withHeaders($headers)
                ->post($baseUrl . '/scheduling_links', $linkPayload);

            if (!$schedulingLinkResponse->successful()) {
                Log::error('Calendly scheduling_links failed', [
                    'response' => $schedulingLinkResponse->json(),
                ]);
                return [
                    'success' => false,
                    'message' => 'Unable to generate a Calendly confirmation link right now.',
                ];
            }

            return [
                'success'        => true,
                'booking_url'    => $schedulingLinkResponse->json('resource.booking_url'),
                'event_type_uri' => $eventTypeUri,
            ];

        } catch (\Throwable $e) {
            Log::error('Calendly appointment acceptance error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Unable to prepare Calendly confirmation at the moment.',
            ];
        }
    }

    public function respondToAppointment(Request $request, Appointment $appointment, string $action)
    {
        if (!in_array($action, ['accept', 'decline'])) {
            abort(404);
        }

        if ($appointment->status === 'completed') {
            return view('web.appointment_response', [
                'title' => 'Appointment Already Completed',
                'subtitle' => 'This appointment is already marked as completed.',
                'status' => 'neutral',
                'calendlyUrl' => null,
            ]);
        }

        $client = Clients::find($appointment->client_id);
        $email = $request->query('email');
        if (!empty($email) && strcasecmp($client?->email ?? '', $email) !== 0) {
            abort(403);
        }

        if ($action === 'accept') {
            $appointment->status = 'accepted';
            $appointment->save();

            $sender = User::find($appointment->user_id);
            $calendlyUrl = null;

            if ($sender) {
                $calendly = $this->createCalendlySchedulingLinkForAppointment($appointment, $client?->email, $sender);
                if ($calendly['success']) {
                    $appointment->calendly_link = $calendly['booking_url'];
                    $appointment->calendly_event_uri = $calendly['event_type_uri'];
                    $appointment->save();
                    $calendlyUrl = $calendly['booking_url'];
                }
            }

            return view('web.appointment_response', [
                'title' => 'Thank You! Appointment Accepted',
                'subtitle' => 'Your response has been recorded successfully.',
                'status' => 'accepted',
                'calendlyUrl' => $calendlyUrl,
            ]);
        }

        $appointment->status = 'canceled';
        $appointment->save();

        return view('web.appointment_response', [
            'title' => 'Appointment Declined',
            'subtitle' => 'You have declined this appointment. The sender has been updated.',
            'status' => 'declined',
            'calendlyUrl' => null,
        ]);
    }

    private function sendAppointmentSms(string $phone, string $clientName, string $senderName, string $acceptLink, string $declineLink, ?string $remarks, string $appointmentDate, string $appointmentTime, string $timezone): array
    {
        $message = "Dear {$clientName},\n\n".
            "{$senderName} has invited you for an appointment on {$appointmentDate} at {$appointmentTime} ({$timezone}).\n\n".
            "Accept: {$acceptLink}\n".
            "Decline: {$declineLink}\n\n".
            (!empty($remarks) ? "Meeting purpose: {$remarks}\n\n" : '').
            "Please confirm by accepting or declining the appointment using the links above.\n\n".
            "Best regards,\nAdwiseri Team";

        $smsUrl = config('services.sms_gateway.url');
        $smsToken = config('services.sms_gateway.token');

        if (function_exists('send_sms')) {
            try {
                $existingResult = send_sms($phone, $message);

                if ($existingResult === false) {
                    return [
                        'sent' => false,
                        'message' => 'Appointment link created successfully, but SMS could not be delivered.',
                    ];
                }

                return ['sent' => true, 'message' => null];
            } catch (\Throwable $e) {
                Log::warning('Existing SMS integration exception', ['error' => $e->getMessage()]);
            }
        }

        if (empty($smsUrl)) {
            return [
                'sent' => false,
                'message' => 'Appointment link created successfully. Email sent. SMS gateway is not configured.',
            ];
        }

        try {
            $response = Http::withToken($smsToken)->post($smsUrl, [
                'phone' => $phone,
                'message' => $message,
            ]);

            if (!$response->successful()) {
                Log::warning('SMS gateway request failed', ['response' => $response->body()]);

                return [
                    'sent' => false,
                    'message' => 'Appointment link created successfully, but SMS could not be delivered.',
                ];
            }

            return ['sent' => true, 'message' => null];
        } catch (\Throwable $e) {
            Log::warning('SMS gateway exception', ['error' => $e->getMessage()]);

            return [
                'sent' => false,
                'message' => 'Appointment link created successfully, but SMS could not be delivered.',
            ];
        }
    }


    public function saveEmailTemplate(Request $request, EmailTemplateService $emailTemplateService)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'template_key' => 'required|string|max:100',
            'template_name' => 'required|string|max:191',
            'custom_name' => 'nullable|string|max:191',
            'subject' => 'nullable|string|max:191',
            'body' => 'nullable|string',
        ]);

        $validated['audience'] = strtolower($user->user_type) === 'admin' ? 'admin' : 'subscriber';

        $emailTemplateService->saveTemplate($user, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Email template saved successfully',
        ]);
    }

    public function getEmailTemplates(EmailTemplateService $emailTemplateService)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $audience = strtolower($user->user_type) === 'admin' ? 'admin' : 'subscriber';
        $templates = $emailTemplateService->getTemplatesForSettings($user);
        $rows = $templates[$audience]->map(function ($template) {
            return [
                'audience' => $template->audience,
                'template_key' => $template->template_key,
                'template_name' => $template->template_name,
                'custom_name' => $template->custom_name,
                'subject' => $template->subject,
                'body' => $template->body,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'audience' => $audience,
            'templates' => $rows,
        ]);
    }


    public function savePaymentReminderSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'client_group' => 'required|in:all,over_500,over_100',
            'email_frequency' => 'required|in:weekly,monthly,quarterly',
            'email_to' => 'required|in:client_only,client_bcc_subscriber',
        ]);

        PaymentReminderSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'client_group' => $validated['client_group'],
                'email_frequency' => $validated['email_frequency'],
                'email_to' => $validated['email_to'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment reminder settings saved successfully',
        ]);
    }

    public function saveReportSettings(Request $request)
    {
        try {
            $user = Auth::user();

            $allowedModules = [
                'clients',
                'applications',
                'invoices',
                'payments',
                'referrals',
                'wallets',
            ];

            if ($user && strtolower($user->user_type) === 'admin') {
                $allowedModules[] = 'subscribers';
                $allowedModules[] = 'affiliates';
            }

            $request->validate([
                'modules' => 'required|array|min:1',
                'modules.*' => 'in:'.implode(',', $allowedModules),
                'frequency' => 'required|in:daily,weekly,monthly,quarterly',
                'delivery_mode' => 'required|in:attachment,link',
                'emails' => ['nullable', 'string', 'max:1000', 'regex:/^\s*[^,\s]+@[^,\s]+(\s*,\s*[^,\s]+@[^,\s]+){0,4}\s*$/']
            ]);

            $setting = ReportSetting::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'modules' => $request->modules,
                    'frequency' => $request->frequency,
                    'delivery_mode' => $request->delivery_mode,
                    'emails' => $request->emails
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Report settings saved successfully! Reports will be sent according to the selected frequency schedule.',
                'data' => $setting
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while saving report settings.',
                'error' => $e
            ], 500);

        }
    }


    public function downloadScheduledReport(Request $request, $file)
    {
        $safeFile = basename($file);
        $filePath = storage_path('app/reports/' . $safeFile);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }

    public function createLead($id)
    {
        $subscriberId = decrypt($id);

        return view('web.create_lead',compact('subscriberId'));
    }
}
