<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoices;
use App\Models\Internal_Invoices;
use App\Models\Invoice_settings;
use App\Models\Activities;
use App\Models\Applications;
use App\Models\UserRoles;
use Auth;
use Mail;
use App\Mail\Invoicemail;
use App\Models\PaymentARs;
use DateTime;
use DataTables;
use DB;

class PaymentController extends Controller
{
    //

    public function invoice_id()
    {
        $ch = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $id = "";
        for($i=0; $i<10; $i++){
            $id = $id.$ch[rand(0, strlen($ch)-1)];
        }
        if(PaymentARs::where('invoice_no','=',$id)->first()){
            return invoice_id();
        }
        return $id;
    }

    // public function my_payments()
    // {
    //     $user = auth()->user();

    //     if ($user->user_type == "Subscriber") {
    //         $subscriber = $user;
    //     } else {
    //         $subscriber = User::find($user->added_by);
    //     }

    //     $paymentAR = PaymentARs::select(
    //             'client_id',
    //             'application_id',
    //             'service_description',
    //             'service_provider',
    //             'service_taken',
    //             'payment_mode',
    //             'amount',
    //             DB::raw('SUM(paid_amount) as paid_amount'),
    //             DB::raw('(SUM(amount) - SUM(paid_amount)) as outstanding'),
    //             DB::raw('MAX(created_at) as latest_payment_date')
    //         )
    //         ->where('subscriber_id', $subscriber->id)
    //         ->where('type', 'ar')
    //         ->groupBy('client_id', 'application_id', 'service_description', 'service_provider', 'service_taken', 'payment_mode', 'amount')
    //         ->orderBy('latest_payment_date', 'desc')
    //         ->get();

    //     $page = "payments";

    //     return view('web.payments', compact('user', 'page', 'paymentAR'));
    // }

    public function my_payments()
    {
        $user = auth()->user();

        $subscriber = $user->user_type == "Subscriber"
            ? $user
            : User::find($user->added_by);

        // get all payment rows (no grouping in SQL)
        $paymentAR = PaymentARs::where('subscriber_id', $subscriber->id)
            ->where('type', 'ar')
            ->orderBy('created_at', 'desc')
            ->get();

        // group them in PHP by client+application to calculate totals
        $grouped = $paymentAR->groupBy(function ($row) {
            return $row->client_id . '|' . $row->application_id;
        });

        foreach ($grouped as $group) {
            $totalAmount = $group->first()->amount;        // base amount for that application
            $totalPaid   = $group->sum('paid_amount');     // total paid so far
            $outstanding = $totalAmount - $totalPaid;      // correct outstanding

            foreach ($group as $item) {
                $item->outstanding = $outstanding;         // attach correct outstanding
            }
        }

        // flatten back for view
        $paymentAR = $grouped->flatten()->sortByDesc('created_at')->values();

        $page = "payments";

        return view('web.payments', compact('user', 'page', 'paymentAR'));
    }


