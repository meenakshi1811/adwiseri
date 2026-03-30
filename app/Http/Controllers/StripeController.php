<?php

namespace App\Http\Controllers;

use Session;
use Auth;
use Mail;
use Stripe;
use DateTime;
use DB;
use Hash;
use App;
use Excel;
use Cookie;
use Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Membership;
use App\Models\Invoices;
use App\Models\Internal_Invoices;
use App\Models\Activities;
use App\Models\MyTimezones;
use App\Models\Invoice_settings;
use App\Models\Used_referrals;
use App\Models\Referrals;
use App\Models\UserRoles;
use App\Models\AffiliateCommissionEarnt;
use App\Models\PaymentARs;

use App\Mail\EmailVerification;
use App\Mail\WelcomeMail;
use App\Mail\PlanSubscriptionMail;

use function App\Helpers\commission_amount;

class StripeController extends Controller
{
    private function generateInternalInvoiceNo(): string
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id = "";
        for ($i = 0; $i < 10; $i++) {
            $id .= $ch[rand(0, strlen($ch) - 1)];
        }

        if (Internal_Invoices::where('invoice_no', '=', $id)->exists()) {
            return $this->generateInternalInvoiceNo();
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

    private function createAdminApInvoiceAndPayment(User $subscriber, User $company, float $amount, string $paymentMode, string $detail = 'Subscription Fees'): Internal_Invoices
    {
        $amount = round(max(0, $amount), 2);

        $internalInvoice = new Internal_Invoices();
        $internalInvoice->invoice_no = $this->generateInternalInvoiceNo();
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
        $internalInvoice->to_name = $subscriber->name;
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
        $internalInvoice->status = "Paid";
        $internalInvoice->type = 'ap';
        $internalInvoice->due_date = date("Y-m-d");
        $internalInvoice->token = $this->generateInternalInvoiceToken();
        $internalInvoice->save();

        PaymentARs::create([
            'subscriber_id' => $subscriber->id,
            'invoice_no' => $internalInvoice->invoice_no,
            'service_provider' => $company->organization ?: 'adwiseri.com',
            'service_taken' => $detail,
            'amount' => $amount,
            'paid_amount' => $amount,
            'payment_mode' => $paymentMode,
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

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {

        $user = Auth::user();
        $data = session('pay_data');
        $amount = $data['plan_price'];
        $error = array();
        try{
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from adwiseri.com."
        ]);
        }
        catch(Stripe_CardError $e) {
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $error = $e->getMessage();
        return back()->with('errors', $error);
        }

        Session::flash('success', 'Payment successful!');

        $duration = $data['plan_duration'];
        if((new DateTime($user->membership_expiry_date)) < (new DateTime("now"))){
            $expired = "expired";
            $wallet_amount = 0;
            $discount = 0;
        }
        else{
            $wallet_amount = $user->wallet;
            $discount = $wallet_amount;
        }
        $user->membership = $data['plan_name'];
        $user->membership_type = "Subscription";
        $user->membership_start_date = new DateTime("now");
        $user->membership_expiry_date = (new DateTime("now"))->modify("+".$duration." years");
        $user->wallet = 0;
        $user->save();
        $my_users = User::where('added_by','=',$user->id)->get();
        foreach($my_users as $myuser){
            $myuser->membership = $user->membership;
            $myuser->membership_type = $user->membership_type;
            $myuser->membership_start_date = $user->membership_start_date;
            $myuser->membership_expiry_date = $user->membership_expiry_date;
            $myuser->wallet = 0;
            $myuser->save();
        }
        // if(isset($referral)){
        //     $amt = $referral->wallet;
        //     $referral->wallet = $amt + ($data['plan_price'] * 0.2);
        //     $referral->save();
        //     $save_referral = new Referrals();
        //     $save_referral->referral_code = $data['referral_code'];
        //     $save_referral->userid = $user->id;
        //     $save_referral->user_name = $user->name;
        //     $save_referral->total_amount = $data['plan_price'];
        //     $save_referral->amount_added = $data['plan_price'] * 0.2;
        //     $save_referral->previous_balance = $amt;
        //     $save_referral->wallet_balance = $amt + ($data['plan_price'] * 0.2);
        //     $save_referral->save();
        // }
        $activity = new Activities();
        $activity->subscriber_id = $user->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "Subscription Updated";
        $activity->activity_detail = $user->name." updates account subscription at ".$data['local_time'];
        $activity->activity_icon = "mmbrcp.png";
        $activity->local_time = $data['local_time'];
        $activity->save();
        $plan = Membership::where('plan_name','=',$data['plan_name'])->first();
        $service_fee = $data['plan_amount'];
        // $discount = 0;
        // $tax = ($service_fee + $discount) * (18/100);
        $tax = 0;
        $company = User::where('user_type','=','admin')->first();
        $chargedAmount = $service_fee - $discount + $tax;
        $internalInvoice = $this->createAdminApInvoiceAndPayment($user, $company, $chargedAmount, "Card", "Subscription Fees");
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
        $invoice->to_country =$user->country;
        $invoice->to_pincode = $user->pincode;
        $invoice->to_phone = $user->phone;
        $invoice->to_email = $user->email;
        $invoice->service_fee = $service_fee;
        $invoice->discount = $discount;
        $invoice->tax = $tax;
        $invoice->payment_mode = "Card";
        $invoice->total = $chargedAmount;
        $invoice->save();
        $activity = new Activities();
        $activity->subscriber_id = $user->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "Invoice Generated";
        $activity->activity_detail = "Invoice generated for subscription update for user ".$user->name." at ".$data['local_time'];
        $activity->activity_icon = "invoice.jpg";
        $activity->local_time = $data['local_time'];
        $activity->save();
        Mail::to($user->email)->send(new PlanSubscriptionMail(
            $user->name,
            $plan['membership'],
            $plan['validity'],
            'Your Subscription Plan Has Been Updated',
            $this->buildInvoicePdfData($internalInvoice, $user, $company)
        ));
        session()->forget('pay_data');
        return redirect()->route('user_membership')->with('payment_success','Payment completed successfully.');

        // return back();
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function regstripePost(Request $request)
    {

        $data = session('reg_data');
        $plan = $data['membership'];
        $duration = $data['duration'];
        $membership = Membership::where('plan_name','=',$plan)->first();
        $plan_amt = $membership->price_per_year;
        if($duration == 1){
            $plan_amount = round(($plan_amt * 1) * 1);
        }
        if($duration == 2){
            $plan_amount = round(($plan_amt * 2) * 0.9);
        }
        if($duration == 3){
            $plan_amount = round(($plan_amt * 3) * 0.8);
        }
        if($duration == 5){
            $plan_amount = round(($plan_amt * 5) * 0.5);
        }
        $amount = $plan_amount;
        try{
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from adwiseri.com."
        ]);
        }
        catch(Stripe_CardError $e) {
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $error = $e->getMessage();
        return back()->with('errors', $error);
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $error = $e->getMessage();
        return back()->with('errors', $error);
        }

        Session::flash('success', 'Payment successful!');

        function get_referral(){
            $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $ref = "";
            for($i=0; $i<8; $i++){
                $ref = $ref.$ch[rand(0, strlen($ch)-1)];
            }
            $referal = User::where('referral','=',$ref)->first();
            if($referal){
                get_referral();
            }
            else{
                return $ref;
            }
        }
        if($data['referral'] != null){
            $find_referral = User::where('referral','=',$data['referral'])->first();
        }
        $user = new User();
        $eotp = rand(10000, 99999);
        $potp = rand(10000, 99999);
        $user->user_type = "Subscriber";
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->category = $data['category'];
        $user->sub_category = $data['subcategory'];
        $user->other_subcategory = $data['other'];
        // $data->membership = $data['membership'];
        $plan = Membership::where('plan_name','=',$data['membership'])->first();
        $user->membership = $plan->plan_name;
        $user->membership_type = "Paid";
        // if($data->membership == "Free"){
        // }
        // else{
        //     $data->membership_type = "Trial";
        //     $data->membership_expiry_date = (new DateTime("now"))->modify("+30 days");
        // }
        $enddate = (new DateTime("now"))->modify("+".$duration." Years");
        $user->membership_start_date = new DateTime("now");
        $user->membership_expiry_date = $enddate;
        $user->wallet = 0;
        $user->referral = get_referral();
        $user->referral_code = $data['referral'];
        $user->email_otp = $eotp;
        $user->phone_otp = $potp;
        $user->timezone = "UTC";
        $user->status = "true";
        $user->password = Hash::make($data['password']);
        $user->save();

        $role = UserRoles::where('user_id','=',$user->id)->get();
            if($role){
                foreach($role as $r)
                {
                    $r->delete();
                }
            }
            $clients = new UserRoles();
            $clients->user_id = $user->id;
            $clients->subscriber_id = $user->added_by;
            $clients->name = $user->name;
            $clients->email = $user->email;
            $clients->module = "Clients";
            $clients->read_only = 1;
            $clients->write_only = 1;
            $clients->update_only = 1;
            $clients->delete_only = 1;
            $clients->read_write_only = 1;
            $clients->save();

            $applications = new UserRoles();
            $applications->user_id = $user->id;
            $applications->subscriber_id = $user->added_by;
            $applications->name = $user->name;
            $applications->email = $user->email;
            $applications->module = "Applications";
            $applications->read_only = 1;
            $applications->write_only = 1;
            $applications->update_only = 1;
            $applications->delete_only = 1;
            $applications->read_write_only = 1;
            $applications->save();

            $communication = new UserRoles();
            $communication->user_id = $user->id;
            $communication->subscriber_id = $user->added_by;
            $communication->name = $user->name;
            $communication->email = $user->email;
            $communication->module = "Communication";
            $communication->read_only = 1;
            $communication->write_only = 1;
            $communication->update_only = 1;
            $communication->delete_only = 1;
            $communication->read_write_only = 1;
            $communication->save();

            $invoices = new UserRoles();
            $invoices->user_id = $user->id;
            $invoices->subscriber_id = $user->added_by;
            $invoices->name = $user->name;
            $invoices->email = $user->email;
            $invoices->module = "Invoices";
            $invoices->read_only = 1;
            $invoices->write_only = 1;
            $invoices->update_only = 1;
            $invoices->delete_only = 1;
            $invoices->read_write_only = 1;
            $invoices->save();

            $payments = new UserRoles();
            $payments->user_id = $user->id;
            $payments->subscriber_id = $user->added_by;
            $payments->name = $user->name;
            $payments->email = $user->email;
            $payments->module = "Payments";
            $payments->read_only = 1;
            $payments->write_only = 1;
            $payments->update_only = 1;
            $payments->delete_only = 1;
            $payments->read_write_only = 1;
            $payments->save();

            $reports = new UserRoles();
            $reports->user_id = $user->id;
            $reports->subscriber_id = $user->added_by;
            $reports->name = $user->name;
            $reports->email = $user->email;
            $reports->module = "Reports";
            $reports->read_only = 1;
            $reports->write_only = 1;
            $reports->update_only = 1;
            $reports->delete_only = 1;
            $reports->read_write_only = 1;
            $reports->save();

            $subscription = new UserRoles();
            $subscription->user_id = $user->id;
            $subscription->subscriber_id = $user->added_by;
            $subscription->name = $user->name;
            $subscription->email = $user->email;
            $subscription->module = "Subscription";
            $subscription->read_only = 1;
            $subscription->write_only = 1;
            $subscription->update_only = 1;
            $subscription->delete_only = 1;
            $subscription->read_write_only = 1;
            $subscription->save();

            $settings = new UserRoles();
            $settings->user_id = $user->id;
            $settings->subscriber_id = $user->added_by;
            $settings->name = $user->name;
            $settings->email = $user->email;
            $settings->module = "Settings";
            $settings->read_only = 1;
            $settings->write_only = 1;
            $settings->update_only = 1;
            $settings->delete_only = 1;
            $settings->read_write_only = 1;
            $settings->save();

            $support = new UserRoles();
            $support->user_id = $user->id;
            $support->subscriber_id = $user->added_by;
            $support->name = $user->name;
            $support->email = $user->email;
            $support->module = "Support";
            $support->read_only = 1;
            $support->write_only = 1;
            $support->update_only = 1;
            $support->delete_only = 1;
            $support->read_write_only = 1;
            $support->save();


        $activity = new Activities();
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "New Subscriber Added";
        $activity->activity_detail = "New Subscriber ".$user->name." registered at ".$data['local_time'];
        $activity->activity_icon = "user.png";
        $activity->local_time = $data['local_time'];
        $activity->save();
        if(isset($find_referral)){
            $totalCommission = $find_referral->affiliateTotalCommission->sum('amount_added');
            $ref_amount =  commission_amount($totalCommission,$amount,$find_referral) ;
            $ace = AffiliateCommissionEarnt::where('referral_code', $data['referral'])->first();
            if (!empty($ace)) {
                $ace->total_earned +=  $ref_amount;
                $ace->pending_amount +=  $ref_amount;
                $ace->last_paid_at = date("Y-m-d H:m:s");
                $ace->save();

            }
            $wallet = $find_referral->wallet;
            $find_referral->wallet = $wallet + $ref_amount;
            $find_referral->save();
            $save_referral = new Referrals();
            $save_referral->referral_code = $data['referral'];
            $save_referral->userid = $user->id;
            $save_referral->user_name = $user->name;
            $save_referral->total_amount = $amount;
            $save_referral->amount_added = $ref_amount;
            $save_referral->previous_balance = $wallet;
            $save_referral->wallet_balance = $wallet + $ref_amount;
            $save_referral->type = 'Referral Commission';
            $save_referral->save();
            $use_referral = new Used_referrals();
            $use_referral->referral_code = $data['referral'];
            $use_referral->subscriber_id = $user->id;
            $use_referral->save();
        }
        $service_fee = $data['amount'];
        $discount = 0;
        $tax = ($service_fee + $discount) * (18/100);
        $tax = 0;
        $company = User::where('user_type','=','admin')->first();

        $subs = User::find($user->id);
        $internal_invoice = $this->createAdminApInvoiceAndPayment($subs, $company, (float) $amount, "Card", "Subscription Fees");

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
        $invoice->to_country =$user->country;
        $invoice->to_pincode = $user->pincode;
        $invoice->to_phone = $user->phone;
        $invoice->to_email = $user->email;
        $invoice->service_fee = $service_fee;
        $invoice->discount = $discount;
        $invoice->tax = $tax;
        $invoice->type = "inward";
        $invoice->payment_mode = "Card";
        $invoice->total = $service_fee - $discount + $tax;
        $invoice->save();
        $activity = new Activities();
        $activity->subscriber_id = $user->id;
        $activity->user_id = $user->id;
        $activity->user_name = $user->name;
        $activity->activity_name = "Invoice Generated";
        $activity->activity_detail = "Invoice generated for subscription update for user ".$user->name." at ".$data['local_time'];
        $activity->activity_icon = "invoice.jpg";
        $activity->local_time = $data['local_time'];
        $activity->save();
        $email = $data['email'];

        $welcomedata = new \stdClass();
        $welcomedata->name = $data['name'];
        $welcomedata->email = $email;
        $welcomedata->plan_name = $plan->plan_name;
        $welcomedata->duration = $duration." Year(s)";
        $welcomedata->amount = $amount;
        $welcomedata->invoice_id = $internal_invoice->id;
        $welcomedata->token = $internal_invoice->token;
        $welcomedata->subscription = "Paid";
        $welcomedata->subscription_type = $plan->plan_name;
        $welcomedata->start_date = !empty($user->membership_start_date)
            ? date('d M Y', strtotime($user->membership_start_date))
            : '-';
        $welcomedata->end_date = !empty($user->membership_expiry_date)
            ? date('d M Y', strtotime($user->membership_expiry_date))
            : '-';
        $welcomedata->paid_amount = number_format((float) $amount, 2);
        $welcomedata->from_email = $company->email;
        $welcomedata->from_name = $company->organization ?: 'adwiseri';
        $welcomedata->invoice_pdf_data = $this->buildInvoicePdfData($internal_invoice, $subs, $company);
        Mail::to($email)->cc('care@adwiseri.com')->send(new WelcomeMail($welcomedata));
            if (Mail::failures()) {
                echo 'Sorry! Please try again latter';
            }else{
                echo 'Great! Successfully send in your mail';
            }

        $maildata = new \stdClass();
        $maildata->name = $data['name'];
        $maildata->email = $email;
        $maildata->otp = $eotp;
        Mail::to($email)->send(new EmailVerification($maildata));
            if (Mail::failures()) {
                echo 'Sorry! Please try again latter';
            }else{
                echo 'Great! Successfully send in your mail';
            }
        session()->forget('reg_data');
        return redirect()->route('otp', $email);

        // return back();
    }
}
