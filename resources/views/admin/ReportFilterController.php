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
use DateTime;
use DateTimeZone;
use App\Mail\Invoicemail;
use App\Mail\PlanSubscriptionMail;
use App\Models\User;
use App\Models\Clients;
use App\Models\Currency;
use App\Models\Offers;
use App\Models\Countries;
use App\Models\States;
use App\Models\Contactus;
use App\Models\Membership;
use App\Models\Features;
use App\Models\About_Advisori;
use App\Models\Messages;
use App\Models\Activities;
use App\Models\AffiliateCommision;
use App\Models\Invoices;
use App\Models\Subscriber_Categories;
use App\Models\Subscriber_Sub_Categories;
use App\Models\Client_jobs;
use App\Models\Client_Docs;
use App\Models\Applications;
use App\Models\Application_assignments;
use App\Models\Internal_Invoices;
use App\Models\Client_discussions;
use App\Models\Tickets;
use App\Models\Faq;
use App\Models\Invoice_settings;
use App\Models\DemoRequests;
use App\Models\UserRoles;
use App\Models\Referrals;
use App\Models\Used_referrals;
use App\Models\AffiliateCommissionEarnt;
use App\Models\Internal_communications;
use App\Models\Affiliates;
use App\Models\Feedbacks;
use App\Models\PaymentARs;
use Carbon\CarbonInterface;

use DataTables;

class ReportFilterController extends Controller
{
    //