    public function  payment_made()
    {
        $user = $this->check_login();

        // $this->set_timezone();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }
        $paymentAP = PaymentARs::where('subscriber_id', '=', $subscriber->id)->where('type','ap')->orderBy('created_at', 'desc')->get();
        $page = "payments";
        // dd($paymentAP);
        return view('web.payments_made', compact('user', 'page', 'paymentAP'));
    }
    public function check_login()
    {
        $user = Auth::user();
        if ($user) {
            return $user;
        } else {
            $user = auth()->guard('affiliates')->user();
            if ($user) {
                $user = User::where('email', $user->email)->first();
                $user['type_user'] = 'affiliate';
                return $user;
            }
            return redirect()->route('login');
        }
    }
    public function add_ar_payments(){

        $user = auth()->user();

        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }

        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            } else {
                $subscriber = User::find($user->added_by);
                $clients = Clients::where('user_id', '=', $user->id)->get();
            }
            $page = "payments";
            $users = User::where('user_type','Subscriber')->get();
            $payments = Invoices::orderBy('created_at', 'desc')->get();
            // $invoices = Invoices::orderBy('created_at', 'desc')->get();
            // $invoices = Invoices::join('users', 'users.id', '=', 'invoices.user_id')
            // ->orderBy('invoices.created_at', 'desc')
            // ->select('invoices.id', 'invoices.invoice', 'users.id as user_id', 'users.name as user_name')
            // ->get();

            $invoices = PaymentARs::with('client')->where('subscriber_id', $subscriber->id)->where('type','ar')->orderBy('created_at', 'asc')->get();


            // $payments = Invoices::where('type', 'inward')->orderBy('created_at', 'desc')->get();
            return view('web.add_ar_payments', compact('user', 'payments', 'page','subscriber','clients', 'invoices'));
        } else {
            return back();
        }
    }
    
    public function getInvoiceDetails($id)
    {   
        // $invoice = Invoices::with(['clients', 'services'])->where('id', $id)->first();

        $paymentAR = PaymentARs::find($id);
        $client = Clients::find($paymentAR->client_id);
        $application = Applications::find($paymentAR->application_id); 
        $service = $paymentAR->service_description;
        $amount = $paymentAR->amount;
        $paidAmount = $paymentAR->paid_amount;

        $paidAmount = PaymentARs::where('client_id', $paymentAR->client_id)->where('subscriber_id', $paymentAR->subscriber_id)->where('type','ar')->orderBy('created_at', 'asc')->get();
        // dd($paidAmount->sum('paid_amount'));
        if (!$paymentAR) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json([
            'success' => 'Successfull', 
            'client' => $client->id,
            'applicationID' => $application->application_id, 
            'applicationName' => $application->application_name, 
            'service' => $service,
            'amount' => $amount, 
            'paidAmmount' => $paidAmount->sum('paid_amount')
        ], 200);
    }

    public function getAPInvoiceDetails($id){
        $user = Auth::user();
        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
            $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
        } else {
            $subscriber = User::find($user->added_by);
            $clients = Clients::where('user_id', '=', $user->id)->get();
        }
        $paymentAR = PaymentARs::find($id);
        $client = Clients::find($paymentAR->client_id);
        $application = Applications::find($paymentAR->application_id); 
        $serviceProvider = $paymentAR->service_provider;
        $serviceTaken = $paymentAR->service_taken;
        $amount = $paymentAR->amount;
        $paidAmount = $paymentAR->paid_amount;

        if (!$paymentAR) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        $paidAmount = PaymentARs::where('subscriber_id', $subscriber->id)->where('type','ap')->orderBy('created_at', 'asc')->get();

        // $paidAmount = PaymentARs::where('subscriber_id', $subscriber->id)->where('type','ap')->orderBy('created_at', 'asc')->get();
        // dd($paidAmount->sum('paid_amount'), $paidAmount->count());
        if (!$paymentAR) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return response()->json([
            'success' => 'Successfull', 
            'serviceProvider' => $serviceProvider,
            'serviceTaken' => $serviceTaken,
            'amount' => $amount, 
            'paidAmmount' => $paidAmount->sum('paid_amount')
        ], 200);
    }

    public function add_ap_payments(){
        $user = Auth::user();
        if ($user) {
            if ($user->user_type == "Subscriber") {
                $subscriber = $user;
                $clients = Clients::where('subscriber_id', '=', $subscriber->id)->get();
            } else {
                $subscriber = User::find($user->added_by);
                $clients = Clients::where('user_id', '=', $user->id)->get();
            }
            $page = "payments";
            $users = User::where('user_type','Subscriber')->get();
            $payments = Invoices::orderBy('created_at', 'desc')->get();

            $invoices = PaymentARs::with('client')->where('subscriber_id', $subscriber->id)->where('type','ap')->orderBy('created_at', 'asc')->get();
            
            // $payments = Invoices::where('type', 'inward')->orderBy('created_at', 'desc')->get();
            return view('web.add_ap_payments', compact('user', 'payments', 'page','subscriber','clients', 'invoices'));
        } else {
            return back();
        }
    }

    public function payment_received(Request $request){

        $application  =  Applications::where('application_id',$request->application_id)->first();
        $data = $request->except(['_token','application_id','local_time']);
        $subscriber = auth()->user()->added_by ? auth()->user()->added_by : auth()->user()->id;
        $data['invoice_no'] =$this->invoice_id();
        $data['subscriber_id'] = $subscriber;
        if($application){
            $data['application_id'] = $application->id;
        }

        $data['type'] ='ar';
        $data['payment_date'] =now();
        $paymentAR = PaymentARs::create($data);

        $activity = new Activities();
        $activity->subscriber_id = $subscriber ;
        $activity->user_id = auth()->user()->id;
        $activity->user_name =  auth()->user()->name;
        $activity->activity_name = "New AR Record added";
        if (auth()->user()->user_type == "Subscriber") {
            $activity->activity_detail = "New AR Record added by " .  auth()->user()->name . " at " . $request->local_time;
        } else {
            $activity->activity_detail = "New AR Record added by " .  auth()->user()->name . "(" . auth()->user()->$subscriber->name . ") at " . $request->local_time;
        }
        $activity->activity_icon = "invoice.jpg";
        $activity->local_time = $request->local_time;
        $activity->save();
        return redirect()->route('my_payments')->with('payments_received', 'AR(Payments Received) Record Created Successfully..');
    }
