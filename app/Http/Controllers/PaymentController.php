<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invoices;
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
use App\Models\Internal_Invoices;
use Carbon\Carbon;

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

            $invoices = $this->buildOutstandingInvoices($subscriber->id, 'ar');


            // $payments = Invoices::where('type', 'inward')->orderBy('created_at', 'desc')->get();
            return view('web.add_ar_payments', compact('user', 'payments', 'page','subscriber','clients', 'invoices'));
        } else {
            return back();
        }
    }
    
    public function getInvoiceDetails($id)
    {   
        $subscriberId = auth()->user()->added_by ? auth()->user()->added_by : auth()->user()->id;
        $invoice = Internal_Invoices::where('subscriber_id', $subscriberId)
            ->where('type', 'ar')
            ->where('invoice_no', $id)
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        $client = Clients::where('subscriber_id', $subscriberId)
            ->where(function ($query) use ($invoice) {
                $query->where('email', $invoice->to_email)
                    ->orWhere('name', $invoice->to_name);
            })
            ->first();

        $paidRows = PaymentARs::where('subscriber_id', $subscriberId)
            ->where('type', 'ar')
            ->where('invoice_no', $invoice->invoice_no)
            ->get();

        $paidAmount = (float) $paidRows->sum('paid_amount');
        $amount = (float) $invoice->total;
        $outstanding = max(0, $amount - $paidAmount);

        return response()->json([
            'success' => 'Successfull', 
            'client' => optional($client)->id,
            'applicationID' => null,
            'applicationName' => null,
            'service' => $invoice->detail,
            'amount' => $amount, 
            'paidAmmount' => $paidAmount,
            'outstandingAmount' => $outstanding,
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
        $invoice = Internal_Invoices::where('subscriber_id', $subscriber->id)
            ->where('type', 'ap')
            ->where('invoice_no', $id)
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        $client = Clients::where('subscriber_id', $subscriber->id)
            ->where(function ($query) use ($invoice) {
                $query->where('email', $invoice->to_email)
                    ->orWhere('name', $invoice->to_name);
            })
            ->first();
        $serviceProvider = $invoice->to_name;
        $serviceTaken = $invoice->detail;
        $amount = (float) $invoice->total;

        $paidRows = PaymentARs::where('subscriber_id', $subscriber->id)
            ->where('type', 'ap')
            ->where('invoice_no', $invoice->invoice_no)
            ->get();

        $paidAmount = (float) $paidRows->sum('paid_amount');
        $outstanding = max(0, ((float) $amount) - $paidAmount);

        return response()->json([
            'success' => 'Successfull', 
            'client' => optional($client)->id,
            'serviceProvider' => $serviceProvider,
            'serviceTaken' => $serviceTaken,
            'amount' => $amount, 
            'paidAmmount' => $paidAmount,
            'outstandingAmount' => $outstanding,
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

            $invoices = $this->buildOutstandingInvoices($subscriber->id, 'ap');
            
            // $payments = Invoices::where('type', 'inward')->orderBy('created_at', 'desc')->get();
            return view('web.add_ap_payments', compact('user', 'payments', 'page','subscriber','clients', 'invoices'));
        } else {
            return back();
        }
    }

    public function payment_received(Request $request){
        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date_format:d-m-Y',
        ]);

        $paymentDate = Carbon::createFromFormat('d-m-Y', $request->payment_date)->startOfDay();
        if ($paymentDate->lt(Carbon::today())) {
            return back()->withInput()->withErrors([
                'payment_date' => 'Payment date must be today or a future date.',
            ]);
        }
        $application  =  Applications::where('application_id',$request->application_id)->first();
        $data = $request->except(['_token','application_id','local_time']);
        $subscriber = auth()->user()->added_by ? auth()->user()->added_by : auth()->user()->id;
        $selectedInvoice = null;
        $selectedInvoiceNo = null;
        $outstandingAmount = (float) ($request->amount ?? 0);
        if (!empty($request->invoices_list)) {
            $selectedInvoice = Internal_Invoices::where('invoice_no', $request->invoices_list)
                ->where('subscriber_id', $subscriber)
                ->where('type', 'ar')
                ->first();
            if ($selectedInvoice) {
                $selectedInvoiceNo = $selectedInvoice->invoice_no;
                $paidAmount = (float) PaymentARs::where('subscriber_id', $subscriber)
                    ->where('type', 'ar')
                    ->where('invoice_no', $selectedInvoiceNo)
                    ->sum('paid_amount');
                $outstandingAmount = max(0, ((float) $selectedInvoice->total) - $paidAmount);
            }
        }
        if ((float) $request->paid_amount > $outstandingAmount) {
            return back()->withInput()->withErrors([
                'paid_amount' => 'Total Paid amount should not exceed ' . number_format($outstandingAmount, 2, '.', '') . ' (Outstanding) !.',
            ]);
        }

        $data['invoice_no'] = $selectedInvoiceNo ?: $this->invoice_id();
        $data['subscriber_id'] = $subscriber;
        if($application){
            $data['application_id'] = $application->id;
        }

        $data['type'] ='ar';
        $data['payment_date'] = $paymentDate->format('Y-m-d');
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
        return redirect()->route('my_payments')->with('payments_received', 'AR (Payments Received) record created successfully.');
    }
// NEED TO FIX ENTRY FORM FOR PAYMENT _AP TYPE BECAUSE IT DOES NOT HAVE CLIENT 
    public function  advance_payment(Request $request){
        $request->validate([
            'paid_amount' => 'required|numeric|min:0.01',
        ]);
        $data = $request->except(['_token','local_time']);
        $subscriber = auth()->user()->added_by ? auth()->user()->added_by : auth()->user()->id;
        $selectedInvoice = null;
        $selectedInvoiceNo = null;
        $outstandingAmount = (float) ($request->amount ?? 0);
        if (!empty($request->invoices_list)) {
            $selectedInvoice = Internal_Invoices::where('invoice_no', $request->invoices_list)
                ->where('subscriber_id', $subscriber)
                ->where('type', 'ap')
                ->first();
            if ($selectedInvoice) {
                $selectedInvoiceNo = $selectedInvoice->invoice_no;
                $paidAmount = (float) PaymentARs::where('subscriber_id', $subscriber)
                    ->where('type', 'ap')
                    ->where('invoice_no', $selectedInvoiceNo)
                    ->sum('paid_amount');
                $outstandingAmount = max(0, ((float) $selectedInvoice->total) - $paidAmount);
            }
        }
        if ((float) $request->paid_amount > $outstandingAmount) {
            return back()->withInput()->withErrors([
                'paid_amount' => 'Total Paid amount should not exceed ' . number_format($outstandingAmount, 2, '.', '') . ' (Outstanding) !.',
            ]);
        }

        $data['invoice_no'] = $selectedInvoiceNo ?: $this->invoice_id();
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
        return redirect()->route('payment_made')->with('advance_payment', 'AP (Payments Made) record created successfully.');
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

    private function buildOutstandingInvoices($subscriberId, $type)
    {
        $invoiceRows = Internal_Invoices::where('subscriber_id', $subscriberId)
            ->where('type', $type)
            ->whereNotIn(DB::raw('LOWER(status)'), ['cancelled', 'withdrawn'])
            ->orderBy('created_at', 'asc')
            ->get();

        $payments = PaymentARs::where('subscriber_id', $subscriberId)
            ->where('type', $type)
            ->orderBy('created_at', 'asc')
            ->get();

        $paymentsByInvoice = $payments->groupBy('invoice_no');

        return $invoiceRows
            ->map(function ($invoice) use ($type, $paymentsByInvoice, $subscriberId) {
                $group = $paymentsByInvoice->get($invoice->invoice_no, collect());
                $totalAmount = (float) $invoice->total;
                $totalPaid = (float) $group->sum('paid_amount');
                $outstanding = round(max(0, $totalAmount - $totalPaid), 2);

                if ($outstanding <= 0) {
                    return null;
                }

                $client = Clients::where('subscriber_id', $subscriberId)
                    ->where(function ($query) use ($invoice) {
                        $query->where('email', $invoice->to_email)
                            ->orWhere('name', $invoice->to_name);
                    })
                    ->first();

                $clientName = optional($client)->name ?? ($invoice->to_name ?: 'N/A');
                $serviceDescription = $invoice->detail ?: 'N/A';

                $applicationId = optional($group->first())->application_id;
                $serviceProvider = $type === 'ap' ? ($invoice->to_name ?: 'N/A') : optional($group->first())->service_provider;
                $serviceTaken = $type === 'ap' ? ($invoice->detail ?: 'N/A') : optional($group->first())->service_taken;

                return [
                    'id' => $invoice->invoice_no,
                    'client_id' => optional($client)->id,
                    'application_id' => $applicationId,
                    'service_description' => $invoice->detail,
                    'service_provider' => $serviceProvider,
                    'service_taken' => $serviceTaken,
                    'amount' => $totalAmount,
                    'paid_amount' => $totalPaid,
                    'outstanding_amount' => $outstanding,
                    'display_label' => $type === 'ap'
                        ? sprintf('%s - %s - %s', $invoice->invoice_no, $invoice->to_name ?: 'N/A', $serviceDescription)
                        : sprintf('%s - %s - %s', $invoice->invoice_no, $clientName, $serviceDescription),
                ];
            })
            ->filter()
            ->values();
    }

    private function calculateOutstandingAmount(PaymentARs $payment, string $type): float
    {
        $query = PaymentARs::where('subscriber_id', $payment->subscriber_id)
            ->where('type', $type)
            ->where('amount', $payment->amount)
            ->where('client_id', $payment->client_id);

        if ($type === 'ar') {
            $query->where('application_id', $payment->application_id)
                ->where('service_description', $payment->service_description);
        } else {
            $query->where('service_provider', $payment->service_provider)
                ->where('service_taken', $payment->service_taken);
        }

        $paidAmount = (float) $query->sum('paid_amount');
        return max(0, ((float) $payment->amount) - $paidAmount);
    }

}