    public function clientsReport()
    {
        // this is used for subscriber check $user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") &&
        $user = Auth::user();

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "homeCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $clientsByCountry = Clients::whereBetween('created_at', [$startDate, $endDate])->where('subscriber_id', '=', $user->id)->select('country as country_name', DB::raw('COUNT(subscriber_id) as No_of_Subscribers'))
                    ->groupBy('country')->get();
            }else {
                $clientsByCountry = Clients::whereBetween('created_at', [$startDate, $endDate])->select('country as country_name', DB::raw('COUNT(subscriber_id) as No_of_Subscribers'))
                    ->groupBy('country')
                    ->get();
            }
            return DataTables::of($clientsByCountry)
                ->addIndexColumn()
                ->editColumn('country_name', function ($row) {
                    return !empty($row->country_name) ? $row->country_name : "N/A";
                })
                ->make(true);
        } elseif (request()->type == "ageGroup") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {


                $clients = DB::table(DB::raw('(SELECT "Under 18" AS age_group UNION ALL 
                        SELECT "18-25" UNION ALL 
                        SELECT "25-35" UNION ALL 
                        SELECT "35-45" UNION ALL 
                        SELECT "45-55" UNION ALL 
                        SELECT "Over 55") AS age_groups'))
                ->leftJoinSub(
                Clients::whereBetween('created_at', [$startDate, $endDate])
                ->where('subscriber_id', '=', $user->id)
                ->select(
                DB::raw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-35'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-45'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                        ELSE 'Over 55'
                    END AS age_group,
                    COUNT(*) AS count
                ")
                )
                ->groupBy('age_group'),
                'clients',
                'age_groups.age_group',
                '=',
                'clients.age_group'
                )
                ->select('age_groups.age_group', DB::raw('COALESCE(clients.count, 0) AS count'))
                ->orderByRaw("
                CASE 
                WHEN age_groups.age_group = 'Under 18' THEN 1
                WHEN age_groups.age_group = '18-25' THEN 2
                WHEN age_groups.age_group = '25-35' THEN 3
                WHEN age_groups.age_group = '35-45' THEN 4
                WHEN age_groups.age_group = '45-55' THEN 5
                WHEN age_groups.age_group = 'Over 55' THEN 6
                END
                ") // Ensures proper sorting
                ->get();

            }
            if ($user->user_type == 'admin') {
                $clients = DB::table(DB::raw('(SELECT "Under 18" AS age_group UNION ALL 
                SELECT "18-25" UNION ALL 
                SELECT "25-35" UNION ALL 
                SELECT "35-45" UNION ALL 
                SELECT "45-55" UNION ALL 
                SELECT "Over 55") AS age_groups'))
        ->leftJoinSub(
        Clients::where('subscriber_id', '=', $user->id)
        ->select(
        DB::raw("
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-35'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-45'
                WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                ELSE 'Over 55'
            END AS age_group,
            COUNT(*) AS count
        ")
        )
        ->groupBy('age_group'),
        'clients',
        'age_groups.age_group',
        '=',
        'clients.age_group'
        )
        ->select('age_groups.age_group', DB::raw('COALESCE(clients.count, 0) AS count'))
        ->orderByRaw("
        CASE 
        WHEN age_groups.age_group = 'Under 18' THEN 1
        WHEN age_groups.age_group = '18-25' THEN 2
        WHEN age_groups.age_group = '25-35' THEN 3
        WHEN age_groups.age_group = '35-45' THEN 4
        WHEN age_groups.age_group = '45-55' THEN 5
        WHEN age_groups.age_group = 'Over 55' THEN 6
        END
        ") // Ensures proper sorting
        ->get();

            }


            return DataTables::of($clients)
            ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'appType') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {


                $applications = Applications::whereBetween('created_at', [$startDate, $endDate])->where('subscriber_id', $user->id)->selectRaw('application_name, COUNT(client_id) AS number_of_clients')
                    ->groupBy('application_name')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $applications = Applications::whereBetween('created_at', [$startDate, $endDate])->selectRaw('application_name, COUNT(client_id) AS number_of_clients')
                    ->groupBy('application_name')
                    ->get();
            }
            return DataTables::of($applications)
            ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'applications') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                    $applications = Clients::join('applications', 'clients.id', '=', 'applications.client_id')
                    ->join('users', 'clients.subscriber_id', '=', 'users.id')
                    ->whereBetween('clients.created_at', [$startDate, $endDate])
                    ->where('clients.subscriber_id', $user->id) // Ensure table alias is correct
                    ->selectRaw('
                        users.name as subscriber_name,
                        users.id as sub_id,
                        CONCAT(clients.name, " (", clients.id, ")") as client_name,
                        clients.id as client_id,
                        COUNT(applications.id) as no_of_applications
                    ')
                    ->groupBy('clients.id', 'users.id')
                    ->havingRaw('COUNT(applications.id) > 1')
                    ->get();
                   
            }
            if ($user->user_type == 'admin') {

                $applications = Clients::join('applications', 'clients.id', '=', 'applications.client_id')
                    ->join('users', 'clients.subscriber_id', '=', 'users.id')
                    ->whereBetween('clients.created_at', [$startDate, $endDate])
                    ->selectRaw('
                        users.name as subscriber_name,
                        users.id as sub_id,
                        clients.name as client_name,
                        clients.id as client_id,
                        COUNT(applications.id) as no_of_applications
                    ')
                    ->groupBy('clients.id', 'users.id')
                    ->havingRaw('COUNT(applications.id) > 1')
                    ->get();
                    
            }
            return DataTables::of($applications)
            ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'payment_mode') {
            $paymentMode =PaymentARs::pluck('payment_mode')->toArray();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $app = PaymentARs::whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('payment_mode', $paymentMode)
                    ->where('subscriber_id', $user->id)
                    ->selectRaw('payment_mode') // Select the payment mode column for grouping
                    ->selectRaw('COUNT(*) as number_of_payment') // Count the number of invoices
                    // ->selectRaw('SUM(amount) as total_payment_sum') // Calculate the sum of invoice totals
                    ->groupBy('payment_mode')
                    ->get();
                    
            }
            if ($user->user_type == 'admin') {
                // $applications = Invoices::selectRaw('payment_mode, COUNT(user_id) AS no_of_applications')
                //     ->groupBy('payment_mode');
                $app = PaymentARs::whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('payment_mode', $paymentMode)
                    ->selectRaw('payment_mode') // Select the payment mode column for grouping
                    ->selectRaw('COUNT(*) as number_of_payment') // Count the number of invoices
                    // ->selectRaw('SUM(amount) as total_payment_sum') // Calculate the sum of invoice totals
                    ->groupBy('payment_mode')
                    ->get();
                  
            }

            // return response()->json(['data' => $applications]);
            return  DataTables::of($app)
            ->addIndexColumn()
            
            ->make(true);
        } elseif (request()->type == 'paymentAmount') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            
                $paymentAP = PaymentARs::where('type', 'ar')
                ->where('subscriber_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('client_id') // Ensure there is a client_id
                ->with([
                    'client', // Eager load client name
                    'application' // Eager load application name (in case application_id is not found)
                ])
                ->orderBy('created_at', 'desc')
                ->get();
               
               
            }
            if ($user->user_type == 'admin') {
                $paymentAP = PaymentARs::where('type', 'ar')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('client_id') // Ensure there is a client_id
                ->with([
                    'client', // Eager load client name
                    'application' // Eager load application name (in case application_id is not found)
                ])
                ->orderBy('created_at', 'desc')
                ->get();
               
               

            }
         
            
            return DataTables::of($paymentAP)
            ->addIndexColumn()
            ->addColumn('application_name', function ($row) {
                return $row->application 
                    ? $row->application->application_name.'('.$row->application_id.')'  // ✅ Use application name if exists
                    : ($row->service_description ?? 'N/A'); // ✅ Otherwise, use service_description
            })
            ->addColumn('client_name', function ($row) {
                return $row->client 
                    ? $row->client->name . ' (' . $row->client_id . ')' 
                    : 'Unknown Client'; // ✅ Prevents undefined error
            })
            ->addColumn('amount_to_pay', function ($row) {
                return  number_format((float) ($row->amount - $row->paid_amount));
            })
            ->addColumn('due_date', function ($row) {
                return \Carbon\Carbon::parse($row->payment_date)->format('d-m-Y'); 
            })
            ->make(true);


        } elseif (request()->type == 'document') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

               
                $clientDocs = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join Client model
                ->selectRaw('clients.name as client_name, client_docs.client_id, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
                ->groupBy('client_docs.client_id', 'clients.name') // Group by client_id and client name
                ->where('client_docs.user_id', $user->id)
                ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                ->orderBy('no_of_docs','desc')
                ->get();
               
            }
            if ($user->user_type == 'admin') {

                $clientDocs = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join Client model
                ->selectRaw('clients.name as client_name, client_docs.client_id, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
                ->groupBy('client_docs.client_id', 'clients.name') // Group by client_id and client name
                ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                ->orderBy('no_of_docs','desc')
                ->get();

            }

            
            return  DataTables::of($clientDocs)
            ->addIndexColumn()
            ->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name.'('.$row->client_id.')' : '';
            })
            
            ->make(true);
        } elseif (request()->type == 'dependants') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                    $clientsWithDependants = Clients::withCount('dependants') // Count dependants for each client
                    ->where('subscriber_id', $user->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                ->havingRaw('dependants_count > 0')
                ->orderBy('dependants_count', 'desc')
                ->get();
            }
            if ($user->user_type == 'admin') {

                $clientsWithDependants = Clients::withCount('dependants') // Count dependants for each client
                ->whereBetween('created_at', [$startDate, $endDate])
                ->havingRaw('dependants_count > 0')
                ->orderBy('dependants_count', 'desc')
                ->get();

            }
            return  DataTables::of($clientsWithDependants)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return  $row->name.'('.$row->id.')';
            })
            
            ->make(true);;
        }
    }
    public function applicationsReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "visaCountry") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applicationsByVisaCountry = Applications::select('application_country as country', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('subscriber_id', $user->id)
                ->groupBy('country')->get();
               
            }
            if ($user->user_type == 'admin') {
                $applicationsByVisaCountry = Applications::select('application_country as country', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('country')->get();
            }
            
            
            return DataTables::of($applicationsByVisaCountry)
            ->addIndexColumn()
            
            ->rawColumns(['name', 'subscriber'])
            ->make(true);
        } elseif (request()->type == "applicationCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applicationsByApplicationCountry = Applications::where('subscriber_id', $user->id)->select('application_country as country', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('country')->get();
            }
            if ($user->user_type == 'admin') {
                $applicationsByApplicationCountry = Applications::select('application_country as country', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('country')->get();
            }
            return  DataTables::of($applicationsByApplicationCountry)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "applicationType") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $applicationsByApplicationType = Applications::where('subscriber_id', $user->id)->select('application_name as applicationType', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('applicationType')->get();
            }
            if ($user->user_type == 'admin') {
                $applicationsByApplicationType = Applications::select('application_name as applicationType', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('applicationType')->get();
            }

            return  DataTables::of( $applicationsByApplicationType)
            ->addIndexColumn()
            ->make(true);

        } elseif (request()->type == "noOfApplicaitonsPerApplication") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applicationsByDependants = Applications::where('subscriber_id', $user->id)->select('application_name as applicationName', DB::raw('count(*) as application_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('applicationName')->get();
                $applicationsByDependants = Applications::select(
                    
                    'applications.application_name as applicationName', // Select application name
                    DB::raw('SUM(CASE WHEN dependants.client_id IS NULL THEN 1 ELSE 0 END) as single'), // Clients with 0 dependants
                    DB::raw('SUM(CASE WHEN dependants.client_id IS NOT NULL THEN 1 ELSE 0 END) as joint') // Clients with 1 or more dependants
                )
                ->join('clients', 'applications.client_id', '=', 'clients.id') // Join with clients
                ->leftJoin('dependants', 'clients.id', '=', 'dependants.client_id') // Left join with dependants
                // ->whereBetween('applications.created_at', [$startDate, $endDate])
                ->where('applications.subscriber_id', $user->id)
                ->whereBetween('applications.created_at', [$startDate, $endDate])
                ->groupBy('applications.application_name') // Group by application name
                ->orderBy('applications.application_name') // Order by application name
                ->get();
            }
            if ($user->user_type == 'admin') {

                $applicationsByDependants = Applications::select(
                        'applications.application_name as applicationName', // Select application name
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NULL THEN 1 ELSE 0 END) as single'), // Clients with 0 dependants
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NOT NULL THEN 1 ELSE 0 END) as joint') // Clients with 1 or more dependants
                    )
                    ->join('clients', 'applications.client_id', '=', 'clients.id') // Join with clients
                    ->leftJoin('dependants', 'clients.id', '=', 'dependants.client_id') // Left join with dependants
                    // ->whereBetween('applications.created_at', [$startDate, $endDate])
                    ->groupBy('applications.application_name') // Group by application name
                    ->orderBy('applications.application_name') // Order by application name
                    ->get();
            }
            return  DataTables::of($applicationsByDependants)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "paymentMode") {
            $paymentMode =PaymentARs::pluck('payment_mode')->toArray();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {


                $applicationsByPaymentMode = PaymentARs::where('subscriber_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('application_id')
                ->whereIn('payment_mode', $paymentMode)
                ->selectRaw('payment_mode')
                ->selectRaw('COUNT(*) as application_count')
                ->groupBy('payment_mode')
                ->get();

            }
            if ($user->user_type == 'admin') {
                $applicationsByPaymentMode = PaymentARs::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('application_id')
                ->whereIn('payment_mode', $paymentMode)
                ->selectRaw('payment_mode')
                ->selectRaw('COUNT(*) as application_count')
                ->groupBy('payment_mode')
                ->get();
            }

            return  DataTables::of($applicationsByPaymentMode)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "paymentAmount") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                    
                    $paymentAP = PaymentARs::where('type', 'ar')
                    ->leftJoin('applications', 'applications.id', '=', 'payment_ar.application_id') // Join applications table
                    ->select(
                        
                       
                        'payment_ar.application_id',
                        DB::raw('COUNT(payment_ar.client_id) as total_clients'),
                        'applications.application_name' // Select application name from joined table
                    )
                    ->whereNotNull('payment_ar.application_id')
                    ->where('payment_ar.subscriber_id', $user->id)
                    ->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                    ->groupBy( 'application_id', 'applications.application_name') // Grouping correctly
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $paymentAP = PaymentARs::where('type', 'ar')
                ->leftJoin('applications', 'applications.id', '=', 'payment_ar.application_id') // Join applications table
                ->select(
                    
                   
                    'payment_ar.application_id',
                    DB::raw('COUNT(payment_ar.client_id) as total_clients'), // Sum the amount_to_pay
                    'applications.application_name' // Select application name from joined table
                )
            
                ->whereNotNull('payment_ar.application_id')
                ->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->groupBy( 'application_id', 'applications.application_name') // Grouping correctly
                ->get();
            }

            
            return DataTables::of($paymentAP)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "documentStored") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                    $applicationsByDocumentStored = Client_Docs::join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join Client model
                    ->selectRaw('applications.application_name as name, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
                    ->groupBy('client_docs.application_id', 'applications.application_name') // Group by application_id and application_name
                    ->whereNotNull('client_docs.application_id') // Ensure application_id is not null in Client_Docs table
                    ->where('client_docs.user_id', $user->id)
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Date range filter
                    ->orderBy('no_of_docs', 'desc') // Order by the count of documents in descending order
                    ->get();
            }
            if ($user->user_type == 'admin') {

                $applicationsByDocumentStored = Client_Docs::join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join Client model
                ->selectRaw('applications.application_name as name, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
                ->groupBy('client_docs.application_id', 'applications.application_name') // Group by application_id and application_name
                ->whereNotNull('client_docs.application_id') // Ensure application_id is not null in Client_Docs table
                ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Date range filter
                ->orderBy('no_of_docs', 'desc') // Order by the count of documents in descending order
                ->get();
            }
            return  DataTables::of($applicationsByDocumentStored)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "noOfApplicaitonsBy1") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                    $totalApplications = Applications::where('subscriber_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            }
            if ($user->user_type == 'admin') {

                $totalApplications = Applications::whereBetween('created_at', [$startDate, $endDate])->count();
            }


            $data = collect(
                [
                    [
                        'total_applications_count' => $totalApplications,
                        'total_applications' => 'Total Applications',
                    ]
                ]
            );
            return  DataTables::of(  $data)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == "noOfApplicaitonsByQuaterly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $quarterlyApplications = Applications::where('subscriber_id', $user->id)->selectRaw('YEAR(created_at) as year, QUARTER(created_at) as quarter, COUNT(*) as application_count')
                    ->groupBy('year', 'quarter');
            }
            if ($user->user_type == 'admin') {
                $quarterlyApplications = Applications::selectRaw('YEAR(created_at) as year, QUARTER(created_at) as quarter, COUNT(*) as application_count')
                    ->groupBy('year', 'quarter');
            }


            return DataTables::of($quarterlyApplications)
                ->editColumn('quarter', function ($row) {
                    // Convert numeric quarter to 1st, 2nd, 3rd, or 4th
                    switch ($row->quarter) {
                        case 1:
                            return 'Q1';
                        case 2:
                            return 'Q2';
                        case 3:
                            return 'Q3';
                        case 4:
                            return 'Q4';
                        default:
                            return 'Unknown';
                    }
                })
                ->make(true);
        } elseif (request()->type == "noOfApplicaitonsByYearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $yearlyApplications = Applications::where('subscriber_id', $user->id)->selectRaw('YEAR(created_at) as year, COUNT(*) as application_count')
                    ->groupBy('year');
            }
            if ($user->user_type == 'admin') {
                $yearlyApplications = Applications::selectRaw('YEAR(created_at) as year, COUNT(*) as application_count')
                    ->groupBy('year');
            }

            return DataTables::of($yearlyApplications)
                ->editColumn('year', function ($row) {
                    return !empty($row->year) ? $row->year : "N/A";
                })
                ->make(true);
        } elseif (request()->type == "noOfApplicaitonsBy") {
            $currentYear = date('Y'); // Get the current year
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            
            }
            $weeklyApplications = Applications::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $currentYear)
            ->selectRaw("CONCAT('Week ', WEEK(created_at), ' (', YEAR(created_at), ')') as type, COUNT(*) as count")
            ->groupByRaw('type')
            ->get();
        
        $quarterlyApplications = Applications::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $currentYear)
            ->selectRaw("CONCAT('Q', QUARTER(created_at), ' ', YEAR(created_at)) as type, COUNT(*) as count")
            ->groupByRaw('type')
            ->get();
        
        $monthlyApplications = Applications::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $currentYear)
            ->selectRaw("DATE_FORMAT(created_at, '%M %Y') as type, COUNT(*) as count")
            ->groupByRaw('type')
            ->get();
        
        $pastYearData = Applications::whereYear('created_at', '<', $currentYear)
            ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            ->groupBy('type')
            ->orderBy('type', 'desc') // ✅ Ensures past years are ordered properly
            ->get();
        
        $formattedData = collect();
        
        // Append all grouped data to formattedData
        foreach ($weeklyApplications as $week) {
            $formattedData->push(['type' => $week->type, 'count' => $week->count]);
        }
        foreach ($quarterlyApplications as $quarter) {
            $formattedData->push(['type' => $quarter->type, 'count' => $quarter->count]);
        }
        foreach ($monthlyApplications as $month) {
            $formattedData->push(['type' => $month->type, 'count' => $month->count]);
        }
        foreach ($pastYearData as $year) {
            $formattedData->push(['type' => $year->type, 'count' => $year->count]);
        }
        
        $formattedData = $formattedData->sortByDesc(function ($item) {
            return intval(preg_replace('/\D/', '', $item['type'])); // Extract numeric value from type for sorting
        })->values(); // Reset keys after sorting
        
        // ✅ Assign Sr.No in correct order **after sorting**
        $formattedData = $formattedData->map(function ($item, $index) {
            return [
                'DT_RowIndex' => $index + 1, // Now properly sequential
                'type' => $item['type'],
                'count' => $item['count']
            ];
        });
              
           
            return DataTables::of( $formattedData)->make(true);
        }
    }
    public function usersReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byRole") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $userFetch = User::where('added_by', $user->id)
                ->where('user_type','User')
                ->whereNotNull('designation')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('designation', DB::raw('count(*) as users'))
                ->groupBy('designation')->get();
            }
            if ($user->user_type == 'admin') {
                $userFetch = User::where('user_type','User')->whereNotNull('designation')->whereBetween('created_at', [$startDate, $endDate])->select('designation', DB::raw('count(*) as users'))->groupBy('designation')->get();

            }
            return response()->json(['data' =>  $userFetch]);
        } else if (request()->type == "ageGroup") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $ageGroup = User::where('user_type','User')->where('added_by', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw("
                        CASE
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-35'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-45'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                            ELSE 'Over 55'
                        END AS age_group
                    "),
                    DB::raw('COUNT(*) AS count')
                )
                    ->groupBy('age_group');
            }
            if ($user->user_type == 'admin') {
                $ageGroup = User::where('user_type','User')->whereBetween('created_at', [$startDate, $endDate])->select(
                    DB::raw("
                        CASE
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-35'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-45'
                            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                            ELSE 'Over 55'
                        END AS age_group
                    "),
                    DB::raw('COUNT(*) AS count')
                )
                    ->groupBy('age_group');
            }


            return DataTables::of($ageGroup)
                ->make(true);
        } else if (request()->type == "applicationProcessed") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $applicationProcessed = Application_assignments::where('subscriber_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('user', function($query) {
                    $query->where('user_type', 'User');
                })->with('user')->select('user_id', DB::raw("COUNT(*) AS count"))->groupBy('user_id')->get();
            }
            if ($user->user_type == 'admin') {
                $applicationProcessed = Application_assignments::whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('user', function($query) {
                    $query->where('user_type', 'User');
                })->with('user')->select('user_id', DB::raw("COUNT(*) AS count"))->groupBy('user_id')->get();
            }


            return DataTables::of($applicationProcessed)
                ->addColumn('username', function ($row) {
                    return $row->user->id;
                })
                ->make(true);
        } else if (request()->type == "meetingNotes") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $ageGroup = Client_discussions::where('subscriber_id', $user->id)->whereHas('user', function($query) {
                    $query->where('user_type', 'User');
                })->whereBetween('created_at', [$startDate, $endDate])->select('user_name', DB::raw('count(discussion) as discussion'))->groupBy('user_name');
            }
            if ($user->user_type == 'admin') {

                $ageGroup = Client_discussions::whereHas('user', function($query) {
                    $query->where('user_type', 'User');
                })->whereBetween('created_at', [$startDate, $endDate])->select('user_name', DB::raw('count(discussion) as discussion'))->groupBy('user_name');
            }

            return DataTables::of($ageGroup)
                ->make(true);
        } else if (request()->type == "communication") {
            // $ageGroup = Client_discussions::select('communication_type', DB::raw('count(user_id) as user_id'))->groupBy('communication_type');

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $data = DB::table('client_discussions')
                ->where('subscriber_id', $user->id)
                ->join('users', 'client_discussions.user_id', '=', 'users.id') // Join with users to get the user_name
                ->where('users.user_type', 'User')
                ->whereBetween('client_discussions.created_at', [$startDate, $endDate])
                ->select(
                    'users.id as user_id',
                    'users.name as user_name',
                    'client_discussions.communication_type',
                    DB::raw("COUNT(DISTINCT client_discussions.user_id) AS total_users") // Count distinct users per communication type
                )
                ->groupBy( 'client_discussions.communication_type','users.name','users.id') // Group by user name and communication type
                ->get();

            }
            if ($user->user_type == 'admin') {

               $data = DB::table('client_discussions')
                ->join('users', 'client_discussions.user_id', '=', 'users.id') // Join with users to get the user_name
                ->where('users.user_type', 'User')
                ->whereBetween('client_discussions.created_at', [$startDate, $endDate])
                ->select(
                    'users.id as user_id',
                    'users.name as user_name',
                    'client_discussions.communication_type',
                    DB::raw("COUNT(DISTINCT client_discussions.user_id) AS total_users") // Count distinct users per communication type
                )
                ->groupBy( 'client_discussions.communication_type','users.name','users.id') // Group by user name and communication type
                ->get();


            // Transform the data into a user-based format

            }

            return DataTables::of( $data)
                ->addColumn('user', function ($row) {
                    return $row->user_name . ' (' . $row->user_id . ')';
                })

                ->make(true);
        } else if (request()->type == "message") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $messagesCount = Internal_communications::join('users', function ($join) {
                    $join->on('internal_communications.send_by', '=', 'users.id')
                         ->orWhereRaw("JSON_CONTAINS(internal_communications.send_to, JSON_QUOTE(CAST(users.id AS CHAR)))");
                })
                ->where('internal_communications.subscriber_id', $user->id)
                ->where('users.user_type', 'User') // Ensure user is of type 'User'
                ->whereBetween('internal_communications.created_at', [$startDate, $endDate]) // Filter messages by date
                ->selectRaw("
                    users.id AS user_id,
                    users.name AS user_name,
                    SUM(CASE WHEN internal_communications.send_by = users.id THEN 1 ELSE 0 END) AS total_messages_sent,
                    SUM(CASE
                        WHEN JSON_CONTAINS(internal_communications.send_to, JSON_QUOTE(CAST(users.id AS CHAR)))
                        THEN 1 ELSE 0
                    END) AS total_messages_received
                ")
                ->groupBy('users.id', 'users.name')
                ->orderBy('users.name', 'asc')
                ->get();
            }
            if ($user->user_type == 'admin') {
                // Subquery 1 for Admin: Count messages sent by each user
                $messagesCount = Internal_communications::join('users', function ($join) {
                    $join->on('internal_communications.send_by', '=', 'users.id')
                         ->orWhereRaw("JSON_CONTAINS(internal_communications.send_to, JSON_QUOTE(CAST(users.id AS CHAR)))");
                })
                ->where('users.user_type', 'User') // Ensure user is of type 'User'
                ->whereBetween('internal_communications.created_at', [$startDate, $endDate]) // Filter messages by date
                ->selectRaw("
                    users.id AS user_id,
                    users.name AS user_name,
                    SUM(CASE WHEN internal_communications.send_by = users.id THEN 1 ELSE 0 END) AS total_messages_sent,
                    SUM(CASE
                        WHEN JSON_CONTAINS(internal_communications.send_to, JSON_QUOTE(CAST(users.id AS CHAR)))
                        THEN 1 ELSE 0
                    END) AS total_messages_received
                ")
                ->groupBy('users.id', 'users.name')
                ->orderBy('users.name', 'asc')
                ->get();
            }

            return DataTables::of( $messagesCount)
                ->editColumn('user_id', function ($row) {
                    $user = User::find($row->user_id);
                    if (!empty($user)) {
                        return $user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        } else if (request()->type == "users") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $ageGroup = Clients::where('subscriber_id', $user->id)->select('subscriber_id', DB::raw('count(user_id) as user_id'))->groupBy('subscriber_id');
            }
            if ($user->user_type == 'admin') {
                $ageGroup = Clients::select('subscriber_id', DB::raw('count(user_id) as user_id'))->groupBy('subscriber_id');
            }

            return DataTables::of($ageGroup)
                ->editColumn('subscriber_id', function ($row) {
                    $user = User::find($row->subscriber_id);
                    if (!empty($user)) {
                        return $user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        }
    }
    public function activityReport()
    {
        if (request()->type == "byActivityType") {
            $activities = Activities::select('activity_name', DB::raw('count(*) as count'))->groupBy('activity_name');
            return DataTables::of($activities)->make(true);
        } elseif (request()->type == "byTime") {


            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;

            $queries = [
                DB::table('activities')
                    ->select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today),

                DB::table('activities')
                    ->select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                DB::table('activities')
                    ->select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                DB::table('activities')
                    ->select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                DB::table('activities')
                    ->select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear),

                DB::table('activities')
                    ->select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))
            ];

            $unionQuery = array_shift($queries);
            foreach ($queries as $query) {
                $unionQuery->unionAll($query);
            }


            return DataTables::of($unionQuery)->make(true);
        } elseif (request()->type == "bySubscribers") {
            $topSubscribers = Activities::with('user')->whereNotNull('subscriber_id')
                ->select('subscriber_id', DB::raw('COUNT(*) as total_activities'))
                ->groupBy('subscriber_id')
                ->limit(10);
            return DataTables::of($topSubscribers)
                ->editColumn('subscriber_id', function ($row) {
                    if (!empty($row->user)) {
                        return $row->user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        }
    }


    public function invoicesReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byAmount") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoices = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->where('user_id', $user->id)->select(
                    DB::raw('
                        CASE
                            WHEN total BETWEEN 1 AND 99 THEN "1-99"
                            WHEN total BETWEEN 100 AND 249 THEN "100-249"
                            WHEN total BETWEEN 250 AND 499 THEN "250-499"
                            WHEN total BETWEEN 500 AND 999 THEN "500-999"
                            WHEN total BETWEEN 1000 AND 2499 THEN "1000-2499"
                            WHEN total BETWEEN 2500 AND 4999 THEN "2500-4999"
                            WHEN total BETWEEN 5000 AND 9999 THEN "5000-9999"
                            WHEN total >= 10000 THEN "10,000+"
                        END AS amount_range')
                )
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('amount_range')
                    ->orderBy('amount_range', 'asc')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $invoices = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('
                        CASE
                            WHEN total BETWEEN 1 AND 99 THEN "1-99"
                            WHEN total BETWEEN 100 AND 249 THEN "100-249"
                            WHEN total BETWEEN 250 AND 499 THEN "250-499"
                            WHEN total BETWEEN 500 AND 999 THEN "500-999"
                            WHEN total BETWEEN 1000 AND 2499 THEN "1000-2499"
                            WHEN total BETWEEN 2500 AND 4999 THEN "2500-4999"
                            WHEN total BETWEEN 5000 AND 9999 THEN "5000-9999"
                            WHEN total >= 10000 THEN "10,000+"
                        END AS amount_range')
                )
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('amount_range')
                    ->orderBy('amount_range', 'asc')
                    ->get();
            }
            
            return DataTables::of($invoices)
                ->make(true);
        } elseif (request()->type == "byType") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $invoice_interval = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->where('subscriber_id', $user->id)
                    ->select('status')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('status')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $invoice_interval = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->select('status')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('status')
                    ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        } elseif (request()->type == "byClient") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoice_interval = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->where('user_id', $user->id)
                    ->select('country')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('country')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $invoice_interval = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->select('country')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('country')
                    ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        } elseif (request()->type == "byVisaCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoice_interval = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->where('user_id', $user->id)
                    ->select('to_country')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('to_country')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $invoice_interval = Invoices::whereBetween('created_at', [$startDate, $endDate])
                ->select('to_country')
                    ->selectRaw('COUNT(*) as number_of_invoices')
                    ->groupBy('to_country')
                    ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        }
    }

    public function paymentReport()
    {
        $user = Auth::user();

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byPaymentMode") {
            $paymentMode = (request()->payment_mode == 'All' )  ?  PaymentARs::pluck('payment_mode')->toArray() : [request()->payment_mode] ;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applications = PaymentARs::where('subscriber_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('payment_mode, COUNT(*) as number_of_payment') // Combine the selects into one statement
                ->groupBy('payment_mode') // Group by the payment mode
                ->orderBy('number_of_payment','desc')
                ->get();

            }
            if ($user->user_type == 'admin') {
                $applications = PaymentARs::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('payment_mode, COUNT(*) as number_of_payment') // Combine the selects into one statement
                ->groupBy('payment_mode') // Group by the payment mode
                ->orderBy('number_of_payment','desc')
                ->get();

            }
          
            return DataTables::of($applications)
            ->make(true);
        } elseif (request()->type == 'byPaymentAmount') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoices = PaymentARs::where('subscriber_id',$user->id)
                ->select(
                    DB::raw('
                        CASE
                            WHEN amount BETWEEN 1 AND 99 THEN "1-99"
                            WHEN amount BETWEEN 100 AND 249 THEN "100-249"
                            WHEN amount BETWEEN 250 AND 499 THEN "250-499"
                            WHEN amount BETWEEN 500 AND 999 THEN "500-999"
                            WHEN amount BETWEEN 1000 AND 2499 THEN "1000-2499"
                            WHEN amount BETWEEN 2500 AND 4999 THEN "2500-4999"
                            WHEN amount BETWEEN 5000 AND 9999 THEN "5000-9999"
                            WHEN amount >= 10000 THEN "10,000+"
                        END AS amount_range')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('amount_range')
                ->get();
            }
            if ($user->user_type == 'admin') {
                $invoices = PaymentARs::select(
                    DB::raw('
                        CASE
                            WHEN amount BETWEEN 1 AND 99 THEN "1-99"
                            WHEN amount BETWEEN 100 AND 249 THEN "100-249"
                            WHEN amount BETWEEN 250 AND 499 THEN "250-499"
                            WHEN amount BETWEEN 500 AND 999 THEN "500-999"
                            WHEN amount BETWEEN 1000 AND 2499 THEN "1000-2499"
                            WHEN amount BETWEEN 2500 AND 4999 THEN "2500-4999"
                            WHEN amount BETWEEN 5000 AND 9999 THEN "5000-9999"
                            WHEN amount >= 10000 THEN "10,000+"
                        END AS amount_range')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('amount_range')
                ->get();
            }
            return DataTables::of($invoices)
                ->make(true);
        } elseif (request()->type == 'byInvoiceType') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {


                $invoices = PaymentARs::where('subscriber_id',$user->id)->select(
                    'payment_mode',
                    DB::raw('COUNT(*) as number_of_invoices'),
                    DB::raw('SUM(amount) as total_amount') // Summing the amount per payment mode
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('payment_mode') // Group by payment mode and amount range
                ->get();
            }
            if ($user->user_type == 'admin') {
                $invoices = PaymentARs::select(
                    'payment_mode',
                    DB::raw('COUNT(*) as number_of_invoices'),
                    DB::raw('SUM(amount) as total_amount') // Summing the amount per payment mode
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('payment_mode') // Group by payment mode and amount range
                ->get();
                // $invoices = PaymentARs::select(
               
            }
            return DataTables::of($invoices)
                ->make(true);
        } elseif (request()->type == "byClientCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoice_interval = PaymentARs::where('payment_ar.subscriber_id',$user->id)
                ->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->whereNotNull('payment_ar.client_id')
                ->join('clients', 'clients.id', '=', 'payment_ar.client_id') // Join with applications
                ->selectRaw('clients.country as country') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('clients.country') // Group by applications.county
                ->get();
            }
            if ($user->user_type == 'admin') {
                $invoice_interval = PaymentARs::whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->whereNotNull('payment_ar.client_id')
                ->join('clients', 'clients.id', '=', 'payment_ar.client_id') // Join with applications
                ->selectRaw('clients.country as country') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('clients.country') // Group by applications.county
                ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        } elseif (request()->type == "byVisaCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoice_interval =  PaymentARs::whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->where('payment_ar.subscriber_id',$user->id)
                ->whereNotNull('payment_ar.application_id')
                ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                ->selectRaw('applications.application_country as to_country') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('applications.application_country') // Group by applications.county
                ->get();
            }
            if ($user->user_type == 'admin') {

                $invoice_interval =  PaymentARs::whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->whereNotNull('payment_ar.application_id')
                ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                ->selectRaw('applications.application_country as to_country') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('applications.application_country') // Group by applications.county
                ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        } elseif (request()->type == "byApplicationType") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoice_interval =  PaymentARs::whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->where('payment_ar.subscriber_id',$user->id)
                ->whereNotNull('payment_ar.application_id')
                ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                ->selectRaw('applications.application_name as application_name') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('applications.application_name') // Group by applications.county
                ->get();
            }
            if ($user->user_type == 'admin') {

                $invoice_interval =  PaymentARs::whereBetween('payment_ar.created_at', [$startDate, $endDate])
                ->whereNotNull('payment_ar.application_id')
                ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                ->selectRaw('applications.application_name as application_name') // Use the county column from applications
                ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                ->groupBy('applications.application_name') // Group by applications.county
                ->get();
            }
            return DataTables::of($invoice_interval)
                ->make(true);
        }
    }


    public function communicationReport()
    {
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        $user = auth()->user();
        if (request()->type == "byMessages") {

            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
            $queries = [
                Internal_communications::select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today)->where('subscriber_id',$user->id),

                Internal_communications::select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->where('subscriber_id',$user->id),

                Internal_communications::select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('subscriber_id',$user->id),

                Internal_communications::select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd])->where('subscriber_id',$user->id),

                Internal_communications::select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear)->where('subscriber_id',$user->id),

                Internal_communications::select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))->where('subscriber_id',$user->id)
            ];
        }else{
            $queries = [
                Internal_communications::select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today),

                Internal_communications::select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                Internal_communications::select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                Internal_communications::select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                Internal_communications::select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear),

                Internal_communications::select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))
            ];
            }

            $unionQuery = array_shift($queries);
            foreach ($queries as $query) {
                $unionQuery->unionAll($query);
            }

            return DataTables::of($unionQuery)
                ->make(true);
        } elseif (request()->type == 'byMeeting') {
            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
            $queries = [
                Client_discussions::select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today)->where('subscriber_id',$user->id),

                Client_discussions::select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->where('subscriber_id',$user->id),

                Client_discussions::select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->where('subscriber_id',$user->id),

                Client_discussions::select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd])->where('subscriber_id',$user->id),

                Client_discussions::select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear)->where('subscriber_id',$user->id),

                Client_discussions::select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))->where('subscriber_id',$user->id)
            ];
        }else{
            $queries = [
                Client_discussions::select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today),

                Client_discussions::select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                Client_discussions::select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                Client_discussions::select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                Client_discussions::select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear),

                Client_discussions::select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))
            ];
            }

            $unionQuery = array_shift($queries);
            foreach ($queries as $query) {
                $unionQuery->unionAll($query);
            }

            return DataTables::of($unionQuery)
                ->make(true);
        } elseif (request()->type == 'meetingNotes') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            $cd = Client_discussions::select('communication_type')
                ->selectRaw('COUNT(*) as number_of_communication')
                ->whereBetween('created_at',[$startDate,  $endDate])
                ->groupBy('communication_type');
                }else{
                    $cd = Client_discussions::select('communication_type')
                    ->selectRaw('COUNT(*) as number_of_communication')
                    ->whereBetween('created_at',[$startDate,  $endDate])
                    ->where('subscriber_id',$user->id)
                    ->groupBy('communication_type');
                }
            return DataTables::of($cd)
                ->make(true);
        } elseif (request()->type == 'messagesSentByUser') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            $cd = Internal_communications::select('user_id')
                ->selectRaw('COUNT(*) as number_of_communication')
                ->whereBetween('created_at',[$startDate,  $endDate])
                ->where('subscriber_id',$user->id)
                ->groupBy('user_id')->get();
            }else{
                $cd = Internal_communications::select('user_id')
                ->selectRaw('COUNT(*) as number_of_communication')
                ->whereBetween('created_at',[$startDate,  $endDate])
                ->groupBy('user_id')->get();

            }
            return DataTables::of($cd)
                ->editColumn('user_id', function ($row) {
                    $user = User::find($row->user_id);
                    if (!empty($user)) {
                        return $user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        }
    }


    public function walletReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == 'byWallets') {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $invoices = Referrals::where('userid', $user->id)
                    ->select(
                        DB::raw("
                            CASE
                                WHEN wallet_balance BETWEEN 0 AND 99.99 THEN '1-99'
                                WHEN wallet_balance BETWEEN 100 AND 249.99 THEN '100-249'
                                WHEN wallet_balance BETWEEN 250 AND 499.99 THEN '250-499'
                                WHEN wallet_balance BETWEEN 500 AND 999.99 THEN '500-999'
                                WHEN wallet_balance BETWEEN 1000 AND 2499.99 THEN '1000-2499'
                                WHEN wallet_balance BETWEEN 2500 AND 4999.99 THEN '2500-4999'
                                WHEN wallet_balance BETWEEN 5000 AND 9999.99 THEN '5000-9999'
                                ELSE '10000+'
                            END AS payment_amount_range")
                    )
                    ->selectRaw('COUNT(*) as number_of_wallet')
                    ->groupBy('payment_amount_range');
            }
            if ($user->user_type == 'admin') {

                $invoices = Referrals::select(
                    DB::raw("
                            CASE
                                WHEN wallet_balance BETWEEN 0 AND 99.99 THEN '1-99'
                                WHEN wallet_balance BETWEEN 100 AND 249.99 THEN '100-249'
                                WHEN wallet_balance BETWEEN 250 AND 499.99 THEN '250-499'
                                WHEN wallet_balance BETWEEN 500 AND 999.99 THEN '500-999'
                                WHEN wallet_balance BETWEEN 1000 AND 2499.99 THEN '1000-2499'
                                WHEN wallet_balance BETWEEN 2500 AND 4999.99 THEN '2500-4999'
                                WHEN wallet_balance BETWEEN 5000 AND 9999.99 THEN '5000-9999'
                                ELSE '10000+'
                            END AS payment_amount_range")
                )
                    ->selectRaw('COUNT(*) as number_of_wallet')
                    ->whereBetween('created_at',[$startDate,  $endDate])
                    ->groupBy('payment_amount_range')
                    ->get();
            }


            return DataTables::of($invoices)
                ->make(true);
        } elseif (request()->type == 'byTransactions') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $cd = Referrals::where('userid', $user->id)
                    ->select('type')
                    ->selectRaw('COUNT(*) as number_of_communication')
                    ->groupBy('type');
                    return DataTables::of($cd)
                 ->editColumn('type', function ($row) {
                    return !empty($row->type) ? $row->type : "N/A";
                })
                ->make(true);
            }
            if ($user->user_type == 'admin') {
                $cd = Referrals::join('users', 'referrals.userid', '=', 'users.id') // Join with users table
                ->select('users.name as user_name', 'referrals.userid') // Select user name and ID
                ->selectRaw('COUNT(*) as number_of_communication') // Count referrals per user
                ->where('referrals.type', '!=', 'Referral Commission') // Exclude 'Referral Commission'
                ->whereBetween('referrals.created_at', [$startDate, $endDate]) // Filter by date
                ->groupBy('referrals.userid', 'user_name') // Group by user ID and name
                ->get();
                return DataTables::of($cd)
                 ->editColumn('user_name', function ($row) {
                    return $row->user_name.'('.$row->userid .')';
                })
                ->make(true);
            }

            
        } elseif (request()->type == 'byDates') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $monthlyTransactions = Referrals::where('userid', $user->id)
                    ->select(
                        DB::raw("DATE_FORMAT(`created_at`, '%Y-%m') AS period"),
                        DB::raw('COUNT(*) AS total_transactions'),
                        DB::raw("'Month' AS period_type")
                    )
                    ->groupBy('period');

                $quarterlyTransactions = Referrals::where('userid', $user->id)
                    ->select(
                        DB::raw("CONCAT(YEAR(`created_at`), '-Q', QUARTER(`created_at`)) AS period"),
                        DB::raw('COUNT(*) AS total_transactions'),
                        DB::raw("'Quarter' AS period_type")
                    )
                    ->groupBy('period');

                $yearlyTransactions = Referrals::where('userid', $user->id)
                    ->select(
                        DB::raw('YEAR(`created_at`) AS period'),
                        DB::raw('COUNT(*) AS total_transactions'),
                        DB::raw("'Year' AS period_type")
                    )
                    ->groupBy('period');

                $totalTransactions = Referrals::where('userid', $user->id)
                    ->select(
                        DB::raw("'Total' AS period"),
                        DB::raw('COUNT(*) AS total_transactions'),
                        DB::raw("'Overall' AS period_type")
                    );
            }
            if ($user->user_type == 'admin') {

                $monthlyTransactions = Referrals::select(
                    DB::raw("DATE_FORMAT(`created_at`, '%Y-%m') AS period"),
                    DB::raw('COUNT(*) AS total_transactions'),
                    DB::raw("'Month' AS period_type")
                )
                ->whereBetween('created_at',[$startDate,  $endDate])
                    ->groupBy('period');
                    

                $quarterlyTransactions = Referrals::select(
                    DB::raw("CONCAT(YEAR(`created_at`), '-Q', QUARTER(`created_at`)) AS period"),
                    DB::raw('COUNT(*) AS total_transactions'),
                    DB::raw("'Quarter' AS period_type")
                )
                ->whereBetween('created_at',[$startDate,  $endDate])
                    ->groupBy('period');

                $yearlyTransactions = Referrals::select(
                    DB::raw('YEAR(`created_at`) AS period'),
                    DB::raw('COUNT(*) AS total_transactions'),
                    DB::raw("'Year' AS period_type")
                )
                ->whereBetween('created_at',[$startDate,  $endDate])
                    ->groupBy('period');

                $totalTransactions = Referrals::select(
                    DB::raw("'Total' AS period"),
                    DB::raw('COUNT(*) AS total_transactions'),
                    DB::raw("'Overall' AS period_type")
                );
            }
            $transactions = $monthlyTransactions
                ->unionAll($quarterlyTransactions)
                ->unionAll($yearlyTransactions)
                ->unionAll($totalTransactions);



            return DataTables::of($transactions)
                ->make(true);
        }
    }

    public function supportReport()
    {
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == 'byTicketType') {
            $cd = Tickets::whereIn('support', ['Billing', 'Sales', 'Support'])->select('support')
                ->selectRaw('COUNT(*) as number_of_tickets')
                ->groupBy('support');
            return DataTables::of($cd)
                ->make(true);
        } elseif (request()->type == 'byTime') {

            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;

            $queries = [
                Tickets::select(DB::raw("'Today' AS period, COUNT(*) AS total_activities"))
                    ->whereDate('created_at', $today),

                Tickets::select(DB::raw("'Last Week' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                Tickets::select(DB::raw("'Last Month' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                Tickets::select(DB::raw("'Last Quarter' AS period, COUNT(*) AS total_activities"))
                    ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                Tickets::select(DB::raw("'Last Year' AS period, COUNT(*) AS total_activities"))
                    ->whereYear('created_at', $lastYear),

                Tickets::select(DB::raw("'Since Inception' AS period, COUNT(*) AS total_activities"))
            ];

            $unionQuery = array_shift($queries);
            foreach ($queries as $query) {
                $unionQuery->unionAll($query);
            }

            return DataTables::of($unionQuery)
                ->make(true);
        } elseif (request()->type == 'byTimeTaken') {
            $timeTaken = Tickets::select(
                DB::raw("
                        CASE
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 1 THEN '1 Day'
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 2 THEN '2 Days'
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 3 THEN '3 Days'
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 7 THEN '1 Week'
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 14 THEN '2 Weeks'
                            WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) > 15 THEN '2 Weeks+'
                            ELSE 'Longer'
                        END AS time_interval,
                        COUNT(*) AS total_tickets
                    ")
            )
            ->whereBetween('created_at',[$startDate,  $endDate])
                ->groupBy('time_interval');
                
            return DataTables::of($timeTaken)
                ->make(true);
        } elseif (request()->type == 'bySupportStaff') {
            $cd = Tickets::select(
                'user_id',
                DB::raw('COUNT(id) AS no_of_tickets_solved'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, `created_at`, `updated_at`)) / 3600 AS avg_time_taken_hours')
            )
            ->whereBetween('created_at',[$startDate,  $endDate])
                // ->where('status','Closed') // Ensure that only solved tickets are considered
                ->groupBy('user_id')
                ->get();
            return DataTables::of($cd)
                ->addColumn('username', function ($row) {
                    $user = User::find($row->user_id);
                    if (!empty($user)) {
                        return $user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        }
    }

    // -------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Refferals ----------------------------------------------------
    // -------------------------------------------------------------------------------------------------------

    public function referralsReport()
    {


        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "subscribers") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
               
            $referrals = User::where('referral_code', $user->referral)  // Fetch users who used User A's referral code
            ->whereHas('getReferrals', function ($query) {
                $query->where('type', 'Referral Commission');  // Ensure "Referral Commission" type exists
            })
            ->withCount(['getReferrals as total_referrals' => function ($query) {
                $query->where('type', 'Referral Commission');  // Count only referrals of type "Referral Commission"
            }])
            ->get();

            }
            if ($user->user_type == 'admin') {
                $referrals = User::where('user_type', 'Affiliate')
                                    ->whereHas('getReferrals', function ($query) {
                                        $query->where('referrals.type', 'Referral Commission')
                                            ->whereNull('referrals.debit_amount')
                                            ->whereHas('user', function ($subQuery) {
                                                $subQuery->where('user_type', 'Subscriber'); // Ensure referred user is a Subscriber
                                            });
                                    })
                                    ->withCount(['getReferrals as total_referrals' => function ($query) {
                                        $query->where('referrals.type', 'Referral Commission')
                                            ->whereNull('referrals.debit_amount')
                                            ->whereHas('user', function ($subQuery) {
                                                $subQuery->where('user_type', 'Subscriber'); // Ensure referred user is a Subscriber
                                            });
                                    }])
                                    ->whereBetween('created_at', [$startDate, $endDate]) // Ensure date filtering applies correctly
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            }
            return DataTables::of( $referrals)
            ->make(true);

        }
        if (request()->type == "subscriberType") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $referrals = User::whereIn('referral_code', function($query) use ($user) {
                    // Fetch all users who have used User A's referral code (User B)
                    $query->select('referral_code')
                          ->from('users')
                          ->where('referral_code', $user->referral);  // User B's referral code
                })
                    ->orWhereIn('referral_code', function($query) use ($user) {
                        // Fetch all users who have used any referral codes from users who have used User A's code
                        $query->select('referral_code')
                              ->from('users')
                              ->whereIn('referral_code', function($subQuery) use ($user) {
                                  // Dynamically fetch all users who have used User A's referral code
                                  $subQuery->select('referral_code')
                                           ->from('users')
                                           ->where('referral', $user->referral); // User A's referral code
                              });
                    })
                    ->whereBetween('users.created_at',[$startDate,  $endDate])
                    ->select('category', DB::raw('COUNT(*) as user_count'))  // Group by category and count users
                    ->groupBy('category')  // Group by category
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $referrals = DB::table('referrals')
                    ->leftJoin('users', 'referrals.userid', '=', 'users.id')
                    ->select(
                        'users.category as type',
                        DB::raw('COUNT(referrals.referral_code) as count')
                    )
                    ->where('referrals.type', 'Referral Commission')
                    ->whereBetween('users.created_at',[$startDate,  $endDate])
                    ->groupBy('users.category');
            }


            return DataTables::of($referrals)
                // ->editColumn('type', function ($row) {
                //     return !empty($row->type) ? $row->type : "N/A";
                // })
                ->make(true);
        }
        if (request()->type == "subscribedPlan") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $referralsPerMembership = User::whereIn('referral_code', function($query) use ($user) {
                    // Fetch all users who have used User A's referral code (User B)
                    $query->select('referral_code')
                          ->from('users')
                          ->where('referral_code', $user->referral);  // User B's referral code
                })
                    ->orWhereIn('referral_code', function($query) use ($user) {
                        // Fetch all users who have used any referral codes from users who have used User A's code
                        $query->select('referral_code')
                              ->from('users')
                              ->whereIn('referral_code', function($subQuery) use ($user) {
                                  // Dynamically fetch all users who have used User A's referral code
                                  $subQuery->select('referral_code')
                                           ->from('users')
                                           ->where('referral', $user->referral); // User A's referral code
                              });
                    })
                    ->whereBetween('users.created_at',[$startDate,  $endDate])
                    ->select('membership', DB::raw('COUNT(*) as user_count'))  // Group by category and count users
                    ->groupBy('membership')  // Group by category
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $referralsPerMembership = DB::table('referrals')
                    ->leftJoin('users', 'referrals.userid', '=', 'users.id')
                    ->select(
                        'users.membership as plan',
                        DB::raw('COUNT(referrals.referral_code) as count')
                    )
                    ->whereBetween('referrals.created_at',[$startDate,  $endDate])
                    ->where('referrals.type', 'Referral Commission')
                    ->groupBy('users.membership')
                    ->get();
            }


            return DataTables::of($referralsPerMembership)
               
                ->make(true);
        }
        if (request()->type == "yearly") {


            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $queries = [
                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Today' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereDate('created_at', $today),

                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Last Week' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Last Month' AS year, COUNT(*) AS count"))
                        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Last Quarter' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Last Year' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereYear('created_at', $lastYear),

                    Referrals::where('userid', $user->id)
                        ->select(DB::raw("'Since Inception' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                ];
            }
            if ($user->user_type == 'admin') {

                $queries = [
                    Referrals::select(DB::raw("'Today' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereDate('created_at', $today),

                    Referrals::select(DB::raw("'Last Week' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd]),

                    Referrals::select(DB::raw("'Last Month' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd]),

                    Referrals::select(DB::raw("'Last Quarter' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereBetween('created_at', [$lastQuarterStart, $lastQuarterEnd]),

                    Referrals::select(DB::raw("'Last Year' AS year, COUNT(*) AS count"))
                        ->where('type', 'Referral Commission')
                        ->whereYear('created_at', $lastYear),

                    Referrals::select(DB::raw("'Since Inception' AS year, COUNT(*) AS count"))
                    ->where('type', 'Referral Commission')
                ];
            }



            $unionQuery = array_shift($queries);
            foreach ($queries as $query) {
                $unionQuery->unionAll($query);
            }

            return DataTables::of($unionQuery)
                ->make(true);
        }
    }




    // -------------------------------------------------------------------------------------------------------
    // ---------------------------------------- affiliates Report ----------------------------------------------------
    // -------------------------------------------------------------------------------------------------------



    public function affiliatesReport()
    {

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "subscribersReferred") {


                $affiliates =  User::where('user_type', 'Affiliate')
                // ->withSum('affiliateTotalCommission as total_commission', 'amount_added')
                // ->having('total_commission', '>', 0)
                ->where('wallet', '>', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('getReferrals', function ($query) {
                    $query->whereNull('referrals.type') // Ensure type is null
                          ->whereNull('referrals.debit_amount'); // Ensure debit_amount is null
                })
                ->withCount(['getReferrals as total_referrals' => function($query) {
                    $query->whereNull('referrals.type')->whereNull('debit_amount');
                }])
                ->get();

            return DataTables::of($affiliates)
                ->editColumn('name', function ($row) {
                    return !empty($row->name) ? $row->name : "N/A";
                })
                ->make(true);
        } elseif (request()->type == "earnedComission") {

            $affiliates =  User::where('user_type', 'Affiliate')
                ->withSum('affiliateTotalCommission as total_commission', 'amount_added')
                ->having('total_commission', '>', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('getReferrals', function ($query) {
                    $query->whereNull('referrals.type') // Ensure type is null
                          ->whereNull('referrals.debit_amount'); // Ensure debit_amount is null
                })
                ->withCount(['getReferrals as total_referrals' => function($query) {
                    $query->whereNull('referrals.type')->whereNull('debit_amount');
                }])
                ->get();

            return DataTables::of($affiliates)
                ->editColumn('total_commission', function ($row) {
                    return round($row->total_commission,2);
                })
                ->make(true);
        } elseif (request()->type == "country") {

            $affiliates = User::select(
                'country', // Group by country
                DB::raw('COUNT(users.id) as total_users') // Count the total number of users per country
            )
            ->where('user_type', 'Affiliate') // Filter by user type 'Affiliate'
            ->whereBetween('created_at', [$startDate, $endDate]) // Filter by creation date range
            ->groupBy('country') // Group by country
            ->orderBy('total_users', 'desc') // Order by total users in descending order
            ->get();


            return DataTables::of($affiliates)
                ->editColumn('country', function ($row) {
                    return !empty($row->country) ? $row->country : "N/A";
                })
                ->make(true);
        } elseif (request()->type == "subscriberType") {



                $referralsId = User::whereBetween('created_at', [$startDate, $endDate]) // Filter by the creation date in referrals
                ->where('user_type', 'Subscriber') // Ensure the user_type is 'Subscriber'
                ->whereNotNull('referral_code') // Only include users with a referral code
                ->orderBy('created_at', 'desc') // Order by creation date
                ->pluck('id') // Get the IDs of the users
                ->toArray();

            // Group the referrals by membership and count the users
            $referrals = Referrals::whereIn('referrals.userid', $referralsId)
            ->whereNull('referrals.type') // Ensure referrals.type is NULL
            ->whereNull('referrals.debit_amount') // Ensure debit_amount is NULL
            ->with(['user' => function($query) {
                $query->select('id', 'category'); // Load only the necessary fields
            }])
            ->get();

        // Group the referrals by the user's category and count
        $groupedReferrals = $referrals->groupBy(function ($referral) {
            return $referral->user ? $referral->user->category : 'Unknown'; // Group by user category
        });
        $categoryCounts = $groupedReferrals->map(function ($items, $category) {
            return [
                'category' => $category,
                'total_users' => $items->count(),
            ];
        });
            return DataTables::of($categoryCounts)
                ->make(true);
        } elseif (request()->type == "subscribedPlan") {

            $referralsId = User::whereBetween('created_at', [$startDate, $endDate]) // Filter by the creation date in referrals
                ->where('user_type', 'Subscriber') // Ensure the user_type is 'Subscriber'
                ->whereNotNull('referral_code') // Only include users with a referral code
                ->orderBy('created_at', 'desc') // Order by creation date
                ->pluck('id') // Get the IDs of the users
                ->toArray();

            // Group the referrals by membership and count the users
            $referrals = Referrals::join('users', 'referrals.userid', '=', 'users.id') // Join referrals with users table
                ->whereIn('referrals.userid', $referralsId) // Filter referrals by the matched user IDs
                ->whereNull('referrals.type') // Ensure referrals.type is NULL
                ->whereNull('referrals.debit_amount') // Ensure debit_amount is NULL
                ->select(
                    'users.membership', // Group by membership
                    DB::raw('COUNT(users.id) as total_users') // Count the number of users per membership
                )
                ->groupBy('users.membership') // Group by membership
                ->orderBy('total_users', 'desc') // Order by the total number of users in descending order
                ->get();


            return DataTables::of( $referrals)

                ->make(true);
        } elseif (request()->type == "currentWalletCredits") {


            $affiliates =  User::where('user_type', 'Affiliate')
            ->where('wallet', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('getReferrals', function ($query) {
                $query->whereNull('referrals.type') // Ensure type is null
                      ->whereNull('referrals.debit_amount'); // Ensure debit_amount is null
            })
            ->withCount(['getReferrals as total_referrals' => function($query) {
                $query->whereNull('referrals.type')->whereNull('debit_amount');
            }])
            ->get();

        return DataTables::of($affiliates)
            ->editColumn('amount_added', function ($row) {
                return !empty($row->wallet) ? $row->wallet : "0";
            })
            ->make(true);
        }
    }
    public function demoReport(Request $request)
    {

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        $demos = DemoRequests::whereBetween('created_at', [$startDate, $endDate])->get();


        return DataTables::of($demos)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y');
            })
            ->editColumn('served_by', function ($row) {
                return 'null';
            })
            ->editColumn('service_date', function ($row) {
                return Carbon::parse($row->updated_at)->format('d-m-Y');
            })
            ->editColumn('served_by', function ($row) {
                return  $row->served_by ? $row->user->name :'';
            })
            ->editColumn('status', function ($row) {
                return ($row->status == 'true') ? 'Served' : (($row->status == 'false') ? 'Not Served' : $row->status);
            })

            ->editColumn('job_title', function ($row) {
                $text = htmlspecialchars($row->job_title);
                $words = explode(' ', $text);
                $truncated = implode(' ', array_slice($words, 0, 10)); // First 25 words

                return '<div class="message-tooltip" data-full-text="' . htmlspecialchars($text) . '">
                        <span class="hover-expand">' . $truncated . '...</span>
                    </div>';
            })
            ->rawColumns(['job_title'])
            ->make(true);
    }
    public function demoRequestReport(Request $request)
    {

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byStatus") {
            $demorequest = DB::table('demo_requests')
            ->select('status', DB::raw('COUNT(*) as status_count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

            return DataTables::of($demorequest)

                ->make(true);
        } elseif (request()->type == "byCountry") {
            $demorequest = DB::table('demo_requests')
                ->select('country', DB::raw('COUNT(*) as total_request'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('country') // Group by both country and status
                ->get();

            return DataTables::of($demorequest)

                ->make(true);
        } elseif (request()->type == "byTimeline") {

            $demorequest = DB::table('demo_requests')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status','!=', 'true') // Ensure status is appropriately filtered
            ->select(
                'country',
                DB::raw("
                    CASE
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 1 THEN '1 Day'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 2 THEN '2 Days'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 3 THEN '3 Days'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 7 THEN '1 Week'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 14 THEN '2 Weeks'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) > 14 THEN '2 Weeks+'
                        ELSE 'Longer'
                    END AS time_interval,
                    COUNT(*) AS total_tickets
                ")
            )
            ->groupBy('country', 'time_interval') // Group by country and time_interval
            ->get();
            return DataTables::of($demorequest)
            ->make(true);
        } elseif (request()->type == "byTimeTaken") {
            $demorequest = DB::table('demo_requests')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'true') // Ensure status is appropriately filtered
            ->select(
                'country',
                DB::raw("
                    CASE
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 1 THEN '1 Day'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 2 THEN '2 Days'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) = 3 THEN '3 Days'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 7 THEN '1 Week'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) <= 14 THEN '2 Weeks'
                        WHEN TIMESTAMPDIFF(DAY, `created_at`, `updated_at`) > 14 THEN '2 Weeks+'
                        ELSE 'Longer'
                    END AS time_interval,
                    COUNT(*) AS total_tickets
                ")
            )
            ->groupBy('country', 'time_interval') // Group by country and time_interval
            ->get();

            return DataTables::of( $demorequest)
            ->make(true);
        }
    }

    public function documentReport(){
        $user = auth()->user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byApplication") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
                ->join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join applications table
                ->join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                // ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                ->select(
                    'users.name as sub_name', // Subscriber name
                    'users.id as sub_id', // Subscriber ID
                    'clients.name as client_name', // Client name
                    'clients.id as client_id', // Client ID
                    'applications.application_name', // Application name
                    'applications.id as application_id', // Application ID
                    DB::raw('COUNT(client_docs.id) as no_of_docs'), // Count the number of client documents
                    DB::raw('COUNT(DISTINCT applications.id) as no_of_applications') // Count the number of unique applications
                )
                ->groupBy(
                    'users.name',
                    'users.id',
                    'clients.id',
                    'clients.name',
                    'applications.application_name',
                    'applications.id'
                ) // Group by subscriber, client, and application
                // ->havingRaw('no_of_applications = 1') // Ensure the client has documents for only one application
                // ->havingRaw('no_of_docs > 0') // Ensure the client uploaded multiple documents
                ->where('client_docs.user_id', $user->id)
                ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                ->whereNotNull('client_docs.application_id') // Ensure application_id is not null
                ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
                ->limit(20)
                ->get();
            }else{
                $documents = DB::table('client_docs')
            ->join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
            ->join('applications', 'client_docs.application_id', '=', 'applications.id') // Join applications table
            ->join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
            ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
            ->whereNotNull('client_docs.application_id') // Ensure application_id is not null
            ->select(
                'users.name as sub_name', // Subscriber name
                'users.id as sub_id', // Subscriber ID
                'clients.name as client_name', // Client name
                'clients.id as client_id', // Client ID
                'applications.application_name', // Application name
                'applications.id as application_id', // Application ID
                DB::raw('COUNT(client_docs.id) as no_of_docs'), // Count the number of client documents
                DB::raw('COUNT(DISTINCT applications.id) as no_of_applications') // Count the number of unique applications
            )
            ->groupBy(
                'users.name',
                'users.id',
                'clients.id',
                'clients.name',
                'applications.application_name',
                'applications.id'
            ) // Group by subscriber, client, and application
            // ->havingRaw('no_of_applications = 1') // Ensure the client has documents for only one application
            // ->havingRaw('no_of_docs > 0') // Ensure the client uploaded multiple documents
            ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
            ->limit(20)
            ->get();

            }


            return DataTables::of($documents)
                ->editColumn('subscriber', function ($row) {
                    return $row->sub_name . ' (' . $row->sub_id . ')';
                })
                ->editColumn('client', function ($row) {
                    return $row->client_name . ' (' . $row->client_id . ')';
                })
                ->editColumn('application', function ($row) {
                    return $row->application_name . ' (' . $row->application_id . ')';
                })
                ->make(true);
        } elseif(request()->type == "byClient"){
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
                ->join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                ->where('client_docs.user_id',$user->id)
                ->select(
                    'users.name as sub_name', // Subscriber name
                    'users.id as sub_id', // Subscriber ID
                    'clients.name as client_name', // Client name
                    'clients.id as client_id', // Client ID
                    DB::raw('COUNT(client_docs.id) as no_of_docs') // Count the number of client documents
                )
                ->groupBy(
                    'users.name',
                    'users.id',
                    'clients.id',
                    'clients.name'
                ) // Group by subscriber and client
                ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
                ->limit(20)
                ->get();
                }else{
                    $documents = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
                    ->join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                    ->select(
                        'users.name as sub_name', // Subscriber name
                        'users.id as sub_id', // Subscriber ID
                        'clients.name as client_name', // Client name
                        'clients.id as client_id', // Client ID
                        DB::raw('COUNT(client_docs.id) as no_of_docs') // Count the number of client documents
                    )
                    ->groupBy(
                        'users.name',
                        'users.id',
                        'clients.id',
                        'clients.name'
                    ) // Group by subscriber and client
                    ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
                    ->limit(20)
                    ->get();
                }

            return DataTables::of($documents)
                ->editColumn('subscriber', function ($row) {
                    return $row->sub_name . ' (' . $row->sub_id . ')';
                })
                ->editColumn('client', function ($row) {
                    return $row->client_name . ' (' . $row->client_id . ')';
                })

                ->make(true);

        } elseif(request()->type == "bySubscriber"){
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
            ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
            ->where('client_docs.user_id',$user->id)
            ->select(
                'users.name as sub_name', // Subscriber name
                'users.id as sub_id', // Subscriber ID
                DB::raw('COUNT(users.id) as no_of_docs') // Count the number of client documents
            )
            ->groupBy(
                'users.name',
                'users.id',

            ) // Group by subscriber and client
            ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
            ->limit(20)
            ->get();

            }else{
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
            ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
            ->select(
                'users.name as sub_name', // Subscriber name
                'users.id as sub_id', // Subscriber ID
                DB::raw('COUNT(users.id) as no_of_docs') // Count the number of client documents
            )
            ->groupBy(
                'users.name',
                'users.id',

            ) // Group by subscriber and client
            ->orderByDesc('no_of_docs') // Order by the number of documents uploaded
            ->limit(20)
            ->get();
            }
            return DataTables::of($documents)
                ->editColumn('subscriber', function ($row) {
                    return $row->sub_name . ' (' . $row->sub_id . ')';
                })

                ->make(true);

        } elseif(request()->type == "bySize"){
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                    ->select(
                        'client_docs.doc_file', // Document file path
                        'client_docs.client_id', // Client ID for file path construction
                        // 'users.id as user_id', // User ID
                        'users.name as user_name' // User name
                    )
                    ->where('client_docs.user_id',$user->id)
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                    ->get();

        }else{
            $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                    ->select(
                        'client_docs.doc_file', // Document file path
                        'client_docs.client_id', // Client ID for file path construction
                        // 'users.id as user_id', // User ID
                        'users.name as user_name' // User name
                    )
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                    ->get();
                    $filesWithSize = [];

            }
            $filesWithSize=[];
            foreach ($documents as $doc) {
                // Construct the file path
                $filePath = public_path('web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file);

                // Check if the file exists
                if (file_exists($filePath)) {
                    // Get file size in bytes
                    $fileSize = filesize($filePath);

                    // Add the document and its size to the array
                    $filesWithSize[] = [
                        'user_name' => $doc->user_name,
                        'doc_file' => $doc->doc_file,
                        'file_size' => $fileSize, // Store size in bytes for sorting
                        'formatted_size' => $fileSize < 1024
                            ? $fileSize . ' B'
                            : ($fileSize < 1048576
                                ? round($fileSize / 1024, 2) . ' KB'
                                : ($fileSize < 1073741824
                                    ? round($fileSize / 1048576, 2) . ' MB'
                                    : round($fileSize / 1073741824, 2) . ' GB')),
                    ];
                }
            }

            // Sort files by size (highest to lowest)
            usort($filesWithSize, function ($a, $b) {
                return $b['file_size'] <=> $a['file_size'];
            });
            $topFiles = array_slice($filesWithSize, 0, 50);
            return DataTables::of($topFiles)
            ->addIndexColumn()
            ->make(true);

        } elseif(request()->type == "byFiletype"){

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
            ->select(
                'client_docs.doc_file', // Document file path
                'client_docs.client_id', // Client ID for file path construction
                'users.name as user_name', // User name
                DB::raw("SUBSTRING_INDEX(client_docs.doc_file, '.', -1) as file_type") // Extract file type
            )
            ->where('client_docs.user_id',$user->id)
            ->whereBetween('client_docs.created_at', [$startDate, $endDate])
            ->get();

        }else{
            $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
            ->select(
                'client_docs.doc_file', // Document file path
                'client_docs.client_id', // Client ID for file path construction
                'users.name as user_name', // User name
                DB::raw("SUBSTRING_INDEX(client_docs.doc_file, '.', -1) as file_type") // Extract file type
            )
            ->whereBetween('client_docs.created_at', [$startDate, $endDate])
            ->get();

        }
        $filesWithType = [];
        foreach ($documents as $doc) {
            // Construct the file path
            $filePath = public_path('web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file);

            // Check if the file exists
            if (file_exists($filePath)) {
                // Get the file type
                $fileType = pathinfo($filePath, PATHINFO_EXTENSION);

                // Add the document and its file type to the array
                $filesWithType[] = [
                    'user_name' => $doc->user_name,
                    'doc_file' => $doc->doc_file,
                    'file_type' => strtolower($fileType), // Normalize to lowercase for grouping
                ];
            }
        }

        // Group files by file type and count each type
        $fileTypeCounts = collect($filesWithType)
            ->groupBy('file_type')
            ->map(function ($files, $type) {
                return [
                    'file_type' => $type,
                    'total_count' => count($files)
                ];
            });
            return DataTables::of($fileTypeCounts)
                ->make(true);

        }



    }



}