// NEED TO FIX ENTRY FORM FOR PAYMENT _AP TYPE BECAUSE IT DOES NOT HAVE CLIENT 
    public function  advance_payment(Request $request){

        $data = $request->except(['_token','local_time']);
        $subscriber = auth()->user()->added_by ? auth()->user()->added_by : auth()->user()->id;
        $data['invoice_no'] =$this->invoice_id();
        $data['subscriber_id'] = $subscriber;
        $data['type'] ='ap';
        $data['payment_date'] =now();
        $paymentAR = PaymentARs::create($data);

        $activity = new Activities();
        $activity->subscriber_id = $subscriber ;
        $activity->user_id = auth()->user()->id;
        $activity->user_name =  auth()->user()->name;
        $activity->activity_name = "New AP Record added";
        if (auth()->user()->user_type == "Subscriber") {
            $activity->activity_detail = "New AP Record added by " .  auth()->user()->name . " at " . $request->local_time;
        } else {
            $activity->activity_detail = "New AP Record added by " .  auth()->user()->name . "(" . auth()->user()->$subscriber->name . ") at " . $request->local_time;
        }
        $activity->activity_icon = "invoice.jpg";
        $activity->local_time = $request->local_time;
        $activity->save();
        return redirect()->route('payment_made')->with('advance_payment', 'AP(Payments Made) Record Created Successfully.');
    }
    public function subscriberPayments()
    {
        $user = auth()->user();

        if ($user->user_type == "Subscriber") {
            $subscriber = $user;
        } else {
            $subscriber = User::find($user->added_by);
        }
         $paymentARs = PaymentARs::where('subscriber_id', '=', $subscriber->id)->orderBy('created_at', 'desc')->get();
         return DataTables::of($paymentARs)
                ->addIndexColumn()
                ->editColumn('client', function ($row) {

                    return $row->client_id ? $row->client->name.'('.$row->client_id.')' :'';
                })

                ->editColumn('outstanding', function ($row) {
                    return ($row->amount - $row->paid_amount);
                })
                ->editColumn('payment_date', function ($row) {
                    return date("d-m-Y", strtotime($row->payment_date));
                })
                ->editColumn('payment_type', function ($row) {
                    return $row->type == 'ap' ? 'AP' :'AR';
                })
                ->editColumn('created_at', function ($row) {
                    return date("d-m-Y", strtotime($row->created_at));
                })

                ->make(true);
    }




}
