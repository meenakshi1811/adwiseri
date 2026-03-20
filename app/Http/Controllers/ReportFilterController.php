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
        $query =  Clients::join('users', 'referrals.userid', '=', 'users.id')
        ->where('users.user_type', 'Subscriber')
        ->whereNull('referrals.debit_amount')
        ->where('referrals.type', 'Referral Commission');

        if (request()->type == "homeCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $clientsByCountry = Clients::whereBetween('created_at', [$startDate, $endDate])->where('subscriber_id', '=', $user->id)->select('country as country_name', DB::raw('COUNT(subscriber_id) as No_of_Subscribers'))
                    ->groupBy('country')->get();
            } else {
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
                        SELECT "18-24" UNION ALL 
                        SELECT "25-34" UNION ALL 
                        SELECT "35-44" UNION ALL 
                        SELECT "45-55" UNION ALL 
                        SELECT "55 +") AS age_groups'))
                    ->leftJoinSub(
                        Clients::whereBetween('created_at', [$startDate, $endDate])
                            ->where('subscriber_id', '=', $user->id)
                            ->select(
                                DB::raw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-24'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-34'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-44'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                        ELSE '55 +'
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
                WHEN age_groups.age_group = '18-24' THEN 2
                WHEN age_groups.age_group = '25-34' THEN 3
                WHEN age_groups.age_group = '35-44' THEN 4
                WHEN age_groups.age_group = '45-55' THEN 5
                WHEN age_groups.age_group = '55 +' THEN 6
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
                SELECT "55 +") AS age_groups'))
                    ->leftJoinSub(
                        Clients::whereBetween('created_at', [$startDate, $endDate])
                            ->select(
                                DB::raw("
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-24'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-34'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-44'
                    WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                    ELSE '55 +'
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
            WHEN age_groups.age_group = '18-24' THEN 2
            WHEN age_groups.age_group = '25-34' THEN 3
            WHEN age_groups.age_group = '35-44' THEN 4
            WHEN age_groups.age_group = '45-55' THEN 5
            WHEN age_groups.age_group = '55 +' THEN 6
            END
            ") // Ensures proper sorting
                    ->get();
            }

            if ($clients->sum('count') === 0) {
                return DataTables::of(collect())->make(true); // return empty collection
            }
            
            return DataTables::of($clients)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'appType') {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $applications = Applications::whereBetween('created_at', [$startDate, $endDate])
                    ->where('subscriber_id', $user->id)
                    ->selectRaw(
                        'CONCAT(application_name, " (", application_id, ")") as application_name, COUNT(DISTINCT client_id) AS number_of_clients'
                    )
                    ->groupBy('application_id', 'application_name') // Ensure correct grouping
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $applications = Applications::whereBetween('created_at', [$startDate, $endDate])->selectRaw(
                    'CONCAT(application_name, " (", application_id, ")") as application_name, COUNT(DISTINCT client_id) AS number_of_clients'
                    )
                    ->groupBy('application_id', 'application_name') // Ensure correct grouping
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
            $paymentMode = PaymentARs::pluck('payment_mode')->toArray();
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
                        'application' // Eager load application name
                    ])
                    ->select(
                        'client_id',
                        'application_id',
                        'service_description',
                        DB::raw('MAX(created_at) as created_at'), // ✅ Use MAX() to avoid GROUP BY error
                        DB::raw('SUM(amount - paid_amount) as amount_to_pay') // ✅ Aggregate sum correctly
                    )
                    ->groupBy('client_id', 'application_id', 'service_description') // ✅ Group by necessary columns
                    ->havingRaw('SUM(amount - paid_amount) > 0')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            if ($user->user_type == 'admin') {
                $paymentAP = PaymentARs::where('type', 'ar')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->whereNotNull('client_id') // Ensure there is a client_id
                    ->with([
                        'client', // Eager load client name
                        'application' // Eager load application name
                    ])
                    ->select(
                        'client_id',
                        'application_id',
                        'service_description',
                        DB::raw('MAX(created_at) as created_at'), // ✅ Use MAX() to avoid GROUP BY error
                        DB::raw('SUM(amount - paid_amount) as amount_to_pay') // ✅ Aggregate sum correctly
                    )
                    ->groupBy('client_id', 'application_id', 'service_description') // ✅ Group by necessary columns
                    ->havingRaw('SUM(amount - paid_amount) > 0')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }


            return DataTables::of($paymentAP)
                ->addIndexColumn()
                ->addColumn('application_name', function ($row) {
                    return $row->application
                        ? $row->application->application_name . '(' . $row->application->application_id . ')'  // ✅ Use application name if exists
                        : ($row->service_description ?? 'N/A'); // ✅ Otherwise, use service_description
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client
                        ? $row->client->name . ' (' . $row->client_id . ')'
                        : 'Unknown Client'; // ✅ Prevents undefined error
                })
                // ->addColumn('amount_to_pay', function ($row) {
                //     return  number_format((float) ($row->amount - $row->paid_amount));
                // })
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
                    ->orderBy('no_of_docs', 'desc')
                    ->get();
            }
            if ($user->user_type == 'admin') {

                $clientDocs = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join Client model
                    ->selectRaw('clients.name as client_name, client_docs.client_id, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
                    ->groupBy('client_docs.client_id', 'clients.name') // Group by client_id and client name
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                    ->orderBy('no_of_docs', 'desc')
                    ->get();
            }


            return  DataTables::of($clientDocs)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->client ? $row->client->name . '(' . $row->client_id . ')' : '';
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
                    return  $row->name . '(' . $row->id . ')';
                })

                ->make(true);;
        } else if (request()->type == "byTimeline") {

            $query =  new Clients;

            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query = $query->where('user_id',$user->id);
              
            }

            if ($user->user_type == 'admin') {
                $yearlyClients = $query;
            }

       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('clients.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('clients.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('clients.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('clients.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('clients.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count clients without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);

            // $currentYear = date('Y'); // Get the current year

            // // Base query with date range
            // $query = null;

               
            // $query1 = null;

            // // Filter for specific user types
            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //    $query = Clients::where('user_id', $user->id);
            //    $query1 = Clients::where('user_id', $user->id);
            // }

            // if ($user->user_type == 'admin') {
            //     $query = new Clients;
            //     $query1 = new Clients;
            // }
            
            // // Past year data query
            // $pastYearData = $query1->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //     ->groupByRaw("YEAR(created_at)")
            //     ->orderBy('type', 'desc')
            //     ->get();

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;

            // // Weekly Applications
            // $weeklyApplications = $weeklyApplications->selectRaw("
            //     WEEK(created_at) as week_num, 
            //     YEAR(created_at) as year_num, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("WEEK(created_at), YEAR(created_at)")
            //     ->orderByRaw("YEAR(created_at) DESC, WEEK(created_at) DESC")
            //     ->get();

            // // Quarterly Applications
            // $quarterlyApplications = $quarterlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     QUARTER(created_at) as quarter, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), QUARTER(created_at)") // Group by YEAR and QUARTER separately
            //     ->orderByRaw("YEAR(created_at) DESC, QUARTER(created_at) DESC") // Correct order
            //     ->get();

            // // Monthly Applications
            // $monthlyApplications = $monthlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     MONTH(created_at) as month, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), MONTH(created_at)") // Group by YEAR and MONTH
            //     ->orderByRaw("YEAR(created_at) DESC, MONTH(created_at) DESC")
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`, `year_num`, `quarter`, etc.
            // // $formattedData = $formattedData->sortByAsc(function ($item) {
            // //     return isset($item['year_num']) ? intval($item['year_num']) : (isset($item['quarter']) ? intval($item['quarter']) : (isset($item['year']) ? intval($item['year']) : 0));
            // // })->values();

            // // Map and format the data
            // // Sorting by ascending order
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     $sort_value = 0;
            
            //     if (isset($item['quarter'])) {
            //         // Format quarterly data as Q1 2025, Q2 2025, etc.
            //         $item['type'] = 'Q' . $item['quarter'] . ' ' . $item['year'];
            //         $sort_value = (int)$item['year'] * 10 + (int)$item['quarter']; // Sort by year and quarter
            //     } elseif (isset($item['year_num'])) {
            //         // Format weekly data as Week 1 2025, Week 2 2025, etc.
            //         $item['type'] = 'Week ' . $item['week_num'] . ' ' . $item['year_num'];
            //         $sort_value = (int)$item['year_num'] * 100 + (int)$item['week_num']; // Sort by year and week
            //     } elseif (isset($item['month'])) {
            //         // Format monthly data as January 2025, February 2025, etc.
            //         $item['type'] = date('F Y', mktime(0, 0, 0, $item['month'], 1, $item['year']));
            //         $sort_value = strtotime($item['type']); // Use timestamp for month sorting
            //     } elseif (is_numeric($item['type'])) {
            //         // If it's a year, sort by the year directly
            //         $sort_value = (int)$item['type'];
            //     } else {
            //         // Default fallback for other types
            //         $item['type'] = $item['type'] ?? 'Unknown';
            //     }
            
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count'],
            //         'sort_value' => $sort_value, // Add sort_value for sorting
            //     ];
            // });
            

            // // Sort by type in ascending order (Weekly first, then Monthly, Quarterly, and Past Year)
           

            // // Return the formatted data for DataTables

            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $yearlyClients = Clients::where('subscriber_id', $user->id)->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyClients = Clients::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyClients)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
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

                $applicationsByVisaCountry = Applications::select('visa_country as country', DB::raw('count(*) as application_count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('subscriber_id', $user->id)
                    ->groupBy('country')->get();
            }
            if ($user->user_type == 'admin') {
                $applicationsByVisaCountry = Applications::select('visa_country as country', DB::raw('count(*) as application_count'))
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('country')->get();
            }


            return DataTables::of($applicationsByVisaCountry)
                ->addIndexColumn()

                ->rawColumns(['name', 'subscriber'])
                ->make(true);
        } elseif (request()->type == "applicationCountry") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applicationsByApplicationCountry = Applications::where('subscriber_id', $user->id)
                    ->select('application_country as country', DB::raw('count(*) as application_count'))
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

            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
            //     $applications = Applications::whereBetween('created_at', [$startDate, $endDate])
            //         ->where('subscriber_id', $user->id)
            //         ->selectRaw(
            //             'CONCAT(application_name, " (", application_id, ")") as application_name, COUNT(DISTINCT client_id) AS number_of_clients'
            //         )
            //         ->groupBy('application_id', 'application_name') // Ensure correct grouping
            //         ->get();
            // }
            // if ($user->user_type == 'admin') {
            //     $applications = Applications::whereBetween('created_at', [$startDate, $endDate])->selectRaw(
            //         'CONCAT(application_name, " (", application_id, ")") as application_name, COUNT(DISTINCT client_id) AS number_of_clients'
            //         )
            //         ->groupBy('application_id', 'application_name') // Ensure correct grouping
            //             ->get();
            // }
            // return DataTables::of($applications)
            //     ->addIndexColumn()
            //     ->make(true);

            $query = new Applications();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query =  $query->where('subscriber_id', $user->id);
                    
            }

            if ($user->user_type == 'admin') {
                $query =  $query;
            }
            $applicationsByApplicationType = $query
            ->select('applications.application_name', DB::raw('COUNT(*) as application_count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('applications.application_name')
            ->orderByDesc('application_count')
            ->get();
            
            return  DataTables::of($applicationsByApplicationType)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "noOfApplicaitonsPerApplication") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                // $applicationsByDependants = Applications::where('subscriber_id', $user->id)->select('application_name as applicationName', DB::raw('count(*) as application_count'))
                // ->whereBetween('created_at', [$startDate, $endDate])
                // ->groupBy('applicationName')->get();
                $applicationsByDependants = Applications::join('clients', 'applications.client_id', '=', 'clients.id') // Join with clients
                    ->leftJoin('dependants', 'clients.id', '=', 'dependants.client_id') // Left join with dependants
                    ->where('applications.subscriber_id', $user->id)
                    ->whereBetween('applications.created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('CONCAT(applications.application_name, " (", applications.application_id, ")") as applicationName'), // Concatenating name and ID
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NULL THEN 1 ELSE 0 END) as single'), // Clients with 0 dependants
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NOT NULL THEN 1 ELSE 0 END) as joint') // Clients with 1 or more dependants
                    )
                    ->groupBy('applications.application_id', 'applications.application_name') // Corrected GROUP BY
                    ->orderBy('applications.application_name') // Order by application name
                    ->get();
            }
            if ($user->user_type == 'admin') {

                $applicationsByDependants = Applications::join('clients', 'applications.client_id', '=', 'clients.id') // Join with clients
                    ->leftJoin('dependants', 'clients.id', '=', 'dependants.client_id') // Left join with dependants
                    ->whereBetween('applications.created_at', [$startDate, $endDate])
                    ->select(
                        DB::raw('CONCAT(applications.application_name, " (", applications.application_id, ")") as applicationName'), // Concatenating name and ID
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NULL THEN 1 ELSE 0 END) as single'), // Clients with 0 dependants
                        DB::raw('SUM(CASE WHEN dependants.client_id IS NOT NULL THEN 1 ELSE 0 END) as joint') // Clients with 1 or more dependants
                    )
                    ->groupBy('applications.application_id', 'applications.application_name') // Corrected GROUP BY
                    ->orderBy('applications.application_name') // Order by application name
                    ->get();
            }
            return  DataTables::of($applicationsByDependants)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "paymentMode") {
            $paymentMode = PaymentARs::pluck('payment_mode')->toArray();
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

            $query = PaymentARs::where('type', 'ar')
                                ->whereNotNull('client_id')
                                ->whereBetween('created_at', [$startDate, $endDate]);
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
               $query = $query->where('subscriber_id', $user->id);
                                
            }

              $paymentAP = $query->with([
                                'client', // Eager load client name
                                'application' // Eager load application name
                            ])
                            ->select(
                                'client_id',
                                'application_id',
                                'service_description',
                                DB::raw('MAX(created_at) as created_at'), // ✅ Use MAX() to avoid GROUP BY error
                                DB::raw('SUM(amount - paid_amount) as amount_to_pay') // ✅ Aggregate sum correctly
                            )
                            ->groupBy('client_id', 'application_id', 'service_description') // ✅ Group by necessary columns
                            ->havingRaw('SUM(amount - paid_amount) > 0')
                            ->orderBy('created_at', 'desc')
                            ->get();
            // if ($user->user_type == 'admin') {
            //     $paymentAP = PaymentARs::where('type', 'ar')
            //         ->whereBetween('created_at', [$startDate, $endDate])
            //         ->whereNotNull('client_id') // Ensure there is a client_id
            //         ->with([
            //             'client', // Eager load client name
            //             'application' // Eager load application name
            //         ])
            //         ->select(
            //             'client_id',
            //             'application_id',
            //             'service_description',
            //             DB::raw('MAX(created_at) as created_at'), // ✅ Use MAX() to avoid GROUP BY error
            //             DB::raw('SUM(amount - paid_amount) as amount_to_pay') // ✅ Aggregate sum correctly
            //         )
            //         ->groupBy('client_id', 'application_id', 'service_description') // ✅ Group by necessary columns
            //         ->havingRaw('SUM(amount - paid_amount) > 0')
            //         ->orderBy('created_at', 'desc')
            //         ->get();
            // }


            return DataTables::of($paymentAP)
                ->addIndexColumn()
                ->addColumn('application_name', function ($row) {
                    return $row->application
                        ? $row->application->application_name . '(' . $row->application->application_id . ')'  // ✅ Use application name if exists
                        : ($row->service_description ?? 'N/A'); // ✅ Otherwise, use service_description
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client
                        ? $row->client->name . ' (' . $row->client_id . ')'
                        : 'Unknown Client'; // ✅ Prevents undefined error
                })

                ->addColumn('due_date', function ($row) {
                    return \Carbon\Carbon::parse($row->payment_date)->format('d-m-Y');
                })

                ->make(true);
        } elseif (request()->type == "documentStored") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $applicationsByDocumentStored = Client_Docs::join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join Client model
                    ->selectRaw(
                        'CONCAT(applications.application_name, " (", applications.application_id, ")") as name, COUNT(*) AS no_of_docs' // Corrected CONCAT and COUNT syntax
                    )
                    ->groupBy('client_docs.application_id', 'applications.application_name') // Group by application_id and application_name
                    ->whereNotNull('client_docs.application_id') // Ensure application_id is not null in Client_Docs table
                    ->where('client_docs.user_id', $user->id)
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Date range filter
                    ->orderBy('no_of_docs', 'desc') // Order by the count of documents in descending order
                    ->get();
            }
            if ($user->user_type == 'admin') {

                $applicationsByDocumentStored = Client_Docs::join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join Client model
                    ->selectRaw('CONCAT(applications.application_name, " (", applications.application_id, ")") as name, COUNT(*) AS no_of_docs') // Select client name, client_id, and count of documents
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
            return  DataTables::of($data)
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

            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            //     $query = $query->where('referrals.referral_code',$user->referral);
              
            // }

             $query = new Applications;

               
            $query1 = new Applications;

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               $query = $query->where('subscriber_id', $user->id);
               $query1 = $query1->where('subscriber_id', $user->id);
            }

            if($user->user_type == 'admin') {
                $query = $query;
               $query1 = $query1;
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('applications.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('applications.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('applications.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('applications.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('applications.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count applications without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);
            // $currentYear = date('Y'); // Get the current year
            // $query = Applications::query()
            //     ->whereBetween('created_at', [$startDate, $endDate])
            //     ->whereYear('created_at', $currentYear);

            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //     $query->where('subscriber_id', $user->id);
            //     $pastYearData = Applications::query()
            //         ->whereYear('created_at', '<', $currentYear)
            //         ->where('subscriber_id', $user->id)
            //         ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //         ->groupBy('type')
            //         ->orderBy('type', 'desc')
            //         ->get();
            // } else {
            //     $pastYearData = Applications::query()
            //         ->whereYear('created_at', '<', $currentYear)
            //         ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //         ->groupBy('type')
            //         ->orderBy('type', 'desc')
            //         ->get();
            // }

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;


            // // Fetch grouped data
            // $weeklyApplications = $weeklyApplications->selectRaw("CONCAT('Week ', WEEK(created_at), ' (', YEAR(created_at), ')') as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // $quarterlyApplications = $quarterlyApplications->selectRaw("CONCAT('Q', QUARTER(created_at), ' ', YEAR(created_at)) as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // $monthlyApplications = $monthlyApplications->selectRaw("DATE_FORMAT(created_at, '%M %Y') as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`
            // $formattedData = $formattedData->sortByDesc(function ($item) {
            //     return intval(preg_replace('/\D/', '', $item['type']));
            // })->values();

            // // Assign `DT_RowIndex` after sorting
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count']
            //     ];
            // });


            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyApplications = Applications::where('subscriber_id', $user->id)->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyApplications = Applications::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyApplications)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
        }
    }
    public function usersReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byRole") {
            $query = new User();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('added_by', $user->id);
            }
            $userFetch = $query->where('user_type', 'User')
                ->whereNotNull('designation')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('designation', DB::raw('count(*) as users'))
                ->groupBy('designation')->get();

            return DataTables::of($userFetch)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "ageGroup") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $user = DB::table(DB::raw('(SELECT "Under 18" AS age_group UNION ALL 
                        SELECT "18-24" UNION ALL 
                        SELECT "25-34" UNION ALL 
                        SELECT "35-44" UNION ALL 
                        SELECT "45-55" UNION ALL 
                        SELECT "55 +") AS age_groups'))
                    ->leftJoinSub(
                        User::whereBetween('created_at', [$startDate, $endDate])
                            ->where('id', '=', $user->id)
                            ->select(
                                DB::raw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-24'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-34'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-44'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                        ELSE '55 +'
                    END AS age_group,
                    COUNT(*) AS count
                ")
                            )
                            ->groupBy('age_group'),
                        'user',
                        'age_groups.age_group',
                        '=',
                        'user.age_group'
                    )
                    ->select('age_groups.age_group', DB::raw('COALESCE(user.count, 0) AS count'))
                    ->orderByRaw("
                CASE 
                WHEN age_groups.age_group = 'Under 18' THEN 1
                WHEN age_groups.age_group = '18-24' THEN 2
                WHEN age_groups.age_group = '25-34' THEN 3
                WHEN age_groups.age_group = '35-44' THEN 4
                WHEN age_groups.age_group = '45-55' THEN 5
                WHEN age_groups.age_group = '55 +' THEN 6
                END
                ") // Ensures proper sorting
                    ->get();       
            }else{
                $user = DB::table(DB::raw('(SELECT "Under 18" AS age_group UNION ALL 
                        SELECT "18-24" UNION ALL 
                        SELECT "25-34" UNION ALL 
                        SELECT "35-44" UNION ALL 
                        SELECT "45-55" UNION ALL 
                        SELECT "55 +") AS age_groups'))
                    ->leftJoinSub(
                        User::whereBetween('created_at', [$startDate, $endDate])
                            ->select(
                                DB::raw("
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-24'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-34'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-44'
                        WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
                        ELSE '55 +'
                    END AS age_group,
                    COUNT(*) AS count
                ")
                            )
                            ->groupBy('age_group'),
                        'user',
                        'age_groups.age_group',
                        '=',
                        'user.age_group'
                    )
                    ->select('age_groups.age_group', DB::raw('COALESCE(user.count, 0) AS count'))
                    ->orderByRaw("
                CASE 
                WHEN age_groups.age_group = 'Under 18' THEN 1
                WHEN age_groups.age_group = '18-24' THEN 2
                WHEN age_groups.age_group = '25-34' THEN 3
                WHEN age_groups.age_group = '35-44' THEN 4
                WHEN age_groups.age_group = '45-55' THEN 5
                WHEN age_groups.age_group = '55 +' THEN 6
                END
                ") // Ensures proper sorting
                    ->get();
            }

            if ($user->sum('count') === 0) {
                return DataTables::of(collect())->make(true); // return empty collection
            }
            

            return DataTables::of($user)
                ->addIndexColumn()
                ->make(true);
            // $ageGroups = collect(["Under 18", "18-24", "25-34", "35-44", "45-55", "55 +"]);

            // $query = User::where('user_type', 'User')
            //     ->whereBetween('created_at', [$startDate, $endDate]);
            
            // if ($user->user_type === 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //     $query->where('added_by', $user->id);
            // }
            
            // $ageData = $query->selectRaw("
            //         CASE 
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 18 THEN 'Under 18'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-24'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '25-34'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '35-44'
            //             WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '45-55'
            //             ELSE '55 +'
            //         END AS age_group, COUNT(*) AS count
            //     ")
            //     ->groupBy('age_group')
            //     ->pluck('count', 'age_group');
            
            // // Ensure all age groups exist, even if there’s no data
            // $users = $ageGroups->map(fn($group) => ['age_group' => $group, 'count' => $ageData[$group] ?? 0])
            //     ->values();

            // return DataTables::of($users)
            //     ->addIndexColumn()
            //     ->make(true);
        } else if (request()->type == "applicationProcessed") {
            $query = new Application_assignments();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query =   $query->where('subscriber_id', $user->id);
            }
            $applicationProcessed =  $query->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('user', function ($query) {
                    $query->where('user_type', 'User');
                })->with('user')->select('user_id', DB::raw("COUNT(*) AS count"))->groupBy('user_id')->get();

            return DataTables::of($applicationProcessed)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user_id ? $row->user->name . '(' . $row->user->id . ')' : '';
                })
                ->make(true);
        } else if (request()->type == "meetingNotes") {
            $query = new Client_discussions();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('subscriber_id', $user->id);
            }
            $meetingNotes = $query->whereHas('user', function ($query) {
                $query->where('user_type', 'User'); 
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user.id')
            ->map(function ($items) {
                return [
                    'username' => $items->first()->user->name . ' (' . $items->first()->user->id . ')',
                    'communication_summary' => $items->groupBy('communication_type')
                        ->map(fn($group, $type) => "$type (" . $group->count() . ")")
                        ->implode(', '),
                    'total_discussions' => $items->count()
                ];
            })->values();
            return DataTables::of($meetingNotes)
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "communication") {
            // $ageGroup = Client_discussions::select('communication_type', DB::raw('count(user_id) as user_id'))->groupBy('communication_type');

            $query = DB::table('client_discussions')
                        ->join('users', 'client_discussions.user_id', '=', 'users.id')
                        ->where('users.user_type', 'User')
                        ->whereBetween('client_discussions.created_at', [$startDate, $endDate])
                        ->select(
                            'client_discussions.communication_type',
                            DB::raw("COUNT(DISTINCT client_discussions.user_id) AS total_users"),
                            DB::raw("COUNT(*) AS total_discussions")
                        );
                       

            // Apply additional filtering based on user type
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('subscriber_id', $user->id);
            }

            $data = $query->groupBy('client_discussions.communication_type')
            ->get();;

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "message") {
            $query = Internal_communications::join('users', function ($join) {
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
                ->orderBy('users.name', 'asc');

            // Apply filtering only if the user is a Subscriber with specific memberships
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('internal_communications.subscriber_id', $user->id);
            }

            $messagesCount = $query->get();


            return DataTables::of($messagesCount)
                ->addIndexColumn()
                ->editColumn('user_id', function ($row) {
                    $user = User::find($row->user_id);
                    return  $user ? $user->name . '(' . $user->id . ')' : '';
                })
                ->make(true);
        } else if (request()->type == "users") {

            $currentYear = date('Y'); // Get the current year
            $query = Clients::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereYear('created_at', $currentYear);

            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('subscriber_id', $user->id);
                $pastYearData = Clients::query()
                    ->whereYear('created_at', '<', $currentYear)
                    ->where('subscriber_id', $user->id)
                    ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
                    ->groupBy('type')
                    ->orderBy('type', 'desc')
                    ->get();
            } else {
                $pastYearData = Clients::query()
                    ->whereYear('created_at', '<', $currentYear)
                    ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
                    ->groupBy('type')
                    ->orderBy('type', 'desc')
                    ->get();
            }

            // Queries for different time groupings
            $weeklyApplications = clone $query;
            $quarterlyApplications = clone $query;
            $monthlyApplications = clone $query;


            // Fetch grouped data
            $weeklyApplications = $weeklyApplications->selectRaw("CONCAT('Week ', WEEK(created_at), ' (', YEAR(created_at), ')') as type, COUNT(*) as count")
                ->groupByRaw('type')
                ->get();

            $quarterlyApplications = $quarterlyApplications->selectRaw("CONCAT('Q', QUARTER(created_at), ' ', YEAR(created_at)) as type, COUNT(*) as count")
                ->groupByRaw('type')
                ->get();

            $monthlyApplications = $monthlyApplications->selectRaw("DATE_FORMAT(created_at, '%M %Y') as type, COUNT(*) as count")
                ->groupByRaw('type')
                ->get();

            // Merge all data into a single collection
            $formattedData = collect()
                ->merge($weeklyApplications)
                ->merge($quarterlyApplications)
                ->merge($monthlyApplications)
                ->merge($pastYearData);

            // Sort correctly by numeric value in `type`
            $formattedData = $formattedData->sortByDesc(function ($item) {
                return intval(preg_replace('/\D/', '', $item['type']));
            })->values();

            // Assign `DT_RowIndex` after sorting
            $formattedData = $formattedData->map(function ($item, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'type' => $item['type'],
                    'count' => $item['count']
                ];
            });


            return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyUser = User::selectRaw('YEAR(users.created_at) as year, COUNT(*) as year_count')->where('added_by', $user->id)->where('user_type', 'User')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyUser = User::selectRaw('YEAR(users.created_at) as year, COUNT(*) as year_count')->where('added_by', $user->id)->where('user_type', 'User')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyUser)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
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
          $query = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
            ->where('total', '>', '0')->where('type', 'ar');

        // Check if user is a Subscriber with a specific membership
        if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") 
            && $user->user_type == 'Subscriber') {
            $query = $query->where('user_id', $user->id); // Apply condition for Subscribers only
        }

        // Select the amount range and count the number of invoices
        $invoices = $query->select(
            DB::raw("
                CASE 
                    WHEN total BETWEEN 1 AND 99 THEN '1-99'
                    WHEN total BETWEEN 100 AND 249 THEN '100-249'
                    WHEN total BETWEEN 250 AND 499 THEN '250-499'
                    WHEN total BETWEEN 500 AND 999 THEN '500-999'
                    WHEN total BETWEEN 1000 AND 2499 THEN '1000-2499'
                    WHEN total BETWEEN 2500 AND 4999 THEN '2500-4999'
                    WHEN total BETWEEN 5000 AND 9999 THEN '5000-9999'
                    WHEN total >= 10000 THEN '10,000+'
                END AS amount_range
            ")
        )
        ->selectRaw('COUNT(*) as number_of_invoices, SUM(total) as total_amount')
        ->groupBy('amount_range')
        ->orderByRaw("FIELD(amount_range, '1-99', '100-249', '250-499', '500-999', '1000-2499', '2500-4999', '5000-9999', '10,000+') ASC") // Change DESC to ASC
        ->get();

            // Debugging Output

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byType") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query  = $query->where('subscriber_id', $user->id)->where('type', 'ar');
            }

            $invoice_interval =  $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('status')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('status')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byClient") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query =  $query->where('user_id', $user->id)->where('type', 'ar');
            }
            $invoice_interval = $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('country')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('country')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byVisaCountry") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query =  $query->where('user_id', $user->id)->where('type', 'ar');
            }
            $invoice_interval =  $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('to_country')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('to_country')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "byTimeLine") {

            
            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            //     $query = $query->where('referrals.referral_code',$user->referral);
              
            // }

             $query = new Internal_Invoices;

               
            $query1 = new Internal_Invoices;

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               $query = $query->where('user_id', $user->id)->where('type', 'ar');
               $query1 = $query1->where('user_id', $user->id)->where('type', 'ar');
            }

            if($user->user_type == 'admin') {
                $query = $query->where('type', 'ar');
               $query1 = $query1->where('type', 'ar');
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('internal_invoices.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('internal_invoices.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count internal_invoices without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);

            // $currentYear = date('Y'); // Get the current year

            // // Base query with date range
            // $query = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
            //     ->whereYear('created_at', $currentYear);

               
            // $query1 = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate]);

            // // Filter for specific user types
            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //    $query = $query->where('user_id', $user->id);
            //    $query1 = $query1->where('user_id', $user->id);
            // }
            // // Past year data query
            // $pastYearData = $query1->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //     ->groupByRaw("YEAR(created_at)")
            //     ->orderBy('type', 'desc')
            //     ->get();

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;

            // // Weekly Applications
            // $weeklyApplications = $weeklyApplications->selectRaw("
            //     WEEK(created_at) as week_num, 
            //     YEAR(created_at) as year_num, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("WEEK(created_at), YEAR(created_at)")
            //     ->orderByRaw("YEAR(created_at) DESC, WEEK(created_at) DESC")
            //     ->get();

            // // Quarterly Applications
            // $quarterlyApplications = $quarterlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     QUARTER(created_at) as quarter, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), QUARTER(created_at)") // Group by YEAR and QUARTER separately
            //     ->orderByRaw("YEAR(created_at) DESC, QUARTER(created_at) DESC") // Correct order
            //     ->get();

            // // Monthly Applications
            // $monthlyApplications = $monthlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     MONTH(created_at) as month, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), MONTH(created_at)") // Group by YEAR and MONTH
            //     ->orderByRaw("YEAR(created_at) DESC, MONTH(created_at) DESC")
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`, `year_num`, `quarter`, etc.
            // // $formattedData = $formattedData->sortByAsc(function ($item) {
            // //     return isset($item['year_num']) ? intval($item['year_num']) : (isset($item['quarter']) ? intval($item['quarter']) : (isset($item['year']) ? intval($item['year']) : 0));
            // // })->values();

            // // Map and format the data
            // // Sorting by ascending order
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     $sort_value = 0;
            
            //     if (isset($item['quarter'])) {
            //         // Format quarterly data as Q1 2025, Q2 2025, etc.
            //         $item['type'] = 'Q' . $item['quarter'] . ' ' . $item['year'];
            //         $sort_value = (int)$item['year'] * 10 + (int)$item['quarter']; // Sort by year and quarter
            //     } elseif (isset($item['year_num'])) {
            //         // Format weekly data as Week 1 2025, Week 2 2025, etc.
            //         $item['type'] = 'Week ' . $item['week_num'] . ' ' . $item['year_num'];
            //         $sort_value = (int)$item['year_num'] * 100 + (int)$item['week_num']; // Sort by year and week
            //     } elseif (isset($item['month'])) {
            //         // Format monthly data as January 2025, February 2025, etc.
            //         $item['type'] = date('F Y', mktime(0, 0, 0, $item['month'], 1, $item['year']));
            //         $sort_value = strtotime($item['type']); // Use timestamp for month sorting
            //     } elseif (is_numeric($item['type'])) {
            //         // If it's a year, sort by the year directly
            //         $sort_value = (int)$item['type'];
            //     } else {
            //         // Default fallback for other types
            //         $item['type'] = $item['type'] ?? 'Unknown';
            //     }
            
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count'],
            //         'sort_value' => $sort_value, // Add sort_value for sorting
            //     ];
            // });
            

            // // Sort by type in ascending order (Weekly first, then Monthly, Quarterly, and Past Year)
           

            // // Return the formatted data for DataTables

            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyInternal_Invoices = Internal_Invoices::where('subscriber_id', $user->id)->where('type', 'ar')->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyInternal_Invoices = Internal_Invoices::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->where('type', 'ar')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyInternal_Invoices)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
        }
        
    }

    public function invoicesReport_ap()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byAmount") {
          $query = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
            ->where('total', '>', '0')->where('type', 'ap');

        // Check if user is a Subscriber with a specific membership
        if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") 
            && $user->user_type == 'Subscriber') {
            $query = $query->where('user_id', $user->id); // Apply condition for Subscribers only
        }

        // Select the amount range and count the number of invoices
        $invoices = $query->select(
            DB::raw("
                CASE 
                    WHEN total BETWEEN 1 AND 99 THEN '1-99'
                    WHEN total BETWEEN 100 AND 249 THEN '100-249'
                    WHEN total BETWEEN 250 AND 499 THEN '250-499'
                    WHEN total BETWEEN 500 AND 999 THEN '500-999'
                    WHEN total BETWEEN 1000 AND 2499 THEN '1000-2499'
                    WHEN total BETWEEN 2500 AND 4999 THEN '2500-4999'
                    WHEN total BETWEEN 5000 AND 9999 THEN '5000-9999'
                    WHEN total >= 10000 THEN '10,000+'
                END AS amount_range
            ")
        )
        ->selectRaw('COUNT(*) as number_of_invoices, SUM(total) as total_amount')
        ->groupBy('amount_range')
        ->orderByRaw("FIELD(amount_range, '1-99', '100-249', '250-499', '500-999', '1000-2499', '2500-4999', '5000-9999', '10,000+') ASC") // Change DESC to ASC
        ->get();

            // Debugging Output

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byType") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query  = $query->where('subscriber_id', $user->id)->where('type', 'ap');
            }

            $invoice_interval =  $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('status')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('status')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byClient") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query =  $query->where('user_id', $user->id)->where('type', 'ap');
            }
            $invoice_interval = $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('country')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('country')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byVisaCountry") {
            $query = new Internal_Invoices();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query =  $query->where('user_id', $user->id)->where('type', 'ap');
            }
            $invoice_interval =  $query->whereBetween('created_at', [$startDate, $endDate])
                ->select('to_country')
                ->selectRaw('COUNT(*) as number_of_invoices')
                ->groupBy('to_country')
                ->get();
            return DataTables::of($invoice_interval)
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "byTimeLine") {

            
            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            //     $query = $query->where('referrals.referral_code',$user->referral);
              
            // }

             $query = new Internal_Invoices;

               
            $query1 = new Internal_Invoices;

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               $query = $query->where('user_id', $user->id)->where('type', 'ap');
               $query1 = $query1->where('user_id', $user->id)->where('type', 'ap');
            }

            if($user->user_type == 'admin') {
                $query = $query->where('type', 'ap');
               $query1 = $query1->where('type', 'ap');
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('internal_invoices.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('internal_invoices.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('internal_invoices.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count internal_invoices without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);

            // $currentYear = date('Y'); // Get the current year

            // // Base query with date range
            // $query = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate])
            //     ->whereYear('created_at', $currentYear);

               
            // $query1 = Internal_Invoices::whereBetween('created_at', [$startDate, $endDate]);

            // // Filter for specific user types
            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //    $query = $query->where('user_id', $user->id);
            //    $query1 = $query1->where('user_id', $user->id);
            // }
            // // Past year data query
            // $pastYearData = $query1->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //     ->groupByRaw("YEAR(created_at)")
            //     ->orderBy('type', 'desc')
            //     ->get();

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;

            // // Weekly Applications
            // $weeklyApplications = $weeklyApplications->selectRaw("
            //     WEEK(created_at) as week_num, 
            //     YEAR(created_at) as year_num, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("WEEK(created_at), YEAR(created_at)")
            //     ->orderByRaw("YEAR(created_at) DESC, WEEK(created_at) DESC")
            //     ->get();

            // // Quarterly Applications
            // $quarterlyApplications = $quarterlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     QUARTER(created_at) as quarter, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), QUARTER(created_at)") // Group by YEAR and QUARTER separately
            //     ->orderByRaw("YEAR(created_at) DESC, QUARTER(created_at) DESC") // Correct order
            //     ->get();

            // // Monthly Applications
            // $monthlyApplications = $monthlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     MONTH(created_at) as month, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), MONTH(created_at)") // Group by YEAR and MONTH
            //     ->orderByRaw("YEAR(created_at) DESC, MONTH(created_at) DESC")
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`, `year_num`, `quarter`, etc.
            // // $formattedData = $formattedData->sortByAsc(function ($item) {
            // //     return isset($item['year_num']) ? intval($item['year_num']) : (isset($item['quarter']) ? intval($item['quarter']) : (isset($item['year']) ? intval($item['year']) : 0));
            // // })->values();

            // // Map and format the data
            // // Sorting by ascending order
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     $sort_value = 0;
            
            //     if (isset($item['quarter'])) {
            //         // Format quarterly data as Q1 2025, Q2 2025, etc.
            //         $item['type'] = 'Q' . $item['quarter'] . ' ' . $item['year'];
            //         $sort_value = (int)$item['year'] * 10 + (int)$item['quarter']; // Sort by year and quarter
            //     } elseif (isset($item['year_num'])) {
            //         // Format weekly data as Week 1 2025, Week 2 2025, etc.
            //         $item['type'] = 'Week ' . $item['week_num'] . ' ' . $item['year_num'];
            //         $sort_value = (int)$item['year_num'] * 100 + (int)$item['week_num']; // Sort by year and week
            //     } elseif (isset($item['month'])) {
            //         // Format monthly data as January 2025, February 2025, etc.
            //         $item['type'] = date('F Y', mktime(0, 0, 0, $item['month'], 1, $item['year']));
            //         $sort_value = strtotime($item['type']); // Use timestamp for month sorting
            //     } elseif (is_numeric($item['type'])) {
            //         // If it's a year, sort by the year directly
            //         $sort_value = (int)$item['type'];
            //     } else {
            //         // Default fallback for other types
            //         $item['type'] = $item['type'] ?? 'Unknown';
            //     }
            
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count'],
            //         'sort_value' => $sort_value, // Add sort_value for sorting
            //     ];
            // });
            

            // // Sort by type in ascending order (Weekly first, then Monthly, Quarterly, and Past Year)
           

            // // Return the formatted data for DataTables

            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyInternal_Invoices = Internal_Invoices::where('subscriber_id', $user->id)->where('type', 'ap')->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyInternal_Invoices = Internal_Invoices::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->where('type', 'ap')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyInternal_Invoices)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
        }
        
    }

    public function paymentReport()
    {
        $user = Auth::user();

        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        $query = new PaymentARs ();
        if (request()->type == "byPaymentMode") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query =  $query->where('subscriber_id', $user->id);
            }
                $applications = $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('type',request()->input('payment_type'))
                    ->selectRaw('payment_mode, COUNT(*) as number_of_payment') // Combine the selects into one statement
                    ->groupBy('payment_mode') // Group by the payment mode
                    ->orderBy('number_of_payment', 'desc')
                    ->get();

            return DataTables::of($applications)
            ->addIndexColumn()
                ->make(true); 
        } elseif (request()->type == 'byPaymentAmount') {
            

            $query = PaymentARs::whereBetween('created_at', [$startDate, $endDate])->where('type',request()->input('payment_type'));
            if (in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']) && $user->user_type == 'Subscriber') {
                $query = $query->where('subscriber_id', $user->id);
            } 
            $paymentAP = $query
            ->selectRaw("
                CASE 
                    WHEN amount BETWEEN 1 AND 99 THEN '1-99'
                    WHEN amount BETWEEN 100 AND 249 THEN '100-249'
                    WHEN amount BETWEEN 250 AND 499 THEN '250-499'
                    WHEN amount BETWEEN 500 AND 999 THEN '500-999'
                    WHEN amount BETWEEN 1000 AND 2499 THEN '1000-2499'
                    WHEN amount BETWEEN 2500 AND 4999 THEN '2500-4999'
                    WHEN amount BETWEEN 5000 AND 9999 THEN '5000-9999'
                    WHEN amount >= 10000 THEN '10,000+'
                END AS amount_range
            ")
           
            ->selectRaw('COUNT(*) as number_of_invoices')
            ->groupBy('amount_range')
            ->orderByRaw("FIELD(amount_range, '1-99', '100-249', '250-499', '500-999', '1000-2499', '2500-4999', '5000-9999', '10,000+') DESC")
            ->get();
            
            

            return DataTables::of($paymentAP)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == 'byOutstandingAmount') {
            
            $query = PaymentARs::where('type',request()->input('payment_type'))
            ->whereNotNull('client_id')
            ->whereBetween('created_at', [$startDate, $endDate]);
            if (in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']) && $user->user_type == 'Subscriber') {
                $query = $query->where('subscriber_id', $user->id);
                    
            } 
            

              $paymentAP = $query->with([
                                'client', // Eager load client name
                                'application' // Eager load application name
                            ])
                            ->where('type',request()->input('payment_type'))
                            ->select(
                                'client_id',
                                'application_id',
                                'service_description',
                                'amount',
                                'paid_amount',
                                'payment_date',
                                DB::raw('MAX(created_at) as created_at'), // ✅ Use MAX() to avoid GROUP BY error
                                DB::raw('SUM(amount - paid_amount) as amount_to_pay') // ✅ Aggregate sum correctly
                            )
                            ->groupBy('client_id', 'application_id', 'service_description','amount','paid_amount','payment_date') // ✅ Group by necessary columns
                            ->havingRaw('SUM(amount - paid_amount) > 0')
                            ->orderBy('created_at', 'desc')
                            ->get();
            
                            return DataTables::of($paymentAP)
                            ->addIndexColumn()
                            ->addColumn('application_name', function ($row) {
                                return $row->application
                                    ? $row->application->application_name . '(' . $row->application->application_id . ')'  // ✅ Use application name if exists
                                    : ($row->service_description ?? 'N/A'); // ✅ Otherwise, use service_description
                            })
                            ->addColumn('client_name', function ($row) {
                                return $row->client
                                    ? $row->client->name . ' (' . $row->client_id . ')'
                                    : 'Unknown Client'; // ✅ Prevents undefined error
                            })
            
                            ->addColumn('due_date', function ($row) {
                                return \Carbon\Carbon::parse($row->payment_date)->format('d-m-Y');
                            })
            
                            ->make(true);
        } elseif (request()->type == 'byInvoiceType') {

            $query = new PaymentARs();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query  = $query ->where('subscriber_id', $user->id);
                    
            }
            $invoices = $query->where('type',request()->input('payment_type'))
            ->select(
                'type',
                DB::raw('COUNT(*) as number_of_invoices'),
                DB::raw('SUM(amount) as total_amount') // Summing the amount per payment mode
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('type') // Group by payment_mode
            ->get();

            
            return DataTables::of($invoices)
            ->editColumn('type', function ($row) {
                // Format the `created_at` column
                return  ($row->type == 'ar') ? 'AR (Payments Received)' :' AP (Payments Made )';
            })
            ->addIndexColumn()

            ->make(true);
        } elseif (request()->type == "byClientCountry") {
            $query =  new PaymentARs();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query =  $query->where('payment_ar.subscriber_id', $user->id);
            }
            
                $invoice_interval =    $query ->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                    ->whereNotNull('payment_ar.client_id')
                    ->where('payment_ar.type',request()->input('payment_type'))
                    ->join('applications', 'applications.id', '=', 'payment_ar.application_id') 
                    ->join('clients', 'clients.id', '=', 'payment_ar.client_id') // Join with applications
                    ->selectRaw('applications.application_country as country') // Use the county column from applications
                    ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                    ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                    ->groupBy('applications.application_country') 
                    ->get();
            
            return DataTables::of($invoice_interval)
            ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byVisaCountry") {
            $query =  new PaymentARs();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query =  $query->where('payment_ar.subscriber_id', $user->id);
            }

                $invoice_interval =  $query->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                    ->whereNotNull('payment_ar.application_id')
                    ->where('payment_ar.type',request()->input('payment_type'))
                    ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                    ->selectRaw('applications.visa_country as to_country') // Use the county column from applications
                    ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                    ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                    ->groupBy('applications.visa_country') // Group by applications.county
                    ->get();
            return DataTables::of($invoice_interval)
            ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == "byApplicationType") {
            $query =  new PaymentARs();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query  =     $query->where('payment_ar.subscriber_id', $user->id);
                
                   
            }
                $invoice_interval =    $query->whereBetween('payment_ar.created_at', [$startDate, $endDate])
                    ->whereNotNull('payment_ar.application_id')
                    ->where('payment_ar.type',request()->input('payment_type'))
                    ->join('applications', 'applications.id', '=', 'payment_ar.application_id') // Join with applications
                    ->selectRaw('applications.application_name as application_name') // Use the county column from applications
                    ->selectRaw('COUNT(payment_ar.id) as number_of_payment') // Count the number of invoices
                    ->selectRaw('SUM(payment_ar.amount) as total_invoice_sum') // Calculate the sum of invoice totals
                    ->groupBy('applications.application_name') // Group by applications.county
                    ->get();
            return DataTables::of($invoice_interval)
            ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "byTimeLine") {

            
            
            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            //     $query = $query->where('referrals.referral_code',$user->referral);
              
            // }

             $query = new PaymentARs;

               
            $query1 = new PaymentARs;

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               $query = $query->where('subscriber_id', $user->id);
               $query1 = $query1->where('subscriber_id', $user->id);
            }

            if($user->user_type == 'admin') {
                $query = $query;
               $query1 = $query1;
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('payment_ar.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('payment_ar.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('payment_ar.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('payment_ar.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('payment_ar.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count payment_ar without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);


            // $currentYear = date('Y'); // Get the current year

            // // Base query with date range
            // $query =   PaymentARs::whereBetween('created_at', [$startDate, $endDate])
            //     ->whereYear('created_at', $currentYear)->where('type',request()->input('payment_type'));

            // // Filter for specific user types
            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //     $query->where('subscriber_id', $user->id);
            // }

            // // Past year data query
            // $pastYearData = PaymentARs::whereYear('created_at', '<', $currentYear)
            //     ->when($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']), function ($query) use ($user) {
            //         return $query->where('subscriber_id', $user->id);
            //     })
            //     ->where('type',request()->input('payment_type'))
            //     ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //     ->groupByRaw("YEAR(created_at)")
            //     ->orderBy('type', 'desc')
            //     ->get();

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;

            // // Weekly Applications
            // $weeklyApplications = $weeklyApplications->selectRaw("
            //     WEEK(created_at) as week_num, 
            //     YEAR(created_at) as year_num, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("WEEK(created_at), YEAR(created_at)")
            //     ->orderByRaw("YEAR(created_at) DESC, WEEK(created_at) DESC")
            //     ->get();

            // // Quarterly Applications
            // $quarterlyApplications = $quarterlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     QUARTER(created_at) as quarter, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), QUARTER(created_at)") // Group by YEAR and QUARTER separately
            //     ->orderByRaw("YEAR(created_at) DESC, QUARTER(created_at) DESC") // Correct order
            //     ->get();

            // // Monthly Applications
            // $monthlyApplications = $monthlyApplications->selectRaw("
            //     YEAR(created_at) as year, 
            //     MONTH(created_at) as month, 
            //     COUNT(*) as count
            // ")
            //     ->groupByRaw("YEAR(created_at), MONTH(created_at)") // Group by YEAR and MONTH
            //     ->orderByRaw("YEAR(created_at) DESC, MONTH(created_at) DESC")
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`, `year_num`, `quarter`, etc.
            // // $formattedData = $formattedData->sortByAsc(function ($item) {
            // //     return isset($item['year_num']) ? intval($item['year_num']) : (isset($item['quarter']) ? intval($item['quarter']) : (isset($item['year']) ? intval($item['year']) : 0));
            // // })->values();

            // // Map and format the data
            // // Sorting by ascending order
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     $sort_value = 0;
            
            //     if (isset($item['quarter'])) {
            //         // Format quarterly data as Q1 2025, Q2 2025, etc.
            //         $item['type'] = 'Q' . $item['quarter'] . ' - ' . $item['year'];
            //         $sort_value = (int)$item['year'] * 10 + (int)$item['quarter']; // Sort by year and quarter
            //     } elseif (isset($item['year_num'])) {
            //         // Format weekly data as Week 1 2025, Week 2 2025, etc.
            //         $item['type'] = 'Week ' . $item['week_num'] . ' - ' . $item['year_num'];
            //         $sort_value = (int)$item['year_num'] * 100 + (int)$item['week_num']; // Sort by year and week
            //     } elseif (isset($item['month'])) {
            //         // Format monthly data as January 2025, February 2025, etc.
            //         $item['type'] = date('F - Y', mktime(0, 0, 0, $item['month'], 1, $item['year']));
            //         $sort_value = strtotime($item['type']); // Use timestamp for month sorting
            //     } elseif (is_numeric($item['type'])) {
            //         // If it's a year, sort by the year directly
            //         $sort_value = (int)$item['type'];
            //     } else {
            //         // Default fallback for other types
            //         $item['type'] = $item['type'] ?? 'Unknown';
            //     }
            
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count'],
            //         'sort_value' => $sort_value, // Add sort_value for sorting
            //     ];
            // });
            

            // // Sort by type in ascending order (Weekly first, then Monthly, Quarterly, and Past Year)
           

            // // Return the formatted data for DataTables

            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "byYear") {

            $query =   PaymentARs::whereBetween('created_at', [$startDate, $endDate])->where('type',request()->input('payment_type'));

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('subscriber_id', $user->id);
            }

            
            $unionQuery = $query->whereBetween('created_at', [$startDate, $endDate])->select(
                DB::raw('YEAR(created_at) AS year'), // Extract year from referral creation date
                DB::raw('COUNT(*) AS count') // Count referrals per year
            )
            ->groupBy(DB::raw('YEAR(created_at)')) // Group by year
            ->orderBy('year', 'desc') // Order from newest to oldest
            ->get();
            return DataTables::of($unionQuery)
            ->addIndexColumn()
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
            $lastWeekStart = $today->copy()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = $today->copy()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = $today->copy()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = $today->copy()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = $today->copy()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = $today->copy()->subYear()->year;

            $query = Internal_communications::selectRaw("
                    CASE 
                        WHEN DATE(created_at) = ? THEN 'Today'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Week'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Month'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Quarter'
                        WHEN YEAR(created_at) = ? THEN 'Last Year'
                        ELSE 'Since Inception'
                    END AS period, 
                    COUNT(*) AS total_activities
                ", [$today, $lastWeekStart, $lastWeekEnd, $lastMonthStart, $lastMonthEnd, $lastQuarterStart, $lastQuarterEnd, $lastYear])
                ->groupBy('period')
                ->orderByRaw("FIELD(period, 'Today', 'Last Week', 'Last Month', 'Last Quarter', 'Last Year', 'Since Inception')");

            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('subscriber_id', $user->id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
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

            // Build query
            $query = Client_discussions::selectRaw("
                    CASE
                        WHEN DATE(created_at) = ? THEN 'Today'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Week'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Month'
                        WHEN created_at BETWEEN ? AND ? THEN 'Last Quarter'
                        WHEN YEAR(created_at) = ? THEN 'Last Year'
                        ELSE 'Since Inception'
                    END AS period,
                    COUNT(*) AS total_activities
                ", [
                $today,
                $lastWeekStart,
                $lastWeekEnd,
                $lastMonthStart,
                $lastMonthEnd,
                $lastQuarterStart,
                $lastQuarterEnd,
                $lastYear
            ])
                ->where('subscriber_id', $user->id) // Filter by subscriber_id
                ->groupBy('period')
                ->orderByRaw("FIELD(period, 'Today', 'Last Week', 'Last Month', 'Last Quarter', 'Last Year', 'Since Inception')");

            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('subscriber_id', $user->id);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'meetingNotes') {
            
            $query = new Client_discussions();
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('subscriber_id', $user->id);
            }
            $meetingNotes = $query->whereHas('user', function ($query) {
                $query->where('user_type', 'User'); 
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('user.id')
            ->map(function ($items) {
                return [
                    'username' => $items->first()->user->name . ' (' . $items->first()->user->id . ')',
                    'communication_summary' => $items->groupBy('communication_type')
                        ->map(fn($group, $type) => "$type (" . $group->count() . ")")
                        ->implode(', '),
                    'total_discussions' => $items->count()
                ];
            })->values();
            return DataTables::of($meetingNotes)
                ->addIndexColumn()
                ->make(true);
        } elseif (request()->type == 'messagesSentByUser') {
            $query = Internal_communications::select('user_id')
                    ->selectRaw('COUNT(*) as number_of_communication');
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query  =  $query->where('subscriber_id', $user->id);
            } 
                $cd = $query->whereBetween('created_at', [$startDate,  $endDate])
                    ->groupBy('user_id')->get();
            
            return DataTables::of($cd)
                ->addIndexColumn()
                ->editColumn('user_id', function ($row) {
                    $user = User::find($row->user_id);
                    if (!empty($user)) {
                        return $user->name;
                    } else {
                        return "";
                    }
                })
                ->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyClient_discussions = Client_discussions::where('user_id', $user->id)->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyClient_discussions = Client_discussions::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyClient_discussions)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
        }
    }


    public function walletReport()
    {
        $user = Auth::user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == 'byWallets') {

            $query = Referrals::select(
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
                    END AS payment_amount_range"
                )
            )
            ->selectRaw('COUNT(*) as number_of_wallet')
            ->groupBy('payment_amount_range');
            
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
                $query->where('userid', $user->id);
            } elseif ($user->user_type == 'admin') {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            
            $invoices = $query->get();


            return DataTables::of($invoices)
            ->addIndexColumn()
            ->make(true);
        } elseif (request()->type == 'byTransactions') {

             $query = Referrals::whereNotNull('type')
                    ->select(
                        'type'
                        )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('COUNT(*) as number_of_communication');
                    
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query = $query->where('userid', $user->id);
                   
            }
            $cd = $query->groupBy('type');
            return DataTables::of($cd)
            ->addIndexColumn()
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
                ->make(true);
            
        } elseif (request()->type == 'byWalletYear') {
            $query =  new Referrals();
            // ->where('users.user_type', 'Subscriber')
            
            if (($user->membership == 'Adwiseri' || $user->membership == 'Adwiseri+' || $user->membership == 'Enterprise') && $user->user_type == 'Subscriber') {
                // Apply condition for 'Subscriber' user type and membership types
             $query =   $query->where('userid',$user->id);
            }
            $unionQuery = $query->whereBetween('created_at', [$startDate, $endDate])->select(
                DB::raw('YEAR(created_at) AS year'), // Extract year from referral creation date
                DB::raw('COUNT(*) AS count') // Count referrals per year
            )
            ->whereNull('debit_amount')
            ->groupBy(DB::raw('YEAR(created_at)')) // Group by year
            ->orderBy('year', 'desc') // Order from newest to oldest
            ->get();
            


            return DataTables::of($unionQuery)
                ->addIndexColumn()
                ->make(true);
        }else if (request()->type == "byDates") {

            $currentYear = date('Y'); // Get the current year

            // Base query with date range
            $query =  Referrals::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('debit_amount')
                ->whereYear('created_at', $currentYear);

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               
                
                $query = $query->where('userid', $user->id);
                $pastYearData = Referrals::whereYear('created_at', '<', $currentYear)
                ->where('userid', $user->id)
                // ->when($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']), function ($query) use ($user) {
                //     return $query->where('user_id', $user->id);
                // })
                ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
                ->whereNull('debit_amount')
                ->groupByRaw("YEAR(created_at)")
                ->orderBy('type', 'desc')
                ->get();

            }else{
                $pastYearData = Referrals::whereYear('created_at', '<', $currentYear)
                // ->when($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']), function ($query) use ($user) {
                //     return $query->where('user_id', $user->id);
                // })
                ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
                ->whereNull('debit_amount')
                ->groupByRaw("YEAR(created_at)")
                ->orderBy('type', 'desc')
                ->get();

            }

            
            // Queries for different time groupings
            $weeklyApplications = clone $query;
            $quarterlyApplications = clone $query;
            $monthlyApplications = clone $query;

            // Weekly Applications
            $weeklyApplications = $weeklyApplications->selectRaw("
                WEEK(created_at) as week_num, 
                YEAR(created_at) as year_num, 
                COUNT(*) as count
            ")
                ->groupByRaw("WEEK(created_at), YEAR(created_at)")
                ->orderByRaw("YEAR(created_at) DESC, WEEK(created_at) DESC")
                ->get();

            // Quarterly Applications
            $quarterlyApplications = $quarterlyApplications->selectRaw("
                YEAR(created_at) as year, 
                QUARTER(created_at) as quarter, 
                COUNT(*) as count
            ")
                ->groupByRaw("YEAR(created_at), QUARTER(created_at)") // Group by YEAR and QUARTER separately
                ->orderByRaw("YEAR(created_at) DESC, QUARTER(created_at) DESC") // Correct order
                ->get();

            // Monthly Applications
            $monthlyApplications = $monthlyApplications->selectRaw("
                YEAR(created_at) as year, 
                MONTH(created_at) as month, 
                COUNT(*) as count
            ")
                ->groupByRaw("YEAR(created_at), MONTH(created_at)") // Group by YEAR and MONTH
                ->orderByRaw("YEAR(created_at) DESC, MONTH(created_at) DESC")
                ->get();

            // Merge all data into a single collection
            $formattedData = collect()
                ->merge($weeklyApplications)
                ->merge($quarterlyApplications)
                ->merge($monthlyApplications)
                ->merge($pastYearData);

            // Sort correctly by numeric value in `type`, `year_num`, `quarter`, etc.
            // $formattedData = $formattedData->sortByAsc(function ($item) {
            //     return isset($item['year_num']) ? intval($item['year_num']) : (isset($item['quarter']) ? intval($item['quarter']) : (isset($item['year']) ? intval($item['year']) : 0));
            // })->values();

            // Map and format the data
            // Sorting by ascending order
            $formattedData = $formattedData->map(function ($item, $index) {
                $sort_value = 0;
            
                if (isset($item['quarter'])) {
                    // Format quarterly data as Q1 2025, Q2 2025, etc.
                    $item['type'] = 'Q' . $item['quarter'] . ' ' . $item['year'];
                    $sort_value = (int)$item['year'] * 10 + (int)$item['quarter']; // Sort by year and quarter
                } elseif (isset($item['year_num'])) {
                    // Format weekly data as Week 1 2025, Week 2 2025, etc.
                    $item['type'] = 'Week ' . $item['week_num'] . ' ' . $item['year_num'];
                    $sort_value = (int)$item['year_num'] * 100 + (int)$item['week_num']; // Sort by year and week
                } elseif (isset($item['month'])) {
                    // Format monthly data as January 2025, February 2025, etc.
                    $item['type'] = date('F Y', mktime(0, 0, 0, $item['month'], 1, $item['year']));
                    $sort_value = strtotime($item['type']); // Use timestamp for month sorting
                } elseif (is_numeric($item['type'])) {
                    // If it's a year, sort by the year directly
                    $sort_value = (int)$item['type'];
                } else {
                    // Default fallback for other types
                    $item['type'] = $item['type'] ?? 'Unknown';
                }
            
                return [
                    'DT_RowIndex' => $index + 1,
                    'type' => $item['type'],
                    'count' => $item['count'],
                    'sort_value' => $sort_value, // Add sort_value for sorting
                ];
            });
            

            // Sort by type in ascending order (Weekly first, then Monthly, Quarterly, and Past Year)
           

            // Return the formatted data for DataTables

            return DataTables::of($formattedData)->make(true);
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
                ->whereBetween('created_at', [$startDate,  $endDate])
                ->groupBy('time_interval');

            return DataTables::of($timeTaken)
                ->make(true);
        } elseif (request()->type == 'bySupportStaff') {
            $cd = Tickets::select(
                'user_id',
                DB::raw('COUNT(id) AS no_of_tickets_solved'),
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, `created_at`, `updated_at`)) / 3600 AS avg_time_taken_hours')
            )
                ->whereBetween('created_at', [$startDate,  $endDate])
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
        
        $query =  Referrals::join('users', 'referrals.userid', '=', 'users.id')
            ->where('users.user_type', 'Subscriber')
            ->whereNull('referrals.debit_amount')
            ->where('referrals.type', 'Referral Commission');

               
        if (request()->type == "subscribers") {
            
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query = $query->where('referrals.referral_code',$user->referral);
            }
            $referrals =  $query->whereBetween('referrals.created_at', [$startDate, $endDate])->select(
                        'users.sub_category',
                        DB::raw('COUNT(users.id) as user_count') // Count users per sub_category
                        )->groupBy('users.sub_category') // Group by sub_category
                        ->orderBy('user_count', 'desc') // Order by user count (optional)
                        ->get();
            return DataTables::of($referrals)
            ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "subscriberType") {
           
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('referrals.referral_code',$user->referral);
            }
            $referrals =  $query->whereBetween('referrals.created_at', [$startDate, $endDate])->select(
                'users.category',
                DB::raw('COUNT(users.id) as user_count') // Count users per sub_category
                )->groupBy('users.category') // Group by sub_category
                ->orderBy('user_count', 'desc') // Order by user count (optional)
                ->get();
            


            return DataTables::of($referrals)
                // ->editColumn('type', function ($row) {
                //     return !empty($row->type) ? $row->type : "N/A";
                // })
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "subscribedPlan") {
           
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('referrals.referral_code',$user->referral);
            }
            $referrals =  $query->whereBetween('referrals.created_at', [$startDate, $endDate])->select(
                'users.membership',
                DB::raw('COUNT(users.id) as user_count') // Count users per sub_category
                )->groupBy('users.membership') // Group by sub_category
                ->orderBy('user_count', 'desc') // Order by user count (optional)
                ->get();
            


            return DataTables::of($referrals)
                // ->editColumn('type', function ($row) {
                //     return !empty($row->type) ? $row->type : "N/A";
                // })
                ->addIndexColumn()
                ->make(true);
        }else if (request()->type == "byTimeline") {


            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

                $query = $query->where('referrals.referral_code',$user->referral);
              
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('referrals.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('referrals.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('referrals.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('referrals.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('referrals.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count referrals without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "yearly") {

            if (($user->membership == 'Adwiseri' || $user->membership == 'Adwiseri+' || $user->membership == 'Enterprise') && $user->user_type == 'Subscriber') {
                // Apply condition for 'Subscriber' user type and membership types
                $query->where('referrals.referral_code', $user->referral); 
            }
            
            $unionQuery = $query->whereBetween('referrals.created_at', [$startDate, $endDate])->select(
                DB::raw('YEAR(referrals.created_at) AS year'), // Extract year from referral creation date
                DB::raw('COUNT(*) AS count') // Count referrals per year
            )
            ->groupBy(DB::raw('YEAR(referrals.created_at)')) // Group by year
            ->orderBy('year', 'desc') // Order from newest to oldest
            ->get();
            return DataTables::of($unionQuery)
            ->addIndexColumn()
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
                ->withCount(['getReferrals as total_referrals' => function ($query) {
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
                ->withCount(['getReferrals as total_referrals' => function ($query) {
                    $query->whereNull('referrals.type')->whereNull('debit_amount');
                }])
                ->get();

            return DataTables::of($affiliates)
                ->editColumn('total_commission', function ($row) {
                    return round($row->total_commission, 2);
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
                ->with(['user' => function ($query) {
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


            return DataTables::of($referrals)

                ->make(true);
        } elseif (request()->type == "currentWalletCredits") {


            $affiliates =  User::where('user_type', 'Affiliate')
                ->where('wallet', '>', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('getReferrals', function ($query) {
                    $query->whereNull('referrals.type') // Ensure type is null
                        ->whereNull('referrals.debit_amount'); // Ensure debit_amount is null
                })
                ->withCount(['getReferrals as total_referrals' => function ($query) {
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
                return  $row->served_by ? $row->user->name : '';
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
                ->where('status', '!=', 'true') // Ensure status is appropriately filtered
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

            return DataTables::of($demorequest)
                ->make(true);
        }
    }

    public function documentReport()
    {
        $user = auth()->user();
        $startDate = Carbon::createFromFormat('d-m-Y', request()->input('startDate'))->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', request()->input('endDate'))->endOfDay();
        if (request()->type == "byApplication") {
            $query = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
            ->join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Join applications table
            ->join('users', 'client_docs.user_id', '=', 'users.id'); // Join users table
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
           
                    $query = $query->where('client_docs.user_id', $user->id);
                    
            } 
                $documents =$query->select(
                                'users.name as sub_name', // Subscriber name
                                'users.id as sub_id', // Subscriber ID
                                'clients.name as client_name', // Client name
                                'clients.id as client_id', // Client ID
                                'applications.application_name', // Application name
                                'applications.application_id as application_id', // Application ID
                                DB::raw('COUNT(client_docs.id) as no_of_docs'), // Count the number of client documents
                                DB::raw('COUNT(DISTINCT applications.id) as no_of_applications') // Count the number of unique applications
                            )
                    
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                    ->whereNotNull('client_docs.application_id') // Ensure application_id is not null
                    
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
            


            return DataTables::of($documents)
                ->addIndexColumn()
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
        } elseif (request()->type == "byClient") {
            $query= Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id') // Join clients table
            ->join('users', 'client_docs.user_id', '=', 'users.id'); // Join users table
    
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $query = $query->where('client_docs.user_id', $user->id);
            } 
                $documents = $query ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
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

            return DataTables::of($documents)
                ->addIndexColumn()
                ->editColumn('subscriber', function ($row) {
                    return $row->sub_name . ' (' . $row->sub_id . ')';
                })
                ->editColumn('client', function ($row) {
                    return $row->client_name . ' (' . $row->client_id . ')';
                })

                ->make(true);
        } elseif (request()->type == "bySubscriber") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table

                    ->whereBetween('client_docs.created_at', [$startDate, $endDate]) // Filter by date range
                    ->where('client_docs.user_id', $user->id)
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
            } else {
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
                ->addIndexColumn()
                ->editColumn('subscriber', function ($row) {
                    return $row->sub_name . ' (' . $row->sub_id . ')';
                })

                ->make(true);
        } elseif (request()->type == "bySize") {
            $query = Client_Docs::join('clients', 'client_docs.client_id', '=', 'clients.id')
            
                ->join('applications', 'client_docs.application_id', '=', 'applications.application_id') // Joining applications table
                ->select(
                    'client_docs.doc_file',
                    'client_docs.client_id',
                    'clients.name as client_name',
                    'applications.application_name', // Select the application name
                    'applications.application_id as application_id', // Select the application ID
                    'client_docs.doc_name',
                    'client_docs.id'
                )
                ->whereBetween('client_docs.created_at', [$startDate, $endDate]);

                if (in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise']) && $user->user_type == 'Subscriber') {
                    $query->where('client_docs.user_id', $user->id);
                }

                $documents = $query->get();
            $filesWithSize = [];
            foreach ($documents as $doc) {
                // Construct the file path
                $filePath = public_path('web_assets/users/client' . $doc->client_id . '/docs/' . $doc->doc_file);
            
                // Check if the file exists
                if (file_exists($filePath)) {
                    // Get file size in bytes
                    $fileSize = filesize($filePath);
            
                    // Format the file size
                    $formattedSize = $fileSize < 1024
                        ? $fileSize . ' B'
                        : ($fileSize < 1048576
                            ? round($fileSize / 1024, 2) . ' KB'
                            : ($fileSize < 1073741824
                                ? round($fileSize / 1048576, 2) . ' MB'
                                : round($fileSize / 1073741824, 2) . ' GB'));
            
                    // Add the document and its size to the array
                    $filesWithSize[] = [
                        'client_name' => $doc->client_name.'('.$doc->client_id.')',
                        'application_name' => $doc->application_name.'('.$doc->application_id.')', // Application name
                        'application_id' => $doc->application_id, // Application ID
                        'docs_name' => $doc->doc_name . ' (' . $doc->id . ')',
                        'doc_file' => $doc->doc_file,
                        'file_size' => $fileSize, // Size in bytes
                        'formatted_size' => $formattedSize, // Human-readable size
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
        } elseif (request()->type == "byFiletype") {

            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $documents = Client_Docs::join('users', 'client_docs.user_id', '=', 'users.id') // Join users table
                    ->select(
                        'client_docs.doc_file', // Document file path
                        'client_docs.client_id', // Client ID for file path construction
                        'users.name as user_name', // User name
                        DB::raw("SUBSTRING_INDEX(client_docs.doc_file, '.', -1) as file_type") // Extract file type
                    )
                    ->where('client_docs.user_id', $user->id)
                    ->whereBetween('client_docs.created_at', [$startDate, $endDate])
                    ->get();
            } else {
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
                ->addIndexColumn()
                ->make(true);
        } else if (request()->type == "byTimeLine") {

            
            
            $today = Carbon::today();
            $lastWeekStart = Carbon::today()->subDays(6)->startOfDay();
            $lastWeekEnd = Carbon::yesterday()->endOfDay();
            $lastMonthStart = Carbon::today()->subMonthNoOverflow()->startOfMonth();
            $lastMonthEnd = Carbon::today()->startOfMonth()->subDay()->endOfDay();
            $lastQuarterStart = Carbon::today()->subQuarterNoOverflow()->startOfQuarter();
            $lastQuarterEnd = Carbon::today()->startOfQuarter()->subDay()->endOfDay();
            $lastYear = Carbon::today()->subYear()->year;
            // if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {

            //     $query = $query->where('referrals.referral_code',$user->referral);
              
            // }

             $query = new Client_Docs;

               
            $query1 = new Client_Docs;

            // Filter for specific user types
            if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
               $query = $query->where('user_id', $user->id);
               $query1 = $query1->where('user_id', $user->id);
            }

            if($user->user_type == 'admin') {
                $query = $query;
               $query1 = $query1;
            }


       
      
            $queries = [
                ['year' => 'Today', 'count' => $query->clone()->whereDate('client_docs.created_at', $today)->count()],
                
                ['year' => 'Last Week', 'count' => $query->clone()->whereBetween('client_docs.created_at', [$lastWeekStart, $lastWeekEnd])->count()],
                
                ['year' => 'Last Month', 'count' => $query->clone()->whereBetween('client_docs.created_at', [$lastMonthStart, $lastMonthEnd])->count()],
                
                ['year' => 'Last Quarter', 'count' => $query->clone()->whereBetween('client_docs.created_at', [$lastQuarterStart, $lastQuarterEnd])->count()],
                
                ['year' => 'Last Year', 'count' => $query->clone()->whereYear('client_docs.created_at', $lastYear)->count()],
                
                ['year' => 'Since Inception', 'count' => $query->count()] // Directly count client_docs without date filters
            ];
            
            return DataTables::of($queries)
                ->addIndexColumn()
                ->make(true);


            // $currentYear = date('Y'); // Get the current year
            // $query = Client_Docs::query()
            //     ->whereBetween('created_at', [$startDate, $endDate])
            //     ->whereYear('created_at', $currentYear);

            // if ($user->user_type == 'Subscriber' && in_array($user->membership, ['Adwiseri', 'Adwiseri+', 'Enterprise'])) {
            //     $query->where('user_id', $user->id);
            //     $pastYearData = Client_Docs::query()
            //         ->whereYear('created_at', '<', $currentYear)
            //         ->where('user_id', $user->id)
            //         ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //         ->groupBy('type')
            //         ->orderBy('type', 'desc')
            //         ->get();
            // } else {
            //     $pastYearData = Client_Docs::query()
            //         ->whereYear('created_at', '<', $currentYear)
            //         ->selectRaw("YEAR(created_at) as type, COUNT(*) as count")
            //         ->groupBy('type')
            //         ->orderBy('type', 'desc')
            //         ->get();
            // }

            // // Queries for different time groupings
            // $weeklyApplications = clone $query;
            // $quarterlyApplications = clone $query;
            // $monthlyApplications = clone $query;


            // // Fetch grouped data
            // $weeklyApplications = $weeklyApplications->selectRaw("CONCAT('Week ', WEEK(created_at), ' (', YEAR(created_at), ')') as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // $quarterlyApplications = $quarterlyApplications->selectRaw("CONCAT('Q', QUARTER(created_at), ' ', YEAR(created_at)) as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // $monthlyApplications = $monthlyApplications->selectRaw("DATE_FORMAT(created_at, '%M %Y') as type, COUNT(*) as count")
            //     ->groupByRaw('type')
            //     ->get();

            // // Merge all data into a single collection
            // $formattedData = collect()
            //     ->merge($weeklyApplications)
            //     ->merge($quarterlyApplications)
            //     ->merge($monthlyApplications)
            //     ->merge($pastYearData);

            // // Sort correctly by numeric value in `type`
            // $formattedData = $formattedData->sortByDesc(function ($item) {
            //     return intval(preg_replace('/\D/', '', $item['type']));
            // })->values();

            // // Assign `DT_RowIndex` after sorting
            // $formattedData = $formattedData->map(function ($item, $index) {
            //     return [
            //         'DT_RowIndex' => $index + 1,
            //         'type' => $item['type'],
            //         'count' => $item['count']
            //     ];
            // });


            // return DataTables::of($formattedData)->make(true);
        } else if (request()->type == "yearly") {
            if (($user->membership == 'Adwiseri' || $user->membership == "Adwiseri+" || $user->membership == "Enterprise") && $user->user_type == 'Subscriber') {
                $yearlyClient_Docs = Client_Docs::where('user_id', $user->id)->selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }
            if ($user->user_type == 'admin') {
                $yearlyClient_Docs = Client_Docs::selectRaw('YEAR(created_at) as year, COUNT(*) as year_count')->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year')->get();
            }

            return  DataTables::of($yearlyClient_Docs)
                ->addIndexColumn()
                ->editColumn('year', function ($row) {
                     return  $row->year;
                })
                ->make(true);;
        }
    }
}
